<?php

namespace App\Pab\Data;

use RuntimeException;

class DataProcessingError extends RuntimeException
{
    /** @var string[] */
    private $path;
    /** @var string */
    private $errorMessage;

    /**
     * @param string   $errorMessage
     * @param string[] $path
     */
    public function __construct($errorMessage, array $path = [])
    {
        parent::__construct($errorMessage);
        $this->path = $path;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return string[]
     */
    public function getPath(): array
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
