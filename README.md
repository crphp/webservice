# crphp/webservice

<a href="https://packagist.org/packages/crphp/webservice"><img src="https://poser.pugx.org/crphp/webservice/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/crphp/webservice"><img src="https://poser.pugx.org/crphp/webservice/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/crphp/webservice"><img src="https://poser.pugx.org/crphp/webservice/license.svg" alt="License"></a>

Está é uma biblioteca básica para se consumir um webservice. Este pacote permite 
o consumo via classe **SoapClient** ou via classe genéria **ClienteGenerico** construida com *CURL*.

Está biblioteca segue os padrões descritos na [PSR-2](http://www.php-fig.org/psr/psr-2/), logo, 
isso implica que a mesma está em conformidade com a [PSR-1](http://www.php-fig.org/psr/psr-1/).

As palavras-chave "DEVE", "NÃO DEVE", "REQUER", "DEVERIA", "NÃO DEVERIA", "PODERIA", "NÃO PODERIA", 
"RECOMENDÁVEL", "PODE", e "OPCIONAL" neste documento devem ser interpretadas como descritas no 
[RFC 2119](http://tools.ietf.org/html/rfc2119). Tradução livre [RFC 2119 pt-br](http://rfc.pt.webiwg.org/rfc2119).

1. [Referências](#referencia)
1. [Funcionalidades](#funcionalidades)
1. [Requisitos (recomendados)](#requisitos)
1. [Baixando o pacote crphp/webservice para o servidor](#webservice)
1. [Exemplos de uso](#exemplos)
1. [Licença (MIT)](#licenca)

## 1 - <a id="referencias"></a>Referências

 - [PSR-1](http://www.php-fig.org/psr/psr-1/)
 - [PSR-2](http://www.php-fig.org/psr/psr-2/)
 - [RFC 2119](http://tools.ietf.org/html/rfc2119). Tradução livre [RFC 2119 pt-br](http://rfc.pt.webiwg.org/rfc2119)

## 2 - <a id="funcionalidades"></a>Funcionalidades

- [x] Consumir webservice
    - [x] Realizar requisições (Request)
    - [x] Intercepctar respostas (Response)
    - [x] Obter lista de serviços listados no WSDL
    - [x] Formatar XML

## 3 - Requisitos (módulos)

Os módulos abaixos já estão definidos no arquivo composer.json, isso significa que serão validados automaticamente.

 - REQUER ext-curl
 - REQUER ext-soap
 - REQUER ext-dom

## 4 - <a id="webservice"></a>Baixando o pacote crphp/webservice para o servidor

Para a etapa abaixo estou pressupondo que você tenha o composer instalado e saiba utilizá-lo:
```
composer require crphp/webservice
```

Ou se preferir criar um projeto:
```
composer create-project --prefer-dist crphp/webservice nome_projeto
```

Caso ainda não tenha o composer instalado, obtenha este em: https://getcomposer.org/download/

## 5 - <a id="exemplos"></a>Exemplos de uso

**Utilizando a classe ClienteGenerico**:
```php
use Crphp\Webservice\ClienteGenerico;

// A esquerda do cabeçalho não pode existir espaço em branco
$xml = '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://smartbear.com">
  <SOAP-ENV:Body>
    <ns1:GetCurrentTime/>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>';

$obj = new ClienteGenerico;
// url de teste: http://secure.smartbearsoftware.com/samples/testcomplete10/webservices/Service.asmx
$obj->setURL('http://endereco_do_webservice');
$obj->setRequest($xml);
$obj->run();

//Retorna um array contendo o cabeçalho da resposta
//$obj->getInfo();

if($obj->getResponse()) {
    // Perfumaria
    echo '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';
    echo '<pre class="prettyprint">' . $obj->formatXML() . '</pre>';
}
```

**Utilizando a classe Soap**:
```php
use Crphp\Webservice\Soap;

//No lugar deste array pode ser passada uma string contendo o xml
$args = [
            'tag1_exemplo'   => 'valor1',
            'tag2_exemplo'   => 'valor2',
            'no_pai' => [
                'no_filho' => 'valor1',
            ]
];
 
$obj = new Soap;
if($erro = $obj->setWsdl('endereco_do_wsdl')) {
    exit($erro);
}

// Retorna um array com a lista de serviços contida no WSDL
// $obj->getMethods();

// Se o retorno for null então significa que a consulta não foi realizada
if(!$erro = $obj->doRequest('nomeServico', $args)) {
    // Perfumaria
    echo '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';
    echo "<pre class='prettyprint' >" . $obj->formatXML($obj->getResponse()) . "</pre>";
    
    // Retorna uma string contendo o cabeçalho da resposta http
    // Obs: Só retorna valor se doRequest já tiver sido chamado
    // $obj->getHeader();
} else {
    echo $erro;
}
```

## 6 - <a id="licenca">Licença (MIT)

Todo o conteúdo presente neste diretório segue o que determina a licença [MIT](https://github.com/fabiojaniolima/laraboot/blob/master/LICENSE).