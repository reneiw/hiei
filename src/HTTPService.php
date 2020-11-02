<?php

namespace Reneiw\Hiei;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class HTTPService
{
    protected ClientInterface $client;
    protected array $errorCallback = [];


    public function __construct(ClientInterface $client, array $options = [])
    {
        $this->client = $client;

        if ($options['errorCallback'] ?? false) {
            $this->errorCallback = $options['errorCallback'];
        }
    }

    public function request(string $method, string $uri, array $params = null, array $headers = [], bool $sync = true)
    {
        // Build the request parameters for Guzzle
        $guzzleParams = [];
        if ($params !== null) {
            $guzzleParams[strtoupper($method) === 'GET' ? 'query' : 'json'] = $params;
        }

        // Add custom headers
        if (count($headers) > 0) {
            $guzzleParams['headers'] = array_merge($guzzleParams['headers'], $headers);
        }

        /**
         * Run the request as async.
         */

        if ($sync === false) {
            // Async request
            $promise = $this->getClient()->requestAsync($method, $uri, $guzzleParams);
            return $promise->then([$this, 'handleSuccess'], [$this, 'handleFailure']);
        }

        /**
         * Run the request as sync.
         */

        try {
            return $this->handleSuccess($this->getClient()->request($method, $uri, $guzzleParams));
        } catch (GuzzleException $e) {
            if ($this->errorCallback) {
                foreach ($this->errorCallback as $callback) {
                    call_user_func_array($callback, [$method, $uri, $params, $e]);
                }
            }
            return $this->handleFailure($e);
        }
    }

    public function handleSuccess(ResponseInterface $resp): array
    {
        // Return Guzzle response and JSON-decoded body
        return [
            'errors' => false,
            'body' => json_decode(trim($resp->getBody()), true),
            'headers' => $resp->getHeaders(),
            'response' => $resp,
            'status' => $resp->getStatusCode(),
        ];
    }

    public function handleFailure(GuzzleException $e): array
    {
        $resp = null;
        $body = null;
        $status = null;

        if ($e instanceof RequestException) {
            $resp = $e->getResponse();
            if ($resp) {
                // Get the body stream
                $rawBody = $resp->getBody();
                $status = $resp->getStatusCode();

                // Build the error object
                if ($rawBody !== null) {
                    // Convert data to response
                    $body = json_decode(trim($resp->getBody()), true);
                }
            }
        }

        return [
            'errors' => true,
            'response' => $resp,
            'status' => $status,
            'body' => $body,
            'exception' => $e,
        ];
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}
