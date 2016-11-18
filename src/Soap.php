<?php

/**
 * Classe de interação com interface soap
 * 
 * @package     Crphp
 * @subpackage  webservice
 * @author      Fábio J L Ferreira <contato@fabiojanio.com>
 * @license     MIT (consulte o arquivo license disponibilizado com este pacote)
 * @copyright   (c) 2016, Fábio J L Ferreira
 */

namespace Crphp\Webservice;

class Soap
{
    /**
     * Armazena uma instância de SoapClient
     *
     * @var string 
     */
    private $client;

    /**
     * Chama o WSDL
     * 
     * @param string $wsdl
     * @param array $opcoes
     * @return void|string em caso de sucesso retorna vazio, para erro retorna string
     */
    public function setWsdl($wsdl, Array $opcoes = null)
    {
        if (!$opcoes)
        {
            $opcoes = [
                        'cache_wsdl' => 'WSDL_CACHE_NONE',
                        'soap_version' => 'SOAP_1_1',
                        'trace' => 1,
                        'encoding' => 'UTF-8'
                      ];
        }

        try {
            $this->client = new \SoapClient($wsdl, $opcoes);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
    
    /**
     * Dispara a consulta contra o serviço informado
     * 
     * @param string $acao
     * @param array $argumentos
     * @return void|string em caso de sucesso retorna vazio, para erro retorna string
     */
    public function consultar($acao, Array $argumentos)
    {
        try {
            if(!$this->client)
            {
                throw new \Exception("Ocorreu um erro ao tentar chamar o serviço <strong>{$acao}</strong>.");
            }
            
            $this->client->__soapCall($acao, array($argumentos));
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Retorna a assinatura dos métodos
     * 
     * @return array|void em caso de sucesso retorna array, para erro retorna null
     */
    public function getMetodos()
    {
        if($this->client)
        {
            foreach($this->client->__getFunctions() as $metodo)
            {  
                $array = explode(' ', substr($metodo, 0, strpos($metodo, '(')));
                $metodos[] = end($array);
            }
            return $metodos;
        }
        
        return null;
    }

    /**
     * Retorna o XML enviado
     * 
     * @return string|void em caso de sucesso retorna string, para erro retorna null
     */
    public function getRequest()
    {
        return ($this->client) ? $this->client->__getLastRequest() : null;
    }

    /**
     * Retorna o XML recebido
     * 
     * @return string|void em caso de sucesso retorna string, para erro retorna null
     */
    public function getResponse()
    {
        return ($this->client) ? $this->client->__getLastResponse() : null;
    }

    /**
     * Converte string para o formato XML
     * 
     * @param string $soap
     * @return null|string se não tiver dado para transformação retorna null
     */
    public function formatarXML($soap)
    {
        if(!$soap) { return null; }
        
        $dom = new \DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($soap);
        
        return htmlentities($dom->saveXML());
    }
}