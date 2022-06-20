<?php

namespace Nyrok\LobbyCore\Commands;

use DateTime;
use DateTimeZone;
use Nyrok\LobbyCore\Core;
use Nyrok\SnowMoon\Core;
use Nyrok\SnowMoon\Provider\LanguageProvider;
use Nyrok\SnowMoon\Provider\PlayerProvider;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class MuteCommand extends ViperCommands
{
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0], $args[1])) {
            $player = $this->getOwningPlugin()->getServer()->getPlayer($args[0]);
            $time = strtotime(str_replace(["S", "M", "H", "D", "W", "Y"], ["seconds", "minutes", "hours","days","weeks","years"], $args[1]));
            $reason = isset($args[2]) ? implode(" ", array_slice($args, 2)) : "Aucune raison donnée.";
            if($player) {
                $player->setMuted(true);
                $format = (new DateTime())->setTimestamp(($time == "forever") ? time() * 2 : $time)->setTimezone(new DateTimeZone("GMT+2"))->format("d/m/Y à H:i");
                Core::getInstance()->getMuteList()->set($player->getName(), $time ?: time());
                $player->sendMessage(str_replace(["{staff}", "{reason}", "{time}"], [$sender->getName(), $reason,
                    ], LanguageProvider::getLanguageMessage("messages.success.muted", PlayerProvider::toCustomPlayer($player), true)));
                $sender->sendMessage(str_replace(["{player}", "{reason}", "{time}"], [$player->getName(), $reason,
                    (new DateTime())->setTimestamp(($time == "forever") ? time() * 2 : $time)->setTimezone(new DateTimeZone("GMT+2"))->format("d/m/Y à H:i")], LanguageProvider::getLanguageMessage("messages.success.mute", $sender instanceof Player ? PlayerProvider::toCustomPlayer($sender) : null, true)));
            } else {
                $sender->sendMessage(LanguageProvider::getLanguageMessage("messages.errors.player-not-found", $sender instanceof Player ? PlayerProvider::toCustomPlayer($sender) : null, true));
            }
        }
        else {
            $sender->sendMessage(LanguageProvider::getPrefix().$this->getUsage());
        }
    }
}