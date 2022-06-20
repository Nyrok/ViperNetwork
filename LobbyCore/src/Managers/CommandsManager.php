<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Commands\ViperCommands;
use Nyrok\LobbyCore\Core;

abstract class CommandsManager
{
    /**
     * @return ViperCommands[]
     */
    public static function getCommands(): array {
        return [

        ];
    }

    public static function initCommands(): void {
        foreach(self::getCommands() as $command){
            Core::getInstance()->getServer()->getCommandMap()->register($command->getName(), $command);
            Core::getInstance()->getLogger()->notice("[COMMANDS] Command: {$command->getName()} Loaded");
        }
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getDescription(string $name): string {
        return Core::getInstance()->getConfig()->getNested("commands.$name", ['description' => ""])['description'] ?? "";
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getUsageMessage(string $name): string {
        return Core::getInstance()->getConfig()->getNested("commands.$name", ['usageMessage' => "/$name"])['usageMessage'] ?? "";
    }

    /**
     * @param string $name
     * @return array
     */
    public static function getAliases(string $name): array {
        return Core::getInstance()->getConfig()->getNested("commands.$name", ['aliases' => []])['aliases'] ?? [];
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getPermission(string $name): string {
        return Core::getInstance()->getConfig()->getNested("commands.$name", ['permission' => "core.commands.$name"])['permission'] ?? "core.commands.$name";
    }
}