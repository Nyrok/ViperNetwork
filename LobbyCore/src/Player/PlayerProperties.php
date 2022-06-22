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
        $this->setProhibedProperties(["autosprint"]);
        $this->setBaseProperties([
            "status" => [
                "muted" => false,
                "freezed" => false,
            ],
            "parameters" => [
                "cps" => 0,
                "reach" => 0,
                "combo" => 0,
                "ping" => 0,
                "pots" => 0,
                "autosprint" => true
            ]
        ]);
    }
}