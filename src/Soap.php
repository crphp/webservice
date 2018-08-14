<?php

/**
 * Classe de interação com interface soap.
 * 
 * @package     crphp
 * @subpackage  webservice
 * @author      Fábio J L Ferreira <contato@fabiojanio.com>
 * @license     MIT (consulte o arquivo license disponibilizado com este pacote)
 * @copyright   (c) 2016, Fábio J L Ferreira
 */

namespace Crphp\Webservice;

use \SoapVar;
use \Exception;
use \SoapClient;
use \DOMDocument;

class Soap
{
    /**
     * Armazena uma instância de SoapClient.
     *
     * @var SoapClient
     */
    private $client;

    /**
     * Consulta o WSDL informado.
     * 
     * @param   string       $wsdl
     * @param   array        $opcoes
     *
     * @return  void|string  void = sucesso, string = erro
     */
    public function setWsdl($wsdl, array $opcoes = null)
    {
        if (!$opcoes) {
            $opcoes = [
                'cache_wsdl' => 'WSDL_CACHE_NONE',
                'soap_version' => 'SOAP_1_2',
                'trace' => 1,
                'encoding' => 'UTF-8',
                // Corrige um problema onde era retornado o erro: Could not connect to host
                'location' => substr($wsdl, 0, -5)
            ];
        }

        try {
            $this->client = new SoapClient($wsdl, $opcoes);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
        
    /**
     * Dispara a consulta contra o serviço informado.
     * 
     * @param   string          $servico
     * @param   string|array    $argumentos
     *
     * @return  void|string     null = sucesso, string = erro
     */
    public function doRequest($servico, $argumentos)
    {
        try {
            if(!$this->client) {
                throw new Exception("Ocorreu um erro ao tentar chamar o serviço <b>{$servico}</b>.");
            }

            if(is_string($argumentos)) {
                $argumentos = [new SoapVar($argumentos, XSD_ANYXML)];
            }

            $this->client->__soapCall($servico, $argumentos);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Retorna o cabeçalho HTTP da resposta enviada pelo webservice.
     *
     * @param   bool          $nl2br
     *
     * @return  null|string
     */
    public function getResponseHeader($nl2br = true)
    {
        if($this->client) {
            $responseHeader = $this->client->__getLastResponseHeaders();

            /* @see http://php.net/manual/pt_BR/function.nl2br.php Documentação para a função nlb2br */
            return ($nl2br) ? nl2br($responseHeader) : $responseHeader;
        }

        return null;
    }

    /**
     * Retorna os métodos expostos pelo WSDL.
     * 
     * @return  array|void
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
     * Retorna o XML enviado.
     * 
     * @return  string|null  Em caso de sucesso retorna string, para erro retorna null
     */
    public function getRequest()
    {
        return ($this->client) ? $this->client->__getLastRequest() : null;
    }

    /**
     * Retorna o XML recebido.
     * 
     * @return string|null  Em caso de sucesso retorna string, para erro retorna null
     */
    public function getResponse()
    {
        return ($this->client) ? $this->client->__getLastResponse() : null;
    }

    /**
     * Converte string para o formato XML.
     * 
     * @param string $soap
     *
     * @return null|string  Se não tiver dado para transformação retorna null
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