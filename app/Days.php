<?php

namespace App;


enum Days: int
{
    case Sunday = 0;
    case Monday = 1;
    case Tuesday = 2;
    case Wednesday = 3;
    case Thursday = 4;
    case Friday = 5;
    case Saturday = 6;

    public function isJumaa(): bool
    {
        return $this === self::Friday;
    }
}
