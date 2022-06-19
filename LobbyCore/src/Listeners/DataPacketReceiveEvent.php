<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Player\ViperPlayer;
use Nyrok\LobbyCore\Utils\PlayerUtils;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent as ClassEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\network\mcpe\protocol\types\PlayerAction;
use pocketmine\network\mcpe\protocol\types\PlayerBlockActionWithBlockInfo;

final class DataPacketReceiveEvent implements Listener
{
    const NAME = "DataPacketReceiveEvent";

    /**
     * @param ClassEvent $event
     */
    public function onEvent(ClassEvent $event){
        $packet = $event->getPacket();
        $player = $event->getOrigin()->getPlayer();
        if($player instanceof ViperPlayer){
            if($packet instanceof PlayerAuthInputPacket){
                $blockaction = $packet->getBlockActions();
                if(!is_null($blockaction)){
                    foreach ($blockaction as $action){
                        if($action instanceof PlayerBlockActionWithBlockInfo){
                            if($action->getActionType() === PlayerAction::START_BREAK){
                                $player->addClick();
                            }
                        }
                    }
                }
            }
            if(isset($player->clicksData) &&
                (
                    ($event->getPacket()::NETWORK_ID === InventoryTransactionPacket::NETWORK_ID && $packet->trData instanceof UseItemOnEntityTransactionData) ||
                    ($event->getPacket()::NETWORK_ID === LevelSoundEventPacket::NETWORK_ID && $packet->sound === LevelSoundEvent::ATTACK_NODAMAGE) ||
                    ($event->getPacket()::NETWORK_ID === PlayerActionPacket::NETWORK_ID && $packet->action === PlayerAction::START_BREAK)
                )
            ){
                $player->addClick();
            }
        }
    }
}