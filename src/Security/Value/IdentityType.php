<?php

namespace App\Security\Value;

use App\Pab\Value\Enum;

/**
 * @method static IdentityType anonymous()
 * @method static IdentityType user()
 */
class IdentityType extends Enum
{
    const VALUES = ['anonymous', 'user'];
}