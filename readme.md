# crphp/webservice
Está é uma biblioteca básica para se consumir um webservice. Este pacote permite 
o consumo via classe SoapClient ou via classe genéria construida com CURL.

1. [Funcionalidades](#funcionalidades)
1. [Requisitos (recomendados)](#requisitos)
1. [Baixando o pacote crphp/webservice para o servidor](#webservice)
1. [Exemplos de uso](#exemplos)
1. [Licença (MIT)](#licenca)

## 1 - <a id="funcionalidades"></a>Funcionalidades
- [x] Consumir webservice
    - [x] Request XML
    - [x] Response XML
    - [x] Obter lista de métodos/serviços
    - [x] Formatar XML

## 2 - Requisitos (módulos)
Os módulos abaixo se fazem necessário para que está biblioteca possa ser utilizada:
- Curl
- Soap

## 3 - <a id="webservice"></a>Baixando o pacote crphp/webservice para o servidor

Para a etapa abaixo estou pressupondo que você tenha o composer instalado e saiba utilizá-lo:
```
composer require crphp/webservice
```

Ou se preferir criar um projeto:
```
composer create-project --prefer-dist crphp/webservice nome_projeto
```

Caso ainda não tenha o composer instalado, obtenha este em: https://getcomposer.org/download/

## 4 - <a id="exemplos"></a>Exemplos de uso

**Utilizando a classe ClienteGenerico**:
```php
use Crphp\Webservice\ClienteGenerico;

// arquivo xml com cabeçalho
$request = '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.webserviceX.NET/"><SOAP-ENV:Body></SOAP-ENV:Body></SOAP-ENV:Envelope>
';

$obj = new ClienteGenerico;
$obj->setURL('http://endereco_do_webservice');
$obj->setRequest($request);
$obj->run();

if($obj->getResponse())
{
    // Perfumaria
    echo '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';
    echo "<pre class='prettyprint' >" . $obj->formatarXML() . "</pre>";
}
```

**Utilizando a classe Soap **
```php
use Crphp\Webservice\Soap;

$args = [
            'tag1_exemplo'   => 'valor1',
            'tag2_exemplo'      => 'valor2'
        ];
 
$obj = new Soap;
$obj->setWsdl('http://endereco_do_wsdl');

// Se o retorno for null então significa que a consulta não foi realizada
if(!$obj->consultar('ConvertTemp', $args))
{
    // Perfumaria
    echo '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';
    echo "<pre class='prettyprint' >" . $obj->formatarXML($obj->getResponse()) . "</pre>";
}
```

Os exemplos acima são simples, porém, as classes supracitadas possuem recursos que permitem validar se a 
requisição foi realizada com sucesso, bem como realizar outras validações.

## 5 - <a id="licenca">Licença (MIT)
Leia o arquivo de licença para maiores informações.