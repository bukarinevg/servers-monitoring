<?php
namespace app\source\attribute\validation;

use Attribute;

/**
 * Class TypeAttribute
 *
 * This class is an attribute that validates the type of a parameter.
 */
#[Attribute]
Class TypeAttribute{
    
        /**
        * @var string $type The type of the parameter.
        */
        private string $type;
    
        /**
        * TypeAttribute constructor.
        *
        * @param string $type The type of the parameter.
        */
        public function __construct(string $type) {
            $this->type = $type;
        }
    
        /**
        * Validates the type of the parameter.
        *
        * @param mixed $value The value of the parameter.
        * @return bool True if the type is valid, false otherwise.
        */
        public function validate($value): bool {
            return gettype($value) === $this->type;
        }
}