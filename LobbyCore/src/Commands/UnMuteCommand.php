<?php
namespace Nyrok\LobbyCore\Commands;

use Nyrok\LobbyCore\Core;
use pocketmine\command\CommandSender;

final class UnMuteCommand extends ViperCommands
{
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0])) {
            $player = $this->getOwningPlugin()->getServer()->getPlayer($args[0]);
            if($player) {
                $player->setMuted(false);

            } else {
                $sender->sendMessage(Core::getInstance()->getPrefix()."Le joueur n'a pas été trouvé");
            }
        }
        else {
            $sender->sendMessage(Core::getInstance()->getPrefix().$this->getUsage());
        }
    }
}