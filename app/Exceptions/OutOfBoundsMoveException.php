<?php

namespace App\Exceptions;

use Throwable;

class OutOfBoundsMoveException extends GameException
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct('The desired cell is outside of the board.', 103, $previous);
    }
}
