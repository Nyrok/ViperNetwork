<?php

namespace Nyrok\LobbyCore\Commands;

use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

final class FreezeCommand extends ViperCommands
{
    protected const NAME = "freeze";

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(parent::execute($sender,$commandLabel, $args))
        if(isset($args[0])){
            $player = $this->getOwningPlugin()->getServer()->getPlayerByPrefix($args[0]);
            $sender_language = $this->getSenderLanguage($sender);
            if($player){
                $player->setImmobile(true);
                $player->getLanguage()->getMessage("messages.freeze.freezed", ["{player}" => $sender->getName()])->send($player);
                $sender_language?->getMessage("messages.freeze.freezer", ["{player}" => $player->getName()])->send($sender);
            }
            else{
                $sender_language?->getMessage("messages.player.not-found")->send($sender);
            }
        }
        else{
            $sender->sendMessage($this->getUsage());
        }
    }
}