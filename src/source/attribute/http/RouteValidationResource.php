<?php
namespace app\source\attribute\http;

use BadMethodCallException;
use app\source\exceptions\BadRequestException;

class RouteValidationResource{
    public static function validateRoute(
        string $class,
        string $method,
        string | null $param = null
    ): bool | BadMethodCallException
    {
        $reflector = new \ReflectionMethod($class, $method);
        $attributes = $reflector->getAttributes();
        foreach ($attributes as $attribute) {
            $attribute = $attribute->newInstance();
            if (method_exists($attribute, 'validate')) {
                if (!$attribute->validate()) {
                    throw new BadMethodCallException("The applied rest api method is not valid");
                }
            }
        }
        return true;
    }
}