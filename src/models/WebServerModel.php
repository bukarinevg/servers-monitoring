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
    #[LengthAttribute(min: 1, max: 100)]
    #[TypeAttribute(type: 'string')]
    #[RequiredAttribute]
    public string $name;

    #[FieldAttribute]
    #[LengthAttribute(min: 1, max: 100)]
    #[TypeAttribute(type: 'string')]
    #[RequiredAttribute]
    public string $path;

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $port;

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $status;

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $count;

    #[FieldAttribute]
    #[TypeAttribute(type: 'string')]
    public string|null $status_message;

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $type;

    #[FieldAttribute]
    #[TypeAttribute(type: 'string')]
    public string|null $username;

    #[FieldAttribute]
    #[TypeAttribute(type: 'string')]
    public string|null $password;
    
} 

