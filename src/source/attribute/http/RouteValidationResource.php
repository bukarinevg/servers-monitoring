<?php
namespace app\source\attribute\http;

class RouteValidationResource{
    public static function validateRoute(
        string $class,
        string $method
    ): bool | \Exception
    {
        $reflector = new \ReflectionMethod($class, $method);
        $attributes = $reflector->getAttributes();
        foreach ($attributes as $attribute) {
            $attribute = $attribute->newInstance();
            if (method_exists($attribute, 'validate')) {
                if (!$attribute->validate()) {
                    throw new \Exception("The applied rest api method is not valid");
                }
            }
        }
        return true;
    }
}