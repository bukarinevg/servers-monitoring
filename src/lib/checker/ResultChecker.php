<?php

namespace app\lib\checker;

use app\models\WebServerWorkModel;

class ResultChecker {
    public function __construct(
        public int $server_id,
        public int $status,
        public int $status_code,
        public string $message
    ){}

    public function saveWork() : string{
        $work = new WebServerWorkModel();
        $work->web_server_id = $this->server_id;
        $work->status = $this->status;
        $work->status_code = $this->status_code;
        $work->message = substr($this->message, 0, 255);
        $work->save();
        return date('H:i:s d-m-Y', $work->created_at);

    }
}