<?php

include_once 'utils/xml-converter.php';
include_once 'utils/strings.php';

include_once 'classes/api-request.php';
include_once 'classes/api-response.php';
include_once 'classes/api-response-handler.php';

$timestampStart = time();

$requestURI = $_SERVER['REQUEST_URI'];

$request = new APIRequest($requestURI);

$handler = new APIResponseHandler($request);
$obj = $handler->getObject();

$response = new APIResponse();
$response->setData($obj);

$timestampEnd = time();
$response->setDelay($timestampEnd - $timestampStart);

// Prepares the result
$arr = array(
    "request" => $request->toArray(),
    "response" => $response->toArray()
);

if ($request->getExtension() == "json") {
    $json = json_encode($arr);
    header('Content-Type: text/json');
    echo $json;
}
else if ($request->getExtension() == "xml") {
    $xml = new SimpleXMLElement('<root/>');
    array_to_xml($arr,$xml);
    header('Content-Type: text/xml');
    echo $xml->asXML();
}





?>