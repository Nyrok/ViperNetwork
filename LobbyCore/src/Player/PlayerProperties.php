<?php

namespace Nyrok\LobbyCore\Player;

use Nyrok\LobbyCore\Traits\PropertiesTrait;

final class PlayerProperties {
    use PropertiesTrait;

    public function __construct(ViperPlayer $player)
    {
        $nbt = $player->getNBT();
        var_dump($nbt);
        if($nbt->getTag("parameters")){
            var_dump($nbt->getTag("parameters"));
        }
        $this->setBaseProperties([
            "status" => [
                "muted" => false,
                "freezed" => false,
            ],
            "parameters" => [
                "cps" => 0,
                "reach" => 0,
                "combo" => 0,
            ]
        ]);
    }
}