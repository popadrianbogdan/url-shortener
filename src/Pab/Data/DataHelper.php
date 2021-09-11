<?php

namespace App\Pab\Data;

use function gettype;
use function is_array;
use function is_int;
use function is_string;

class DataHelper
{
    /** @var mixed */
    private $value;
    /** @var bool */
    private $maybe = false;
    /** @var array */
    private $path;

    /**
     * @param mixed    $value
     * @param string[] $path
     */
    public function __construct($value, $path = [])
    {
        $this->value = $value;
        $this->path = $path;
    }

    /**
     * @return DataHelper
     */
    public function maybe(): DataHelper
    {
        $maybeHelper = clone $this;
        $maybeHelper->maybe = true;

        return $maybeHelper;
    }

    /**
     * @param callable $processor
     * @return DataHelper
     */
    public function process(callable $processor): DataHelper
    {
        if ($this->maybe && null === $this->value) {
            return $this;
        }

        try {
            return new self($processor($this->value));
        } catch (DataProcessingError $e) {
            throw new DataProcessingError(
                $e->getErrorMessage().' for the '.implode('->', $this->path).' input.',
                array_merge($this->path, $e->getPath())
            );
        }
    }

    /**
     * @param string $expectedType
     * @param string $receivedType
     * @param string $key
     *
     * @return DataProcessingError
     */
    public function createError(string $expectedType, string $receivedType, $key = null): DataProcessingError
    {
        $errorMessage = 'Please email fildes3d3@gmail.com with the following error message, url, 
            and what you were trying to do.';

        if ($key) {
            $errorMessage = $errorMessage.' Error: The '.implode('->', $this->path).
                ' input is missing the '.$key.' field.';
        } else {
            $errorMessage = $errorMessage.' Error: Expecting '.$expectedType.', received '.$receivedType.
                ' for '.implode('->', $this->path).' input.';
        }

        throw new DataProcessingError($errorMessage, $this->path);
    }

    public function getString(): ?string
    {
        if ($this->maybe && null === $this->value && strlen($this->value) !== 0) {
            return null;
        }

        if (!is_string($this->value) && strlen($this->value) === 0) {
            $this->createError('string', gettype($this->value));
        }

        return $this->value;
    }

    public function getInteger(): ?int
    {
        if ($this->maybe && null === $this->value) {
            return null;
        }

        if (is_string($this->value) && ((string) (int) $this->value) === $this->value) {
            return (int) $this->value;
        }

        if (!is_int($this->value)) {
            $this->createError('integer', gettype($this->value));
        }

        return $this->value;
    }

    /**
     * @param string $key
     *
     * @return DataHelper
     */
    public function access(string $key): DataHelper
    {
        if ($this->maybe) {
            if (null === $this->value
                || (is_array($this->value) && !\array_key_exists($key, $this->value))
            ) {
                return (new self(null, array_merge($this->path, [$key])))->maybe();
            }
        }

        if (!is_array($this->value) || !\array_key_exists($key, $this->value)) {
            $this->createError('array', gettype($this->value), $key);
        }

        return new self($this->value[$key], array_merge($this->path, [$key]));
    }

    /**
     * @return mixed|null
     */
    public function get()
    {
        if ($this->maybe && null === $this->value) {
            return null;
        }

        if (null === $this->value) {
            throw new DataProcessingError(implode('->', $this->path).': Value is null', $this->path);
        }

        return $this->value;
    }
}
