<?php

namespace App\Models;

class Move
{
    public function __construct(
        public readonly Player $player,
        public readonly Coordinates $coordinates
    )
    {
    }

    public static function fromArray(array $data)
    {
        return new self(
            new Player($data['player']),
            new Coordinates($data['coordinates']['x'], $data['coordinates']['y'])
        );
    }
}
