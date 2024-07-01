<?php

namespace app\lib\checker;

use app\enums\ServerStatusEnum;
use app\models\WebServerModel;

class FtpServerChecker extends AbstractChecker {
    public function checkServers(): void {
        $results = [];
        foreach ($this->servers as $server) {
           if($server instanceof WebServerModel) {
            try{
                $connection = ftp_connect($server->path, $server->port?? 21, 60);
            }catch(\Exception $e){
                $connection = false;
            }
            if($connection){
                $results[] = new ResultChecker(
                    server_id: $server->id,
                    status: ServerStatusEnum::STATUS_SUCCESS->value ,
                    status_code: 200,
                    message: 'Success'
                );

            }
            else{
                $results[] = new ResultChecker(
                    server_id: $server->id,
                    status: ServerStatusEnum::STATUS_FAILURE->value ,
                    status_code: 500,
                    message: 'Failure'
                );
            }
            
           }
        }
        $this->handleResults($results);
    }
}
