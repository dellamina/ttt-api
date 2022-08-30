<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;

class GameTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test game can be created.
     *
     * @return void
     */
    public function test_game_be_created()
    {
        $this->json('POST', '/game')
            ->seeJsonStructure([
                'id',
                'turn',
                'board' => [
                    'cells'
                ],
                'winner',
                'finished',
            ])
            ->assertResponseStatus(200);
    }

    /**
     * Test moves are correctly validated.
     *
     * @return void
     */
    public function test_moves_are_correctly_validated()
    {
        $this->json('POST', '/game');
        $gameId = $this->response->json('id');

        $this->move($gameId, 1, 0, 0)
            ->assertResponseStatus(200);

        // invalid turn
        $this->move($gameId, 1, 0, 0)
            ->seeJson(['message' => 'It is not your turn, be patient.'])
            ->assertResponseStatus(422);

        // out of board cell
        $this->move($gameId, 2, -1, 0)
            ->seeJson(['message' => 'The desired cell is outside of the board.'])
            ->assertResponseStatus(422);

        // not empty cell
        $this->move($gameId, 2, 0, 0)
            ->seeJson(['message' => 'The desired cell is not empty.'])
            ->assertResponseStatus(422);
    }

    /**
     * Test game can end in a tie.
     *
     * @return void
     */
    public function test_match_can_end_tie()
    {
        $this->json('POST', '/game');
        $gameId = $this->response->json('id');

        $this->move($gameId, 1, 0, 0);
        $this->move($gameId, 2, 0, 1);
        $this->move($gameId, 1, 1, 0);
        $this->move($gameId, 2, 2, 0);
        $this->move($gameId, 1, 2, 2);
        $this->move($gameId, 2, 1, 1);
        $this->move($gameId, 1, 2, 1);
        $this->move($gameId, 2, 1, 2);
        $this->move($gameId, 1, 0, 2);

        $this->seeJson([
            'winner' => null,
            'finished' => true,
        ]);
    }

    /**
     * Test game correctly check for row, column or diagonal winniners.
     *
     * @return void
     */
    public function test_row_column_or_diagonal_winners()
    {
        // row winner
        $this->json('POST', '/game');
        $gameId = $this->response->json('id');

        $this->move($gameId, 1, 0, 0);
        $this->move($gameId, 2, 0, 1);
        $this->move($gameId, 1, 1, 0);
        $this->move($gameId, 2, 0, 2);
        $this->move($gameId, 1, 2, 0);

        $this->seeJson([
            'winner' => 1,
            'finished' => true,
        ]);

        // column winner
        $this->json('POST', '/game');
        $gameId = $this->response->json('id');

        $this->move($gameId, 1, 2, 1);
        $this->move($gameId, 2, 0, 0);
        $this->move($gameId, 1, 1, 0);
        $this->move($gameId, 2, 0, 1);
        $this->move($gameId, 1, 2, 0);
        $this->move($gameId, 2, 0, 2);

        $this->seeJson([
            'winner' => 2,
            'finished' => true,
        ]);

        // diagonal winner
        $this->json('POST', '/game');
        $gameId = $this->response->json('id');

        $this->move($gameId, 1, 0, 0);
        $this->move($gameId, 2, 0, 1);
        $this->move($gameId, 1, 1, 1);
        $this->move($gameId, 2, 0, 2);
        $this->move($gameId, 1, 2, 2);

        $this->seeJson([
            'winner' => 1,
            'finished' => true,
        ]);
    }

    protected function move($gameId, $player, $x, $y)
    {
        $move = [
            'game_id' => $gameId,
            'player' => $player,
            'coordinates' => [
                'x' => $x,
                'y' => $y,
            ],
        ];

        return $this->json('POST', '/game/move', $move);
    }
}
