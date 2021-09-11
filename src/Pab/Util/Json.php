<?php

namespace App\Pab\Util;

class Json
{
    /**
     * @param string $json
     * @param bool   $asArray will always return an array
     *
     * @return mixed
     */
    public static function decode(string $json, bool $asArray = false)
    {
        if (!$json) {
            return $asArray ? [] : null;
        }
        $decoded = json_decode($json, true);
        if (json_last_error()) {
            throw new JsonError(json_last_error_msg());
        }

        return $decoded;
    }
}
