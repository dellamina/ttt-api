<?php

namespace App\Models;

use App\Exceptions\NotEmptyCellMoveException;
use App\Exceptions\OutOfBoundsMoveException;
use App\Exceptions\WrongTurnException;
use App\Casts\Board;
use App\Models\Board as ModelsBoard;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'board' => Board::class,
        'finished' => 'boolean',
    ];

    protected $protected = [];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // auto-sets values on creation
        static::creating(function ($model) {
            $model->board = new ModelsBoard([[null, null, null], [null, null, null], [null, null, null]]);
            $model->turn = $model->turn ?? 1;
        });
        
    }

    public function makeMove(Move $move)
    {
        $this->validate($move);

        $this->board->set($move->coordinates->x, $move->coordinates->y, $move->player->id);
        $this->turn = 3 - $move->player->id;
        
        if ($this->board->hasWinner()) {
            $this->winner = $move->player->id;
            $this->finished = true;
        }

        if ($this->board->isTie()) {
            $this->finished = true;
        }

        $this->save();

        return $this;
    }

    protected function validate($move)
    {
        if ($this->turn != $move->player->id) {
            throw new WrongTurnException();
        }

        if (!in_array($move->coordinates->x, range(0, 2)) || !in_array($move->coordinates->y, range(0, 2))) {
            throw new OutOfBoundsMoveException();
        }

        if ($this->board->get($move->coordinates->x, $move->coordinates->y) != null) {
            throw new NotEmptyCellMoveException();
        }
    }
}
