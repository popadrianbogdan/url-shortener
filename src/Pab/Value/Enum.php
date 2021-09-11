<?php

namespace App\Pab\Value;

use App\Exception\InvalidEnumArgumentException;
use function in_array;

abstract class Enum
{
    /** @var string[] */
    const VALUES = [];

    private $value;

    final public function __construct($value)
    {
        if ($value instanceof static) {
            $this->value = $value->getValue();

            return;
        }

        if (!in_array($value, static::values(), true)) {
            throw new InvalidEnumArgumentException("Invalid value '$value' on instantiating ".static::class);
        }

        $this->value = $value;
    }

    /**
     * @param string $methodName
     *
     * @return static
     */
    public static function __callStatic(string $methodName, array $arguments)
    {
        return new static($methodName);
    }

    final public function getValue()
    {
        return $this->value;
    }

    protected static function values(): array
    {
        return static::VALUES;
    }
}