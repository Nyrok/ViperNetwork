<?php

namespace Nyrok\LobbyCore\Commands;

use Nyrok\SnowMoon\Provider\LanguageProvider;
use Nyrok\SnowMoon\Provider\PlayerProvider;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class UnBanCommand extends ViperCommands
{
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0])) {
            $player = $this->getOwningPlugin()->getServer()->getOfflinePlayer($args[0]);
            if($player){
                $this->getOwningPlugin()->getServer()->getNameBans()->remove($player->getName());
                $sender->sendMessage(str_replace(["{player}"], [$player->getName()], LanguageProvider::getLanguageMessage("messages.success.unban", $sender instanceof Player ? PlayerProvider::toCustomPlayer($sender) : null, true)));
            } else{
                $sender->sendMessage(LanguageProvider::getLanguageMessage("messages.errors.player-not-found", $sender instanceof Player ? PlayerProvider::toCustomPlayer($sender) : null, true));
            }
        }
        else {
            $sender->sendMessage(LanguageProvider::getPrefix().$this->getUsage());
        }
    }
}