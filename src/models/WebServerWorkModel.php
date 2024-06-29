<?php


namespace app\models;

use app\source\attribute\validation\FieldAttribute;
use app\source\attribute\validation\TypeAttribute;
use app\source\attribute\validation\RequiredAttribute;

class WebServerWorkModel extends \app\source\model\AbstractModel
{
    public string $table = 'web_server_work';

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int $web_server_id;

    #[FieldAttribute]
    public int|null $workload;

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $status;

    #[FieldAttribute]
    #[TypeAttribute(type: 'integer')]
    public int|null $status_code;

    #[FieldAttribute]
    #[TypeAttribute(type: 'string')]
    public string|null $message;


}