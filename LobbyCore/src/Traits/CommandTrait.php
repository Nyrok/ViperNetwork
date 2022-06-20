<?php

namespace Nyrok\LobbyCore\Traits;

use Nyrok\LobbyCore\Managers\CommandsManager;
use pocketmine\command\Command;
use pocketmine\permission\DefaultPermissions;

trait CommandTrait
{
    /**
     * @var Command|null
     */
    private static ?Command $command = null;

    /**
     * @param Command $command
     */
    private static function setCommand(Command $command): void {
        self::$command = $command;
    }

    public static function init(){
        if(self::$command !== null){
            self::$command->setDescription(CommandsManager::getDescription(self::$command->getName()));
            self::$command->setAliases(CommandsManager::getAliases(self::$command->getName()));
            self::$command->setUsage(CommandsManager::getUsageMessage(self::$command->getName()));
            self::$command->setPermission(CommandsManager::getPermission(self::$command->getName()) ?? DefaultPermissions::ROOT_OPERATOR);
        }
    }
}