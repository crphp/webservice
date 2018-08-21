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

## 1 - <a name="referencias"></a>Referências

 - [PSR-1](http://www.php-fig.org/psr/psr-1/)
 - [PSR-2](http://www.php-fig.org/psr/psr-2/)
 - [RFC 2119](http://tools.ietf.org/html/rfc2119) (tradução livre [RFC 2119 pt-br](http://rfc.pt.webiwg.org/rfc2119))

## 2 - <a name="funcionalidades"></a>Funcionalidades

- [x] Consumir webservice
    - [x] Realizar requisições (Request)
    - [x] Intercepctar respostas (Response)
    - [x] Obter lista de serviços listados no WSDL
    - [x] Formatar XML

## 3 - <a name="requisitos"></a>Requisitos (módulos)

Os módulos abaixos já estão definidos no arquivo composer.json, isso significa que serão validados automaticamente.

 - REQUER ext-curl
 - REQUER ext-soap
 - REQUER ext-dom

## 4 - <a name="webservice"></a>Baixando o pacote crphp/webservice para o servidor

Para a etapa abaixo estou pressupondo que você tenha o composer instalado e saiba utilizá-lo:
```
composer require crphp/webservice
```

Ou se preferir criar um projeto:
```
composer create-project --prefer-dist crphp/webservice nome_projeto
```

Caso ainda não tenha o composer instalado, obtenha este em: https://getcomposer.org/download/

## 5 - <a name="exemplos"></a>Exemplos de uso

**Utilizando a classe ClienteGenerico**:
```
use Crphp\Webservice\ClienteGenerico;

// A esquerda do cabeçalho não pode existir espaço em branco
$xml = '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://smartbear.com">
  <SOAP-ENV:Body>
    <ns1:GetCurrentTime/>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>';

$obj = new ClienteGenerico;
$obj->setRequest('http://endereco_do_webservice')
    ->doRequest('nome_servico', $xml);

//Retorna um array contendo o cabeçalho da resposta
//$obj->getHeader();

if($xml = $obj->getResponse()) {
    // Perfumaria
    echo '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';
    echo '<pre class="prettyprint">' . $obj->formatXML($xml) . '</pre>';
}
```

**Utilizando a classe Soap**:
```
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
if($erro = $obj->setRequest('endereco_do_wsdl')) {
    exit($erro);
}

// Retorna um array com a lista de serviços contida no WSDL
// $obj->getMethods();

// Se o retorno for null então significa que a consulta não foi realizada
if(!$erro = $obj->doRequest('nomeServico', $args)) {
    // Perfumaria
    echo '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';
    echo "<pre class='prettyprint' >" . $obj->formatXML($obj->getResponse()) . "</pre>";
    
    /**
     * Retorna uma string contendo o cabeçalho da resposta http do webservice. Deve vir depois de doRequest()
     *
     * @see http://php.net/manual/pt_BR/function.nl2br.php Documentação para a função nlb2br.
     */
    echo nl2br($obj->getHeader());
} else {
    echo $erro;
}
```

## 6 - <a name="licenca"></a>Licença (MIT)

Todo o conteúdo presente neste diretório segue o que determina a licença [MIT](https://github.com/fabiojaniolima/laraboot/blob/master/LICENSE).