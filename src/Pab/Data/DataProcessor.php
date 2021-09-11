<?php

namespace App\Pab\Data;

interface DataProcessor
{
    /**
     * @param mixed $in
     *
     * @return mixed
     */
    public function __invoke($in);
}