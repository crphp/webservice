<?php

/**
 * Classe de interação com interface soap
 * 
 * @package     crphp
 * @subpackage  webservice
 * @author      Fábio J L Ferreira <contato@fabiojanio.com>
 * @license     MIT (consulte o arquivo license disponibilizado com este pacote)
 * @copyright   (c) 2016, Fábio J L Ferreira
 */

namespace Crphp\Webservice;

use \Exception;
use \SoapClient;
use \DOMDocument;

class Soap
{
    /**
     * Armazena uma instância de SoapClient
     *
     * @var string 
     */
    private $client;

    /**
     * Consulta o WSDL informado
     * 
     * @param   string       $wsdl
     * @param   array        $opcoes
     * @return  null|string  null = sucesso, string = erro
     */
    public function setWsdl($wsdl, array $opcoes = null)
    {
        if (!$opcoes) {
            $opcoes = [
                'cache_wsdl' => 'WSDL_CACHE_NONE',
                'soap_version' => 'SOAP_1_2',
                'trace' => 1,
                'encoding' => 'UTF-8'
            ];
        }

        try {
            $this->client = new SoapClient($wsdl, $opcoes);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
        
    /**
     * Dispara a consulta contra o serviço informado
     * 
     * @param   string       $acao
     * @param   array        $argumentos
     * @return  null|string  null = sucesso, string = erro
     */
    public function consult($acao, array $argumentos = null)
    {
        try {
            if(!$this->client) {
                throw new Exception("Ocorreu um erro ao tentar chamar o serviço <b>{$acao}</b>.");
            }
            
            $this->client->__soapCall($acao, array($argumentos));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Retorna os métodos expostos pelo WSDL
     * 
     * @return array|null
     */
    public function getMethods()
    {
        if($this->client) {
            foreach($this->client->__getFunctions() as $metodo) {  
                $array = explode(' ', substr($metodo, 0, strpos($metodo, '(')));
                $metodos[] = end($array);
            }
            return $metodos;
        }
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
    public function formatXML($soap)
    {
        if(!$soap) {
            return null;
        }
        
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($soap);
        
        return htmlentities($dom->saveXML());
    }
}