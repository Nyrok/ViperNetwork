<?php

namespace Nyrok\LobbyCore\Commands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class RekitCommand extends ViperCommands
{
    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){

        }
    }
}