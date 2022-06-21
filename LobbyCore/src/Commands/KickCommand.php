<?php

namespace Nyrok\LobbyCore\Commands;

use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

final class KickCommand extends ViperCommands
{
    protected const NAME = "kick";

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0])) {
            $player = $this->getOwningPlugin()->getServer()->getPlayerByPrefix($args[0]);
            $reason = isset($args[1]) ? implode(" ", array_slice($args, 1)) : "Aucune raison donnée.";
$sender_language = $this->getSenderLanguage($sender);
            if($player instanceof ViperPlayer) {
                $player->kick($player->getLanguage()->getMessage("messages.kick.kicked", ["{reason}" => $reason, "{player}" => $sender->getName()])->__toString());
                $sender_language?->getMessage("messages.kick.kicker", ["{player}" => $player->getName(), "{reason}" => $reason])->send($sender);
            } else {
                $sender_language->getMessage("messages.player.not-found")->send($sender);
            }
        }
        else {
            $sender->sendMessage($this->getUsage());
        }
    }
}