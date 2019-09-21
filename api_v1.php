<?php

include_once 'utils/xml-converter.php';
include_once 'utils/strings.php';

include_once 'classes/APIRequest.php';
include_once 'classes/responses/APIStructuredResponse.php';
include_once 'classes/responses/APIBinaryResponse.php';
include_once 'classes/APIResponseHandler.php';
include_once 'classes/dispatchers/APIFileResourceDispatcher.php';
include_once 'classes/dispatchers/APIAnnotationDispatcher.php';

$timestampStart = time();

$requestURI = $_SERVER['REQUEST_URI'];

$request = new APIRequest($requestURI);

// Build the dispatcher, that will help to use the right implementation method.
// For production purposes, it's better to store it into $GLOBALS.
$dispatcher = new APIFileResourceDispatcher("test");

// Get a handler and pass a dispatcher to make the handle.
$handler = new APIResponseHandler($request, $dispatcher);

// Ask the handle to get the response.
$response = $handler->getResponse();

// Complete the response with delay if needed.
$timestampEnd = time();
if (is_a($response, APIStructuredResponse::class)) {
    $response->setDelay($timestampEnd - $timestampStart);
}

// Prints the result.
if (is_a($response, APIStructuredResponse::class)) {
    if ($request->getExtension() == "json") {
        header('Content-Type: text/json');
        echo $response->asJSON();
    } else if ($request->getExtension() == "xml") {
        header('Content-Type: text/xml');
        echo $response->asXML();
    }
} else if (is_a($response, APIBinaryResponse::class)) {
    if ($request->getExtension() == "png") {
        header('Content-Type: image/png');
        $response->asPNG();
    }
}




?>