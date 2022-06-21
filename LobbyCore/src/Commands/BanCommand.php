<?php

namespace Nyrok\LobbyCore\Commands;

use DateTime;
use DateTimeZone;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

final class BanCommand extends ViperCommands
{
    protected const NAME = "ban";

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0])) {
            $player = $this->getOwningPlugin()->getServer()->getPlayerByPrefix($args[0]) ?? $this->getOwningPlugin()->getServer()->getOfflinePlayer($args[0]);
            $time = isset($args[1]) ? strtotime(str_replace(["S", "M", "H", "D", "W", "Y"], ["seconds", "minutes", "hours","days","weeks","years"], $args[1])) : "forever";
            $reason = isset($args[2]) ? implode(" ", array_slice($args, 2)) : "Aucune raison donnée.";
            $sender_language = $this->getSenderLanguage($sender);
            if($player){
                $this->getOwningPlugin()->getServer()->getNameBans()->addBan($player->getName(), $reason, ($time === "forever" ? null : new DateTime())?->setTimestamp($time), $sender->getName());
                $sender_language?->getMessage("messages.ban.banner", ["{player}" => $player->getName(), "{reason}" => $reason, "{time}" => ($time === "forever" ? "Pour toujours" : (new DateTime())->setTimestamp($time)->setTimezone(new DateTimeZone("GMT+2"))->format("d/m/Y à H:i"))])->send($sender);
                if(($target = $this->getOwningPlugin()->getServer()->getPlayerByPrefix($player->getName()))->isConnected() and $target instanceof ViperPlayer){
                    $target->kick($target->getLanguage()->getMessage("messages.ban.banned", [
                        "{player}" => $sender->getName(),
                        "{reason}" => $reason,
                        "{time}" => ($time === "forever" ? "Pour toujours" : (new DateTime())->setTimestamp($time)->setTimezone(new DateTimeZone("GMT+2"))->format("d/m/Y à H:i"))]
                    )->__toString());
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