<?php

namespace Nyrok\LobbyCore\Player;

use Nyrok\LobbyCore\Managers\GradesManager;
use Nyrok\LobbyCore\Traits\PropertiesTrait;

final class PlayerProperties {
    use PropertiesTrait;

    public function __construct(ViperPlayer $player)
    {
        $nbt = $player->getNBT();
        if($nbt->getTag("parameters")){
        }
        $this->setProhibedProperties(["autosprint", "respawnffa", "rekit"]);
        $this->setBaseProperties([
            "infos" => [
                "grade" => GradesManager::getDefaultGrade()->getName(),
            ],
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
                "autosprint" => true,
                "respawnffa" => true,
                "rekit" => true
            ]
        ]);
    }
}