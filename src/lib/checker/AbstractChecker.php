<?php


namespace app\lib\checker;

use app\models\WebServerModel;
use app\models\WebServerWorkModel;
use app\enums\ServerStatusEnum;
use app\enums\ServerMessageEnum;
use app\enums\ServerChecksAmountEnum;
use app\source\services\Email;
use Checker;


abstract class AbstractChecker implements InterfaceChecker {
    protected Email $email;
    protected array $users = [];
    protected array $servers = [];

    public function __construct($users = [], $servers = []) {
        $this->email = Email::getInstance();
        $this->servers = $servers;
        $this->users = $users;
    }

    public function handleResults(array $results): void {
        foreach ($results as $result) {
            if($result instanceof ResultChecker){
                $work = new WebServerWorkModel();
                $work->web_server_id = $result->server_id;
                $work->status = $result->status;
                $work->status_code = $result->status_code;
                $work->message = substr($result->message, 0, 255);
                $work->save();
                $time = date('H:i:s d-m-Y', $work->created_at);

                $server = WebServerModel::find($result->server_id);
                echo "Server($server->name) is $server->status_message\n";
                if ($result->status == $server->status && $server->count == ServerChecksAmountEnum::getValue($result->status)) {
                    continue;
                } elseif ($result->status !== $server->status) {
                    $server->status = $result->status;
                    $server->status_message = ServerMessageEnum::MESSAGE_UNKNOWN->value;
                    $server->count = 1;
                } elseif ($result->status == ServerStatusEnum::STATUS_FAILURE->value) {
                    $server->count++;
                    if ($server->count === ServerChecksAmountEnum::STATUS_FAILURE->value) {
                        $server->status_message = ServerMessageEnum::MESSAGE_FAILURE->value;
                        $this->email->sendEmail($this->users, "Server($server->name) is down ", "The time is $time. The server($server->name), url - $server->path is down. (Status code: $result->status_code)");
                    }
                } elseif ($result->status == ServerStatusEnum::STATUS_SUCCESS->value) {
                    $server->count++;
                    if ($server->count === ServerChecksAmountEnum::STATUS_SUCCESS->value) {
                        $server->status_message = ServerMessageEnum::MESSAGE_SUCCESS->value;
                    }
                }

                $server->save();
            }
        }
        return;
    }

}
