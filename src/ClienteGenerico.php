<?php

/**
 *  Classe genérica de interação com webservice.
 * 
 * @package     crphp
 * @subpackage  webservice
 * @author      Fábio J L Ferreira <contato@fabiojanio.com>
 * @license     MIT (consulte o arquivo license disponibilizado com este pacote)
 * @copyright   (c) 2016, Fábio J L Ferreira
 */

namespace Crphp\Webservice;

use Crphp\Webservice\Traits\FormatarXML;

class ClienteGenerico implements iRequestXML
{
    use FormatarXML;

    /**
     * Armazena uma instância de Curl.
     * 
     * @var resource
     */
    private $curl;
    
    /**
     * Armazena o conteúdo retornado pela consulta.
     *
     * @var string
     */
    private $content;
    
    /**
     * Armazena as informações referentes a requisição.
     * 
     * @var array
     */
    private $info;

    /**
     * Cabeçalho a ser enviado.
     *
     * @var array
     */
    private $header;

    /**
     * Conteúdo (xml) bruto a ser enviado.
     *
     * @var string
     */
    private $rawContent;

    /**
     * Atribui alguns valores considerados padrão.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->curl = curl_init();

        /** @see http://php.net/manual/pt_BR/function.curl-setopt.php */
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($this->curl, CURLOPT_POST, true);
    }

    /**
     * Define o tempo máximo do pedido.
     *
     * @param   int     $timeout    Em segundos.
     *
     * @return  \Crphp\Webservice\ClienteGenerico
     */    
    public function setTimeOut($timeout = 180)
    {
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $timeout);

        return $this;
    }

    /**
     * Define o agente a ser utilizado.
     * 
     * @param   string  $agent
     *
     * @return  \Crphp\Webservice\ClienteGenerico
     */  
    public function setAgent($agent = "PHP ClienteGenerico")
    {
        curl_setopt($this->curl, CURLOPT_USERAGENT, $agent);

        return $this;
    }

    /**
     * Adiciona o conteúdo e atribui um cabeçalho a requisição.
     *
     * @param   string  $target
     * @param   array   $header
     * @param   array   $increment
     *
     * @return  \Crphp\Webservice\ClienteGenerico
     */
    public function setRequest($target, array $header = null, array $increment = null)
    {
        if (!$header) {
            $this->header = [
                'Content-type: text/xml;charset=UTF-8',
                'Accept: text/xml',
                'Cache-Control: no-cache',
                'Pragma: no-cache',
            ];

            if ($increment) {
                $this->header = array_merge($this->header, $increment);
            }
        }

        curl_setopt($this->curl, CURLOPT_URL, $target);

        return $this;
    }

    /**
     * * Dispara a consulta contra o serviço informado.
     *
     * @param $service
     * @param $arguments
     *
     * @return void
     */
    public function doRequest($service, $arguments)
    {
        $incrementHeader = [
            'Content-length: ' . strlen($arguments),
            'SOAPAction: ' . $service,
        ];

        $this->rawContent = $arguments;

        $header = array_merge($this->header, $incrementHeader);

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $arguments);

        $this->content = curl_exec($this->curl);
        $this->info = curl_getinfo($this->curl);

        curl_close($this->curl);
    }

    /**
     * Define regras de redirecionamento de URL, tais como se deve serguir redirecionamentos, total
     * de redirecionamentos aceitos e se deve ser aplicado refresh caso um redirect seja seguido.
     * 
     * @param   bool  $redirect
     * @param   int   $redirectNum
     * @param   bool  $refresh
     *
     * @return  \Crphp\Webservice\ClienteGenerico
     */  
    public function setRedirect($redirect = true, $redirectNum = 5, $refresh = true)
    {
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, $redirect);
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, $redirectNum);
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, $refresh);

        return $this;
    }
    
    /**
     * Informações da requisição obtidos do método curl_getinfo.
     * 
     * @see http://php.net/manual/pt_BR/function.curl-getinfo.php
     * 
     * @return  array
     */
    public function getHeader()
    {
        $raw = $this->info;

        return [
            'raw_info'      => $raw,
            'status'        => $raw['http_code'],
            'time'          => round($raw['total_time'] * 1000) . ' ms',
            'size'          => round($raw['size_upload'] / 1024, 2) . ' KB',
        ];
    }

    /**
     * Retorna o xml submetido.
     *
     * @return string
     */
    public function getRequest()
    {
        return $this->rawContent;
    }

    /**
     * Retorna o output devolvido pelo servidor alvo.
     * 
     * @return  null|string     Em caso de sucesso retorna vazio, para erro retorna string.
     */
    public function getResponse()
    {
        $status = $this->getHeader()['raw_info']['http_code'];
        
        if ($status === 500 || $status === 404 || $status === 403 || !$this->content) {
            return null;
        }
        
        return $this->content;
    }
}