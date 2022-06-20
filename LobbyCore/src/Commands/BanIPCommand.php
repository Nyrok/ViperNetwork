<?php

namespace Nyrok\LobbyCore\Commands;

use DateTime;
use DateTimeZone;
use Nyrok\SnowMoon\Core;
use Nyrok\SnowMoon\Provider\LanguageProvider;
use Nyrok\SnowMoon\Provider\PlayerProvider;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class BanIPCommand extends ViperCommands
{

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0])) {
            $ip = $this->getOwningPlugin()->getServer()->getPlayer($args[0])?->getAddress() ?? $this->getOwningPlugin()->getServer()->getOfflinePlayer($args[0])?->getAddress() ?? (substr_count($args[0], ".") === 4 ? $args[0] : null);
            $time = isset($args[1]) ? strtotime(str_replace(["S", "M", "H", "D", "W", "Y"], ["seconds", "minutes", "hours","days","weeks","years"], $args[1])) : "forever";
            $reason = isset($args[2]) ? implode(" ", array_slice($args, 2)) : "Aucune raison donnée.";
            if($ip){
                $this->getOwningPlugin()->getServer()->getIPBans()->addBan($ip, $reason, ($time === "forever" ? null : new DateTime())?->setTimestamp($time), $sender->getName());
                $sender->sendMessage(str_replace(
                    ["{ip}", "{reason}", "{type}", "{time}"],
                    [$ip, $reason, ($time === "forever" ? "définitivement" : "temporairement"), (new DateTime())->setTimestamp($time === "forever" ? time() * 2 : $time)->setTimezone(new DateTimeZone("GMT+2"))->format("d/m/Y à H:i")],
                    LanguageProvider::getLanguageMessage("messages.success.ipban", $sender instanceof Player ? PlayerProvider::toCustomPlayer($sender) : null, true)));
                if(($target = $this->getOwningPlugin()->getServer()->getPlayer($args[0]))?->isConnected()){
                    $target->kick(str_replace(
                        ["{staff}", "{reason}", "{time}"],
                        [$sender->getName(), $reason, (new DateTime())->setTimestamp($time === "forever" ? time() * 2 : $time)->setTimezone(new DateTimeZone("GMT+2"))->format("d/m/Y à H:i")],
                        LanguageProvider::getLanguageMessage("messages.success.ipbanned", PlayerProvider::toCustomPlayer($target), true)), false);
                }
            } else{
                $sender->sendMessage(LanguageProvider::getLanguageMessage("messages.errors.player-not-found", $sender instanceof Player ? PlayerProvider::toCustomPlayer($sender) : null, true));
            }
        }
        else {
            $sender->sendMessage(LanguageProvider::getPrefix().$this->getUsage());
        }
    }
}