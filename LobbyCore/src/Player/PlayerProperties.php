<?php

namespace Nyrok\LobbyCore\Player;

use UnknowL\Trait\PropertiesTrait;

final class PlayerProperties{
    use PropertiesTrait;

    public function __construct()
    {
        $this->setBaseProperties([
            "param" => ["cps" => 0],
        ]);
    }

}