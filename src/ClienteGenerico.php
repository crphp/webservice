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

use \DOMDocument;

class ClienteGenerico
{
    /**
     * Armazena uma instância de Curl.
     * 
     * @var object
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
     * Atribui alguns valores considerados padrão.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2);
    }

    /**
     * Define a URL alvo e o tempo máximo do pedido, contando desde o tempo de 
     * conexão até o retorno da requisição.
     * 
     * @param   string  $uri
     * @param   int     $timeout
     *
     * @return  \Crphp\Webservice\ClienteGenerico
     */    
    public function setURL($uri, $timeout = 30)
    {
        curl_setopt($this->curl, CURLOPT_URL, $uri);
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
     * @param   string  $post
     * @param   array   $header
     * @param   array   $increment
     *
     * @return  \Crphp\Webservice\ClienteGenerico
     */
    public function setRequest($post = null, array $header = null, array $increment = null)
    {
        if (!$header) {
            $header = [
                "Content-type: text/xml;charset=UTF-8",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "Content-length: " . strlen($post),
            ];

            if ($increment) {
                $header = array_merge($header, $increment);
            }
        }

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post);

        return $this;
    }

    /**
     * Define regras de redirecionamento de URL, tais como se deve serguir 
     * redirecionamentos, total de redirecionamentos aceitos e se deve ser 
     * aplicado refresh caso um redirect seja seguido.
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
     * Executa a consulta a URL alvo.
     * 
     * @return void
     */
    public function run()
    {
        $this->content = curl_exec($this->curl);
        $this->info = curl_getinfo($this->curl);
        curl_close($this->curl);
    }
    
    /**
     * Informações da requisição obtidos do método curl_getinfo.
     * 
     * @see http://php.net/manual/pt_BR/function.curl-getinfo.php
     * 
     * @return  array
     */
    public function getInfo()
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
     * Retorna o output devolvido pelo servidor alvo.
     * 
     * @return  null|string     Em caso de sucesso retorna vazio, para erro retorna string
     */
    public function getResponse()
    {
        $status = $this->getInfo()['raw_info']['http_code'];
        
        if ($status === 500 || $status === 404 || $status === 403 || !$this->content) {
            return null;
        }
        
        return $this->content;
    }

    /**
     * Formata o retorno do método getResponse.
     *
     * @return  null|string     Se não tiver dado para transformação retorna null
     */
    public function formatXML()
    {        
        if (!$this->getResponse()) {
            return null;
        }

        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($this->getResponse());

        return htmlentities($dom->saveXML());
    }
}