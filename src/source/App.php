<?php
namespace app\source;

class App{

    private $config;

    public function __construct($config){
        $this->config = $config;
    }
    public function run(){
        print_r($this->config);
    }
}