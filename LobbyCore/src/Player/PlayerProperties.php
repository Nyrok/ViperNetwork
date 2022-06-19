<?php

namespace Nyrok\LobbyCore\Player;

use UnknowL\Trait\PropertiesTrait;

class PlayerProperties{
    use PropertiesTrait;

    public function __construct()
    {
        $this->setBaseProperties([
            "param" => ["cps" => 0],
        ]);
    }

}