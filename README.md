# php-rest-wrapper
PHP Wrapper for managing easy Rest-based APIs

## Main features
Some main features this PHP wrapper have :
* Code of all requests are separated by files
* Using reflexion to make coding easier
* Handle XML and JSON responses. You use arrays, the Wrapper does the conversion.

## How to install
Add  the library in your composer.json file :
```
require {
    "doubotis/php-rest-wrapper": "dev-master"
}
```

## How to use

```
// Get the REQUEST_URI.
$requestURI = $_SERVER['REQUEST_URI'];

// Build an API Request and pass the REQUEST_URI var.
$request = new Doubotis\PHPRestWrapper\APIRequest($requestURI);

// Build the dispatcher, that will help to use the right implementation method.
// For production purposes, it's better to store it into $GLOBALS.
$dispatcher = new Doubotis\PHPRestWrapper\Dispatchers\APIFileResourceDispatcher(__DIR__ . "/resource.txt");

// Get a handler and pass a dispatcher to make the handle.
$handler = new Doubotis\PHPRestWrapper\APIResponseHandler($dispatcher);

// Ask the handle to get the response.
$response = null;
try {
    $handler->handleRequest($request);
    $response = $handler->getResponse();
} catch (Exception $ex) {
    die($ex->getMessage());
}

// Prints the result.
if (is_a($response, Doubotis\PHPRestWrapper\APIStructuredResponse::class)) {
    if ($request->getExtension() == "json") {
        header('Content-Type: text/json');
        echo $response->asJSON();
    } else if ($request->getExtension() == "xml") {
        header('Content-Type: text/xml');
        echo $response->asXML();
    }
}
```

### Specify new Resource
To specify new resource, use the command.api file. The file is like a single .txt file structured as `<HTTP supported methods> <Servlet name> <regex url>`. Each line is a resource that can be accessed by several HTTP methods. The resource is specified with a regex.

Example :
```
GET Base /
GET POST Users /users
GET POST DELETE User /users/([^/]+)
GET POST Me /me
GET POST MeItIs /me/itis
```

This example will create 5 resources "/", "/users", "/users/...", "/me", "/me/itis".

### PHP Implementation Example
For above example, let's see the `GET Base /` line.
Create a `Base` class extending `NativeImplementation` implementing `IGetHandler` because this is the only supported method.
Next, override the get method, like that :
```php
public function get($request)
{
    $arr = array(
        "version" => 10000,
        "compilationDate" => time()
    );
    
    return $arr;
}
```
Additionnaly, if you want to open DB connections or files, you can override `init()` and `dealloc()` methods.

### APIRequest Object
The `$request` parameter is an `APIRequest` object, allowing you to get :
* the complete URI
* the HTTP method
* the resource
* the extension
* the filter
* the sorting
* the HTTP Headers

### Throwing exceptions
If something goes wrong into the process, you can throw many exceptions :
* BadRequestException : means parameters of this resource are missing or wrong.
* ForbiddenAccessException : the access to this resource is forbidden.
* InternalErrorException : the server encountered an internal error.
* HTTPException : the base exception class.
* NotImplementedException : the resource is not implemented yet.
* ResourceNotFoundException : the resource cannot be found. Useful when using regex for asking a specific username for instance.
* TooManyRequestsException : the resource cannot be accessed because the user have done too many requests.
* UnauthorizedAccessException : the resource is not authorized, but can be authorized by specifying some details.
* UnavailableServiceException : the resource is not available.

Each of these exceptions handle the status HTTP code and wrap the stack exception to allow external users to debug what is wrong. You can add a detailed description of the exception by using the `setMessage()` method.

By specifying the header `X-Show-Stacktrace` any user can see the entire stack exception. You can of course limit the use of this header to specific users.
