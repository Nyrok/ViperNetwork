<?php
namespace Nyrok\LobbyCore\Tasks;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Player\ViperPlayer;
use Nyrok\LobbyCore\Utils\PlayerUtils;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\scheduler\Task;
use pocketmine\world\Position;

final class SpawnTask extends Task
{
    public function __construct(private Position $position, private int $decompte, private ViperPlayer $player)
    {
    }

    public function onRun(): void
    {
        if($this->player->isOnline() and $this->player->getPosition()->equals($this->position)){
            if($this->decompte === 0){
                PlayerUtils::teleportToSpawn($this->player);
            }
            else {
                $this->decompte--;
                $task = new $this($this->position, $this->decompte, $this->player);
                $this->player->sendTip($this->player?->getLanguage()->getMessage("messages.spawn.countdown", ["{countdown}" => $this->decompte], false)->__toString());
                $this->player->getEffects()->add(new EffectInstance(VanillaEffects::BLINDNESS(), 40, 0, false));
                Core::getInstance()->getScheduler()->scheduleDelayedTask($task, 20);
            }
        }
        else {
            $this->player?->getLanguage()->getMessage("messages.spawn.cancel")->send($this->player);
        }
    }

}