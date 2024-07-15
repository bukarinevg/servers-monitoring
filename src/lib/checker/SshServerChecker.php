<?php

namespace app\lib\checker;

use app\enums\ServerStatusEnum;
use app\models\WebServerModel;
use phpseclib3\Net\SSH2;

class SshServerChecker extends AbstractChecker {
    public function checkServers(): void {
        $results = [];
        foreach ($this->servers as $server) {
            if ($server instanceof WebServerModel) {
                try{
                    $ssh = new SSH2($server->path, $server->port?? 22, 60);
                    if (!$ssh->login($server->username, $server->password)) {
                        $results[] = new ResultChecker(
                            server_id: $server->id,
                            status: ServerStatusEnum::STATUS_FAILURE->value,
                            status_code: 500,
                            message: 'Failure'
                        );
                    } else {
                        $results[] = new ResultChecker(
                            server_id: $server->id,
                            status: ServerStatusEnum::STATUS_SUCCESS->value,
                            status_code: 200,
                            message: 'Success'
                        );
                    }

                }catch(\Exception $e){
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