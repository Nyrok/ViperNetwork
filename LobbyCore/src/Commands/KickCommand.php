<?php

namespace Nyrok\LobbyCore\Commands;

use Nyrok\SnowMoon\Provider\LanguageProvider;
use Nyrok\SnowMoon\Provider\PlayerProvider;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class KickCommand extends ViperCommands
{
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0])) {
            $player = $this->getOwningPlugin()->getServer()->getPlayer($args[0]);
            $reason = isset($args[1]) ? implode(" ", array_slice($args, 1)) : "Aucune raison donnée.";
            if($player) {
                $player->kick(str_replace(["{staff}", "{reason}"], [$sender->getName(), $reason], LanguageProvider::getLanguageMessage("messages.success.kick", $sender instanceof Player ? PlayerProvider::toCustomPlayer($sender) : null, true)), false);
                $sender->sendMessage(str_replace(["{player}"], [$player->getName()], LanguageProvider::getLanguageMessage("messages.success.kick", $sender instanceof Player ? PlayerProvider::toCustomPlayer($sender) : null, true)));
            } else {
                $sender->sendMessage(LanguageProvider::getLanguageMessage("messages.errors.player-not-found", $sender instanceof Player ? PlayerProvider::toCustomPlayer($sender) : null, true));
            }
        }
        else {
            $sender->sendMessage(LanguageProvider::getPrefix().$this->getUsage());
        }
    }
}