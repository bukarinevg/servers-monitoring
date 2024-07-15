<?php

namespace app\source\enums;

enum ParamTypeEnum :string{
    case INT = 'int';
    case STRING = 'string';
    case FLOAT = 'float';
    case BOOL = 'bool';
    case NULL = 'null';
}