<?php

namespace Nyrok\LobbyCore\Commands;

use DateTime;
use DateTimeZone;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

final class BanIPCommand extends ViperCommands
{
    protected const NAME = "ban-ip";

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0])) {
            $ip = $this->getOwningPlugin()->getServer()->getPlayer($args[0])?->getAddress() ?? $this->getOwningPlugin()->getServer()->getOfflinePlayer($args[0])?->getAddress() ?? (substr_count($args[0], ".") === 4 ? $args[0] : null);
            $time = isset($args[1]) ? strtotime(str_replace(["S", "M", "H", "D", "W", "Y"], ["seconds", "minutes", "hours","days","weeks","years"], $args[1])) : "forever";
            $reason = isset($args[2]) ? implode(" ", array_slice($args, 2)) : "Aucune raison donnée.";
            $sender_language = $this->getSenderLanguage($sender);
            if($ip){
                $this->getOwningPlugin()->getServer()->getIPBans()->addBan($ip, $reason, ($time === "forever" ? null : new DateTime())?->setTimestamp($time), $sender->getName());
                $sender_language?->getMessage("messages.ban-ip.banned", ["{player}" => $ip, "{reason}" => $reason, "{time}" => ($time === "forever" ? "Pour toujours" : (new DateTime())->setTimestamp($time)->setTimezone(new DateTimeZone("GMT+2"))->format("d/m/Y à H:i"))])->send($sender);
                if(($target = $this->getOwningPlugin()->getServer()->getPlayer($args[0]))?->isConnected()){
                    $target->kick($reason);
                }
            } else{
                $sender_language?->getMessage("messages.player.not-found")->send($sender);
            }
        }
        else {
            $sender->sendMessage($this->getUsage());
        }
    }
}