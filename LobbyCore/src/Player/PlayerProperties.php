<?php

namespace Nyrok\LobbyCore\Player;

use Nyrok\LobbyCore\Traits\PropertiesTrait;

final class PlayerProperties {
    use PropertiesTrait;

    public function __construct()
    {
        $this->setBaseProperties([
            "param" => ["cps" => 0],
        ]);
    }

}