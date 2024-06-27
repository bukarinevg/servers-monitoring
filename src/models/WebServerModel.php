<?php

namespace app\models;

use app\source\attribute\validation\FieldAttribute;
use app\source\attribute\validation\LengthAttribute;
use app\source\attribute\validation\TypeAttribute;
use app\source\attribute\validation\RequiredAttribute;

class WebServerModel extends \app\source\model\AbstractModel
{
    public string $table = 'web_server';


    #[FieldAttribute]
    #[LengthAttribute(min: 5, max: 100)]
    #[TypeAttribute(type: 'string')]
    #[RequiredAttribute]
    public $name;

    #[FieldAttribute]
    #[LengthAttribute(min: 1, max: 100)]
    #[TypeAttribute(type: 'string')]
    #[RequiredAttribute]
    public $ip_address;

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $port;

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $status;



    
} 

