<?php

namespace app\source\attribute;


class AttributeHelper
{
    /**
     * Get fields from the class with attribute
     *
     * @param string $class
     * @param string $attribute
     * @return array
     */
    static function getFieldsWithAttribute(string $class, string $attribute): array
    {
        echo "Class: $class\n";echo "Attribute: $attribute\n";
        
        $reflector = new \ReflectionClass($class);
        $properties = $reflector->getProperties();
        
        $fields = [];
        foreach ($properties as $property) {
            $reflector = new \ReflectionProperty($class, $property->getName());
            $attributes = $reflector->getAttributes();
  
            foreach ($attributes as $propertyAttribute) {
                if ($propertyAttribute->getName() == $attribute) {
                    $fields[] = $property->getName();
                }
            };
        }   
        return $fields;
    }

    /**
     * Get attribute from the field
     * 
     * @param string $class
     * @param string $field
     * @param string $attribute
     * @return \ReflectionAttribute|null
     */
    static function getAttributeFromField(string $class, string $field, string $attribute): bool{
        $reflector = new \ReflectionProperty($class, $field);
        $attributes = $reflector->getAttributes();
        foreach ($attributes as $attr) {
            if ($attr->getName() == $attribute) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get attributes from the field
     * 
     * @param string $class
     * @param string $field
     * @return array
     */
    static function getAttributesFromField(string $class, string $field): array{
        $reflector = new \ReflectionProperty($class, $field);
        $attributes = $reflector->getAttributes();
        return $attributes;
    }

}