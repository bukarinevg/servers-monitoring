<?php


namespace app\models;

use app\source\attribute\validation\FieldAttribute;
use app\source\attribute\validation\TypeAttribute;
use app\source\attribute\validation\RequiredAttribute;

class WebServerModel extends \app\source\model\AbstractModel
{
    public string $table = 'web_server';

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    #[RequiredAttribute]
    public int $web_server_id;

    #[FieldAttribute]
    public int|null $workload;

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $status;


}