<?php

namespace Crphp\Webservice;

interface iRequestXML
{
    public function setRequest($target, array $header = null, array $increment = null);

    public function doRequest($service, $arguments);

    public function getHeader();

    public function getRequest();

    public function getResponse();

    public function formatXML($xml);
}