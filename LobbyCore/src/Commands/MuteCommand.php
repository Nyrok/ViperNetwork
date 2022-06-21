<?php

namespace Nyrok\LobbyCore\Commands;

use DateTime;
use DateTimeZone;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

final class MuteCommand extends ViperCommands
{
    protected const NAME = "mute";

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0], $args[1])) {
            $player = $this->getOwningPlugin()->getServer()->getPlayerByPrefix($args[0]);
            $sender_language = $this->getSenderLanguage($sender);
            if($player instanceof ViperPlayer){
                $time = strtotime(str_replace(["S", "M", "H", "D", "W", "Y"], ["seconds", "minutes", "hours","days","weeks","years"], $args[1]));
                $reason = isset($args[2]) ? implode(" ", array_slice($args, 2)) : "Aucune raison donnée.";
                $player->getPlayerProperties()->setNestedProperties("status.muted", true);
                $format = (new DateTime())->setTimestamp(($time == "forever") ? time() * 2 : $time)->setTimezone(new DateTimeZone("GMT+2"))->format("d/m/Y à H:i");
                $player->getLanguage()->getMessage("messages.mute.muted", ["{player}" => $sender->getName(), "{reason}" => $reason, "{time}" => $format]);
                $sender_language?->getMessage("messages.mute.muter", ["{player}" => $player->getName(), "{reason}" => $reason, "{time}" => $format])->send($sender);
            } else {
                $sender_language?->getMessage("messages.player.not-connected")->send($sender);
            }
        }
        else {
            $sender->sendMessage($this->getUsage());
        }
    }
}