<?php

namespace Nyrok\LobbyCore\Player;

use Nyrok\LobbyCore\Trait\PropertiesTrait;

class PlayerProperties{
    use PropertiesTrait;

    public function __construct()
    {
        $this->setBaseProperties([
            "parameters" => ["cps" => 0],
        ]);
    }

}