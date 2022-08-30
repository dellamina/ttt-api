<?php

namespace App\Exceptions;

use Throwable;

class NotEmptyCellMoveException extends GameException
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct('The desired cell is not empty.', 102, $previous);
    }
}
