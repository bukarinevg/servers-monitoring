<?php

namespace app\lib\checker;

class ResultChecker {
    public function __construct(
        public int $server_id,
        public int $status,
        public int $status_code,
        public string $message
    ){}

}