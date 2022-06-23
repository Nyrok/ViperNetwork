<?php

namespace Nyrok\LobbyCore\Commands;

use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

final class UnBanCommand extends ViperCommands
{
    protected const NAME = "unban";

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(parent::execute($sender,$commandLabel, $args))
        if(isset($args[0])) {
            $player = $this->getOwningPlugin()->getServer()->getOfflinePlayer($args[0]);
            $sender_language = $this->getSenderLanguage($sender);
            if($player){
                $this->getOwningPlugin()->getServer()->getNameBans()->remove($player->getName());
                $sender_language?->getMessage("messages.ban.unbanned", ["{player}" => $player])->send($sender);
            } else{
                $sender_language?->getMessage("messages.player.not-found", ["{player}" => $player])->send($sender);
            }
        }
        else {
            $sender->sendMessage($this->getUsage());
        }
    }
}