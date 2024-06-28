<?php
namespace app\source;

class Error{

    public static function setResponse($error, $message){
        header("HTTP/1.0 $error");
        echo json_encode([
            'error' => $error,
            'message' => $message,
        ]);
        exit();
    }
}