<?php

/**
 *  Classe genérica de interação com webservice
 * 
 * @package     Crphp
 * @subpackage  webservice
 * @author      Fábio J L Ferreira <contato@fabiojanio.com>
 * @license     MIT (consulte o arquivo license disponibilizado com este pacote)
 * @copyright   (c) 2016, Fábio J L Ferreira
 */

namespace Crphp\Webservice;

class ClienteGenerico
{
    /**
     * Apontamento para uma instância de Curl
     * 
     * @access private
     * @var object
     */
    private $curl;
    
    /**
     * Armazena o xml retornado pela consulta
     *
     * @var string
     */
    private $xml;
    
    /**
     * Armazena as informações referentes a requisição
     * 
     * @var array
     */
    private $info;

    /**
     * Atribui alguns valores considerados padrão
     * 
     * @access public
     */
    public function __construct()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2);
    }

    /**
     * Atribui a URL alvo e o tempo máximo de espera
     * 
     * @param string $url
     * @param int $timeout
     */
    public function setURL($url, $timeout = 30)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $timeout);
    }

    /**
     * Define o agente a ser utilizado
     * 
     * @param string $agente
     */
    public function setAgent($agente = "PHP ClienteGenerico")
    {
        curl_setopt($this->curl, CURLOPT_USERAGENT, $agente);
    }

    /**
     * Adiciona o conteúdo e atribui um cabeçalho a requisição
     * 
     * @param string $post
     * @param array $header
     */
    public function setRequest($post = null, array $header = null)
    {
        if (!$header)
        {
            $header = array(
                "Content-type: text/xml;charset=UTF-8",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "Content-length: " . strlen($post),
            );
        }

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post);
    }

    /**
     * Define regras de redirecionamento de URL
     * 
     * @param bool  $redirect
     * @param int   $numRedirect
     * @param bool  $refresh
     */
    public function setRedirect($redirect = true, $numRedirect = 5, $refresh = true)
    {
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, $redirect);
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, $numRedirect);
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, $refresh);
    }

    /**
     * Executa a consulta a URL alvo
     * 
     * @return void|string em caso de sucesso retorna vazio, para erro retorna string
     */
    public function run()
    {
        $this->xml = curl_exec($this->curl);
        $this->info = curl_getinfo($this->curl); 
        curl_close($this->curl);
    }
    
    /**
     * Informações da requisição obtidos do método curl_getinfo
     * 
     * @see http://php.net/manual/pt_BR/function.curl-getinfo.php
     * 
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Retorna o output devolvido pelo servidor alvo
     * 
     * @return void|string em caso de sucesso retorna vazio, para erro retorna string
     */
    public function getResponse()
    {
        if($this->getInfo()['http_code'] === 500 || $this->getInfo()['http_code'] === 404)
        {
            return null;
        }
        
        return $this->xml;
    }

    /**
     * Formata o retorno do método getResponse
     * 
     * @return null|string se não tiver dado para transformação retorna null
     */
    public function formatarXML()
    {        
        if(!$this->getResponse()) { return null; }

        $dom = new \DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($this->getResponse());
        
        return htmlentities($dom->saveXML());
    }
}
