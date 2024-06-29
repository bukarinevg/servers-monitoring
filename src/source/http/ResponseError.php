<?php
namespace app\source\http;

class ResponseError{

    public static function setResponse($error, $message){
        header("HTTP/1.0 $error");
        echo json_encode([
            'error' => $error,
            'message' => $message,
        ]);
        exit();
    }
}