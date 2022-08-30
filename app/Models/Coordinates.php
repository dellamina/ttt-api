<?php

namespace App\Models;

class Coordinates
{
    public function __construct(public readonly int $x, public readonly int $y)
    {
    }
}
