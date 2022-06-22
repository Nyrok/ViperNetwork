<?php

namespace Nyrok\LobbyCore\Commands;

use Nyrok\LobbyCore\Managers\KitsManager;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

class KitCommand extends ViperCommands
{
    protected const NAME = "kit";

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof ViperPlayer){
            KitsManager::getKit($args[0])->edit($sender);
        }
    }
}