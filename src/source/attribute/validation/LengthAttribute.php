<?php
namespace app\source\attribute\validation;  
use Attribute;

/**
 * Class Length Attribute
 * 
 * This class is an attribute that validates the length of a parameter.
 */
#[Attribute]
class LengthAttribute
{
    /**
     * The minimum length.
     *
     * @var int
     */
    public int $min;

    /**
     * The maximum length.
     *
     * @var int
     */
    public int $max;

    /**
     * LengthAttribute constructor.
     *
     * @param int $min The minimum length. Defaults to 0.
     * @param int $max The maximum length. Defaults to PHP_INT_MAX.
     */
    public function __construct(int $min = 0, int $max = PHP_INT_MAX)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Validates the length of a string.
     *
     * @param string $value The string to validate.
     * @throws \InvalidArgumentException if the string's length is not within the min and max range.
     */
    public function validate(string|int $value) : bool
    {
        $length = strlen((string)$value);
        if ($this->min <= $length && $length < $this->max) {
           return True;
        }
        return False;
    }
}
?>