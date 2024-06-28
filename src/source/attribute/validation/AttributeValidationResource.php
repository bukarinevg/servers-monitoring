<?php
namespace app\source\attribute\validation;

use app\source\attribute\AttributeHelper;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

/**
 * Class AttributeValidationResource
 * 
 * This class is a resource for validating attributes.
 */
class AttributeValidationResource
{
    /**
     * This method validates the  modal properties for each attribute.
     * 
     * @param string $class
     * @param array $data
     * 
     * @return true|\Exception
     */
    public static function validateALLProperties(string $class, array $data): true|\Exception
    {
        //get all fields with FieldAttribute
        $fields = AttributeHelper::getFieldsWithAttribute($class, FieldAttribute::class);
        foreach ($fields as $field) {
            $attributes = AttributeHelper::getAttributesFromField($class, $field);               
            if(
                AttributeHelper::getAttributeFromField(
                $class, $field, RequiredAttribute::class) && (!isset($data[$field]) || empty($data[$field])) ){
                throw new BadRequestException("Field $field is not set");
            }
            else if(!isset($data[$field]) || empty($data[$field] )){
                continue;
            }

            foreach ($attributes as $attribute) {
                $attribute = $attribute->newInstance();
                if (method_exists($attribute, 'validate')) {
                    if (!$attribute->validate($data[$field])) {
                        throw new BadRequestException("The value of the property $field is not valid");
                    }
                }
            }
        }
        return true;
    }
}