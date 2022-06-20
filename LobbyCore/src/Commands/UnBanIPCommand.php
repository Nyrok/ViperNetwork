<?php
namespace Nyrok\LobbyCore\Commands;

use Nyrok\LobbyCore\Core;
use pocketmine\command\CommandSender;

final class UnBanIPCommand extends ViperCommands
{
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0])) {
            $player = $this->getOwningPlugin()->getServer()->getOfflinePlayer($args[0])?->getAddress() ?? substr_count($args[0], ".") === 4 ? $args[0] : null;
            if($player){
                $this->getOwningPlugin()->getServer()->getIPBans()->remove($player->getName());
                $sender->sendMessage(Core::getInstance()->getPrefix()."L'IP $player a été débannie");
            } else{
                $sender->sendMessage(Core::getInstance()->getPrefix()."L'IP n'a pas été trouvé");
            }
        }
        else {
            $sender->sendMessage(Core::getInstance()->getPrefix().$this->getUsage());
        }
    }
}