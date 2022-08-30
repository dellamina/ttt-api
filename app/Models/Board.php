<?php

namespace App\Models;

class Board
{
    public array $cells;

    public function __construct(array $cells)
    {
        $this->cells = $cells;
    }

    public function get(int $x, int $y): ?int
    {
        return $this->cells[$x][$y];
    }

    public function set(int $x, int $y, $value): self
    {
        $this->cells[$x][$y] = $value;
        return $this;
    }

    public function hasWinner()
    {
        return $this->rowCrossed() || $this->columnCrossed() || $this->diagonalCrossed();
    }

    public function isTie()
    {
        foreach (range(0, 2) as $i) {
            foreach (range(0, 2) as $j) {
                if ($this->get($i, $j) == null) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function rowCrossed()
    {
        foreach (range(0, 2) as $i) {
            if (
                $this->get($i, 0) != null &&
                $this->get($i, 0) == $this->get($i, 1) &&
                $this->get($i, 0) == $this->get($i, 2)
            ) {
                return true;
            }
        }
        return false;
    }

    protected function columnCrossed()
    {
        foreach (range(0, 2) as $i) {
            if (
                $this->get(0, $i) != null &&
                $this->get(0, $i) == $this->get(1, $i) &&
                $this->get(0, $i) == $this->get(2, $i)
            ) {
                return true;
            }
        }
        return false;
    }

    protected function diagonalCrossed()
    {
        if (
            $this->get(0, 0) != null &&
            $this->get(0, 0) == $this->get(1, 1) &&
            $this->get(0, 0) == $this->get(2, 2)
        ) {
            return true;
        }
        if (
            $this->get(0, 2) != null &&
            $this->get(0, 2) == $this->get(1, 1) &&
            $this->get(0, 2) == $this->get(2, 0)
        ) {
            return true;
        }
        return false;
    }
}
