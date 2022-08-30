<?php

namespace App\Exceptions;

use Throwable;

class WrongTurnException extends GameException
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct('It is not your turn, be patient.', 101, $previous);
    }
}
