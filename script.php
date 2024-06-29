
<?php
require_once 'vendor/autoload.php';
$config = require_once 'config/config.php';

use app\models\WebServerModel;
use app\models\WebServerWorkModel;
use app\source\db\DataBase;
use app\enums\ServerStatusEnum;
use app\enums\ServerMessageEnum;
use app\enums\ServerChecksAmountEnum;
use app\source\services\Email;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Message;

/**
 * This class checks the status of the web servers.
 */
class ServerChecker {
    /**
     * @var Email $email The Email object.
     */
    private Email $email;
    /**
     * @var Client $client The Guzzle client object.
     */
    private Client $client;

    /**
     * @var array $webServers The array of web servers.
     */
    private array $webServers;

    /**
     * @var array $users The array of users.
     */
    private $users = [];
    /**
     * ServerChecker constructor.
     * @param Client $client The Guzzle client object.
     * @param array $webServers The array of web servers.
     */
    public function __construct(Client $client, array $webServers,  array $users = []) {
        $this->client = $client;
        $this->webServers = $webServers;
        $this->email = Email::getInstance();
        $this->users = $users;
    }

    /**
     * This method checks the status of the web servers.
     * 
     * @return void
     */
    public function checkServers() : void {
        $promises = [];
        foreach ($this->webServers as $server) {
            if ($server instanceof WebServerModel) {
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

    /**
     * This method handles the success of the request.
     * @param $response The response object.
     * @param $server_id server id.
     * @return array The array of results.
     */
    private function handleSuccess(Response $response, $server_id) : array {
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 200 && $statusCode < 300) {
            $status = ServerStatusEnum::STATUS_SUCCESS->value;
        } else {
            $status = ServerStatusEnum::STATUS_FAILURE->value;
        }
        return ['server_id' => $server_id, 'status' => $status, 'status_code' => $statusCode,  'message' => $response->getReasonPhrase()];
    }

    /**
     * This method handles the failure of the request.
     * @param Throwable $e The exception object.
     * @param $server_id The server id.
     * @return array The array of results.
     */
    private function handleFailure(Throwable $e, int $server_id) : array {
        $statusCode= 500;
        $message = '';
        if ($e instanceof ConnectException) {
            $statusCode = 504;
            $message = "Gateway Timeout.";
            
        } elseif ($e instanceof RequestException) {
            if ($response = $e->getResponse()) {
                $statusCode = $response->getStatusCode();
                $message = $response->getReasonPhrase();
            }
        } elseif ($e instanceof ServerException ){
            $statusCode = 500;
            $message = "Internal Server Error.";
        
        } elseif ($e instanceof TooManyRedirectsException) {
            $statusCode = 310;
            $message = "Too many redirects.";
        } else {
            $statusCode = 500;
            $message = $e->getMessage();
        }
        return ['server_id' => $server_id, 'status' => ServerStatusEnum::STATUS_FAILURE->value, 'status_code' => $statusCode ,  'message' => $message];
    }

    /**
     * This method handles the results of the requests.
     * @param array $results The array of results.
     * 
     * @return void
     */
    public function handleResults(array $results) : void {
        foreach ($results as $result) {
            [ $server_id, $status, $statusCode, $message] = [$result['server_id'], $result['status'], $result['status_code'], $result['message']];

            $work = new WebServerWorkModel();
            $work->web_server_id = $server_id;
            $work->status = $status;
            $work->status_code = $statusCode;
            $work->message = substr($message, 0, 255);
            $work->save();
            $time = date('H:i:s d-m-Y', $work->created_at);

            $server = WebServerModel::find($server_id);
            if($status == $server->status && $server->count == ServerChecksAmountEnum::getValue($status)){
                continue;
            }
            elseif($status !== $server->status) {
                $server->status = $status;
                $server->status_message = ServerMessageEnum::MESSAGE_UNKNOWN->value;
                $server->count = 1;
            }
            elseif($status ==  ServerStatusEnum::STATUS_FAILURE->value ) {
                
                $server->count++;
                if($server->count === ServerChecksAmountEnum::STATUS_FAILURE->value) {
                    $server->status_message = ServerMessageEnum::MESSAGE_FAILURE->value;
                    $this->notifyUsers("Server($server->name) is down ", "The time is $time. The server($server->name), url - $server->path is down. (Status code: $statusCode)");
                }
            }
            elseif($status ==  ServerStatusEnum::STATUS_SUCCESS->value) {
                $server->count++;
                if($server->count === ServerChecksAmountEnum::STATUS_SUCCESS->value) {
                    $server->status_message = ServerMessageEnum::MESSAGE_SUCCESS->value;
                }
            }
            $server->save();
            
        }
        return ;
    }

    public function notifyUsers($subject, $message) : void {
        foreach ($this->users as $user) {
            $this->email->sendEmail($user, $subject, $message);
        }
    }
}

DataBase::getInstance($config['components']['db']);
Email::getInstance($config['components']['email']);

$client = new Client(['timeout' => 60, 'max_redirects' => true]);
$servers = WebServerModel::findAll();

$checker = new ServerChecker($client, $servers, $config['admins'] ?? []);
$checker->checkServers();






