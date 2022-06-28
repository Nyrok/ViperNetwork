<?php

namespace Nyrok\LobbyCore\Commands;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Managers\FormsManager;
use Nyrok\LobbyCore\Managers\LobbyManager;
use Nyrok\LobbyCore\Player\ViperPlayer;
use Nyrok\LobbyCore\Tasks\SpawnTask;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\MainLogger;
use pocketmine\world\Position;

final class SpawnCommand extends ViperCommands
{
    protected const NAME = "spawn";

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(parent::execute($sender,$commandLabel, $args))
        if($sender instanceof ViperPlayer){
            Core::getInstance()->getScheduler()->scheduleDelayedTask(new SpawnTask($sender->getPosition(), 3, $sender), 10);
        }
    }

}