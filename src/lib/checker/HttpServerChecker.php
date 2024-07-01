<?php

namespace app\lib\checker;

use app\models\WebServerModel;
use app\enums\ServerStatusEnum;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Promise\Utils;
use Throwable;

class HttpServerChecker extends AbstractChecker {

    protected Client $client;

    public function __construct(array $servers = [], array $users = []) {
        parent::__construct($users, $servers);
        $this->client = new Client(['timeout' => 60, 'max_redirects' => true]);
    }


    public function checkServers(): void {
        $promises = [];
        foreach ($this->servers as $server) {
            if ($server instanceof WebServerModel) {
                echo "Checking HTTP servers...\n";
                $uri = $server->path;
                $promises[$server->id] = $this->client->getAsync($uri)->then(
                    function ($response) use ($server) {
                        return $this->handleSuccess($response, $server->id);
                    },
                    function (Throwable $e) use ($server) {
                        return $this->handleFailure($e, $server->id);
                    }
                );
            }
        }
        $results = Utils::all($promises)->wait();
        $this->handleResults($results);
    }

    private function handleSuccess(Response $response, int $server_id): ResultChecker {
        $statusCode = $response->getStatusCode();
        $status = ($statusCode >= 200 && $statusCode < 300) ? ServerStatusEnum::STATUS_SUCCESS->value : ServerStatusEnum::STATUS_FAILURE->value;
        return new ResultChecker(
            server_id: $server_id,
            status: $status,
            status_code: $statusCode,
            message: $response->getReasonPhrase()
        );
    }

    private function handleFailure(Throwable $e, int $server_id): ResultChecker {
        $statusCode = 500;
        $message = '';

        if ($e instanceof ConnectException) {
            $statusCode = 504;
            $message = "Gateway Timeout.";
        } elseif ($e instanceof RequestException && $response = $e->getResponse()) {
            $statusCode = $response->getStatusCode();
            $message = $response->getReasonPhrase();
        } elseif ($e instanceof ServerException) {
            $statusCode = 500;
            $message = "Internal Server Error.";
        } elseif ($e instanceof TooManyRedirectsException) {
            $statusCode = 310;
            $message = "Too many redirects.";
        } else {
            $statusCode = 500;
            $message = $e->getMessage();
        }

        return new ResultChecker(
            server_id: $server_id,
            status: ServerStatusEnum::STATUS_FAILURE->value,
            status_code: $statusCode,
            message: $message

        );
    }
}