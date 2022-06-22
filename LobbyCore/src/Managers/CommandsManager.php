<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Commands\BanCommand;
use Nyrok\LobbyCore\Commands\BanIPCommand;
use Nyrok\LobbyCore\Commands\FreezeCommand;
use Nyrok\LobbyCore\Commands\KickCommand;
use Nyrok\LobbyCore\Commands\KitCommand;
use Nyrok\LobbyCore\Commands\MuteCommand;
use Nyrok\LobbyCore\Commands\RekitCommand;
use Nyrok\LobbyCore\Commands\UnBanCommand;
use Nyrok\LobbyCore\Commands\UnBanIPCommand;
use Nyrok\LobbyCore\Commands\UnFreezeCommand;
use Nyrok\LobbyCore\Commands\UnMuteCommand;
use Nyrok\LobbyCore\Commands\ViperCommands;
use Nyrok\LobbyCore\Core;

abstract class CommandsManager
{
    /**
     * @return ViperCommands[]
     */
    public static function getCommands(): array {
        return [
            new BanCommand(),
            new BanIPCommand(),
            new FreezeCommand(),
            new KickCommand(),
            new MuteCommand(),
            new RekitCommand(),
            new UnBanCommand(),
            new UnBanIPCommand(),
            new UnFreezeCommand(),
            new UnMuteCommand(),
            new KitCommand()
        ];
    }

    public static function initCommands(): void {
        foreach (Core::getInstance()->getServer()->getCommandMap()->getCommands() as $command) {
            foreach(self::getCommands() as $cmd) {
                if($cmd->getName() === $command->getName()){
                    Core::getInstance()->getServer()->getCommandMap()->unregister($command);
                }
            }
        }

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
    public static function getUsage(string $name): string {
        return Core::getInstance()->getConfig()->getNested("commands.$name", ['usage' => "/$name"])['usage'] ?? "";
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