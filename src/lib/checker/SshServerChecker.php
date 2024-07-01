<?php

namespace app\lib\checker;

use app\enums\ServerStatusEnum;
use app\models\WebServerModel;
use phpseclib3\Net\SSH2;

class SshServerChecker extends AbstractChecker {
    public function checkServers(): void {
        echo 'Checking SSH servers...';
        $results = [];
        foreach ($this->servers as $server) {
            if ($server instanceof WebServerModel) {
                echo $server->path;
                try{
                    echo 'Trying to connect to server';
                    $ssh = new SSH2($server->path, $server->port?? 22, 60);
                    if (!$ssh->login($server->username, $server->password)) {
                        echo 'Failure';
                        $results[] = new ResultChecker(
                            server_id: $server->id,
                            status: ServerStatusEnum::STATUS_FAILURE->value,
                            status_code: 500,
                            message: 'Failure'
                        );
                    } else {
                        echo 'Success';
                        $results[] = new ResultChecker(
                            server_id: $server->id,
                            status: ServerStatusEnum::STATUS_SUCCESS->value,
                            status_code: 200,
                            message: 'Success'
                        );
                    }

                }catch(\Exception $e){
                    echo 'Failure';
                    echo $e->getMessage();
                    $results[] = new ResultChecker(
                        server_id: $server->id,
                        status: ServerStatusEnum::STATUS_FAILURE->value,
                        status_code: 500,
                        message: substr($e->getMessage(), 0, 255)   
                    );
                }    
            }
        }
        
        $this->handleResults($results);
    }
}