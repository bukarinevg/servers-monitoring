<?php
namespace app\source\attribute\validation;

use Attribute;

/**
 * Class AttributeValidationResource
 * 
 * This class is a resource for validating attributes.
 */
class AttributeValidationResource
{
    public static function validateProperty(string $class, string $propertyName , mixed $value): true|\Exception
    {
        $reflector = new \ReflectionProperty($class, $propertyName);
        $attributes = $reflector->getAttributes();
        foreach ($attributes as $attribute) {
            $attribute = $attribute->newInstance();
            if (method_exists($attribute, 'validate')) {
                if (!$attribute->validate($value)) {
                    throw new \Exception("The value of the property $propertyName is not valid");
                }
            }
            
        }
        return true;
        // foreach ($attributes as $attribute) {
        //     if ($attribute->getName() === Attribute::class) {
        //         return $attribute->newInstance();
        //     }
        // }


    }
}