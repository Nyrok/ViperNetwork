<?php

namespace Nyrok\LobbyCore\Player;

use pocketmine\player\Player;

final class ViperPlayer extends Player{

    public int $cps = 0;

    public function getCps(): int{
        return $this->cps;
    }

    public function sendParameters(): void{
       
    }

}