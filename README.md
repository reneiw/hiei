# Hiei

- Http request Is Especially Import!.

## Prompt

If you just need a returnable middleware.  
then you can try [caseyamcl/guzzle_retry_middleware](https://github.com/caseyamcl/guzzle_retry_middleware).  
I think you will like him.

## Installing

Via Composer

```shell
$ composer require reneiw/hiei -vvv
```

## Quickstart

```php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use Reneiw\Hiei\HieiMiddleware;
use Reneiw\Hiei\HTTPService;

$stack = HandlerStack::create();
$stack->push(
    HieiMiddleware::factory(
        [
            //Set a maximum number of attempts per request, default 10
            'max_retry_attempts' => 2,
            // Only retry when status is equal to these response codes, default [429, 503]
            'retry_on_status' => [204, 429, 503],
        ]
    )
);
$client = new Client(['handler' => $stack]);
$http = new HTTPService(
    $client, 
    [
        'errorCallback' => [
            function ($method, $uri, $params, GuzzleException $e) {
               logger()->info('123', [$method, $uri, $params, $e->getMessage()]);
            },
            function ($method, $uri, $params, GuzzleException $e) {
               logger()->info('223', [$method, $uri, $params, $e->getMessage()]);
            },
        ],
   ]
);
return $http->request('GET', 'http://www.google.com/generate_204');

```

## Usage

TODO

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/reneiw/hiei/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/reneiw/hiei/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
