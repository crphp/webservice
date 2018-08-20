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
use Crphp\Webservice\Traits\FormatarXML;

class Soap implements iRequestXML
{
    use FormatarXML;

    /**
     * Armazena uma instância de SoapClient.
     *
     * @var SoapClient
     */
    private $client;

    /**
     * Consulta o WSDL informado.
     * 
     * @param   string      $wsdl
     * @param   array       $header
     * @param   array       $increment
     *
     * @return  void|string  void = sucesso, string = erro
     */
    public function setRequest($wsdl, array $header = null, array $increment = null)
    {
        if (!$header) {
            $header = [
                'cache_wsdl' => 'WSDL_CACHE_NONE',
                'soap_version' => 'SOAP_1_2',
                'user_agent' => 'PHP/SOAP',
                'trace' => 1,
                'encoding' => 'UTF-8',
                // Corrige um problema onde era retornado o erro: Could not connect to host
                'location' => substr($wsdl, 0, -5)
            ];
        }

        if ($increment) {
            $header = array_merge($header, $increment);
        }

        try {
            $this->client = new SoapClient($wsdl, $header);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
        
    /**
     * Dispara a consulta contra o serviço informado.
     * 
     * @param   string          $service
     * @param   string|array    $arguments
     *
     * @return  void|string     null = sucesso, string = erro
     */
    public function doRequest($service, $arguments)
    {
        try {
            if (!$this->client) {
                throw new Exception("Ocorreu um erro ao tentar chamar o serviço <b>{$service}</b>.");
            }

            if (is_string($arguments)) {
                /* @see http://php.net/manual/pt_BR/function.libxml-use-internal-errors.php */
                libxml_use_internal_errors(true);
                $xml = simplexml_load_string($arguments);

                if ($xml === false) {
                    foreach (libxml_get_errors() as $error) {
                        throw new Exception('Ocorreu um erro ao processar o XML de envio: ' . $error->message);
                    }
                }

                if ($xml = $xml->children('soapenv', true)) {
                    $arguments = $xml->Body->children()->asXML();
                }

                $arguments = [new SoapVar($arguments, XSD_ANYXML)];
            }

            $this->client->__soapCall($service, $arguments);
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
    public function getHeader($nl2br = true)
    {
        if ($this->client) {
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
        if ($this->client) {
            foreach($this->client->__getFunctions() as $service) {
                $array = explode(' ', substr($service, 0, strpos($service, '(')));
                $service[] = end($array);
            }

            return $service;
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
}