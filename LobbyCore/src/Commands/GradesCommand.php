<?php

namespace Nyrok\LobbyCore\Commands;

use Nyrok\LobbyCore\Managers\FormsManager;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

class GradesCommand extends ViperCommands
{
    protected const NAME = "grades";

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(parent::execute($sender,$commandLabel, $args))
        if($sender instanceof ViperPlayer){
            FormsManager::gradesForm($sender);
        }
    }
}