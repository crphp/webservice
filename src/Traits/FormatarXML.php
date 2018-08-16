<?php

namespace Crphp\Webservice\Traits;

use \DOMDocument;

trait FormatarXML {

    /**
     * Converte string para o formato XML.
     *
     * @param string $xml
     *
     * @return null|string  Se não tiver dado para transformação retorna null
     */
    public function formatXML($xml)
    {
        if (!$xml) {
            return null;
        }

        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);

        return htmlentities($dom->saveXML());
    }
}