<?php

namespace App\Http\Controllers;

use App\Models\Coordinates;
use App\Models\Game;
use App\Models\Move;
use App\Models\Player;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function start(Request $request)
    {
        $game = new Game();
        $game->save();
        return $game->fresh();
    }

    public function move(Request $request)
    {
        $data = $this->validate($request, [
            'game_id' => ['required', 'exists:games,id'],
            'player' => ['required', 'in:1,2'],
            'coordinates' => [
                'x' => ['required', 'number'],
                'y' => ['required', 'number'],
            ]
        ]);

        $game = Game::find($data['game_id']);

        $game->makeMove(Move::fromArray($data));

        return $game->fresh();
    }
}
