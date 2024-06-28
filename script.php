
<?php
require_once 'vendor/autoload.php';
$config = require_once 'config/config.php';

use app\models\WebServerModel;
use app\source\db\DataBase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

DataBase::getInstance($config['components']['db']);
$webServers = WebServerModel::findAll();

$client = new Client([
    'timeout'  => 60, // Set timeout to 60 seconds
]);

foreach ($webServers as $server) {
    if($server instanceof WebServerModel) {
        try {
            echo "Checking server: $server->id " . $server->ip_address . "\n";
            $startTime = microtime(true);
            $response = $client->get($server->ip_address);
            $endTime = microtime(true);

            $statusCode = $response->getStatusCode();
            $latency = $endTime - $startTime;

           
            if ($statusCode >= 200 && $statusCode < 300 && $latency < 60) {
                $server= WebServerModel::find($server->id);
                $server->status = 1; 
            } else {
                $server= WebServerModel::find($server->id);
                $server->status = 0; 
                echo "Server is down";
            }
        } catch (Exception| RequestException $e) {
            $server= WebServerModel::find($server->id);
            $server->status = 0;
            echo "Server is down because of error: " . $e->getMessage() . "\n";

        }
        $server->save();

    }
}









