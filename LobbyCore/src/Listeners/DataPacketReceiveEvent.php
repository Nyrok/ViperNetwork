<?php

namespace Nyrok\LobbyCore\Listeners;

use Exception;
use Nyrok\LobbyCore\Managers\CustomItemManager;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\BlockToolType;
use pocketmine\block\ItemFrame;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketReceiveEvent as ClassEvent;
use pocketmine\item\Axe;
use pocketmine\item\Hoe;
use pocketmine\item\Pickaxe;
use pocketmine\item\Shovel;
use pocketmine\item\Sword;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\LevelEvent;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\network\mcpe\protocol\types\PlayerAction;
use pocketmine\network\mcpe\protocol\types\PlayerAuthInputFlags;
use pocketmine\network\mcpe\protocol\types\PlayerBlockActionWithBlockInfo;
use pocketmine\Server;
use pocketmine\world\Position;

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
            if (!$packet instanceof PlayerAuthInputPacket) {
                if(isset($player->clicksData) &&
                    (
                        ($event->getPacket()::NETWORK_ID === InventoryTransactionPacket::NETWORK_ID && $packet->trData instanceof UseItemOnEntityTransactionData) ||
                        ($event->getPacket()::NETWORK_ID === LevelSoundEventPacket::NETWORK_ID && $packet->sound === LevelSoundEvent::ATTACK_NODAMAGE) ||
                        ($event->getPacket()::NETWORK_ID === PlayerActionPacket::NETWORK_ID && $packet->action === PlayerAction::START_BREAK)
                    )
                ){
                    $player->addClick();
                }
                return;
            }

            if(($packet->getInputFlags() & (1 << PlayerAuthInputFlags::UP)) !== 0){
                if($player->getPlayerProperties()->canSend("parameters.autosprint", true)){
                    $player->setSprinting();
                }
            }

            try {
                $actions = $packet->getBlockActions();
                if (is_null($actions)) return;

                foreach ($actions as $action) {
                    if (!$action instanceof PlayerBlockActionWithBlockInfo) return;

                    $pos = new Vector3($action->getBlockPosition()->getX(), $action->getBlockPosition()->getY(), $action->getBlockPosition()->getZ());

                    if ($action->getActionType() === PlayerAction::START_BREAK) {
                        if($player instanceof ViperPlayer){
                            $player->addClick();
                        }
                        $item = $player->getInventory()->getItemInHand();
                        if (!in_array($item::class , [
                            Pickaxe::class,
                            Axe::class,
                            Shovel::class,
                            Sword::class,
                            Hoe::class
                        ])) {
                            return;
                        }

                        if ($pos->distanceSquared($player->getPosition()) > 10000) {
                            return;
                        }

                        $target = $player->getWorld()->getBlock($pos);

                        $ev = new PlayerInteractEvent($player, $player->getInventory()->getItemInHand(), $target, null, $action->getFace(), PlayerInteractEvent::LEFT_CLICK_BLOCK);
                        if ($player->isSpectator()) {
                            $ev->cancel();
                        }

                        $ev->call();
                        if ($ev->isCancelled()) {
                            $event->getOrigin()->getInvManager()?->syncSlot($player->getInventory(), $player->getInventory()->getHeldItemIndex());
                            return;
                        }

                        $frameBlock = $player->getWorld()->getBlock($pos);
                        if ($frameBlock instanceof ItemFrame && $frameBlock->getFramedItem() !== null) {
                            if (lcg_value() <= $frameBlock->getItemDropChance()) {
                                $player->getWorld()->dropItem($frameBlock->getPosition(), $frameBlock->getFramedItem());
                            }
                            $frameBlock->setFramedItem(null);
                            $frameBlock->setItemRotation(0);
                            $player->getWorld()->setBlock($pos, $frameBlock);
                            return;
                        }

                        $block = $target->getSide($action->getFace());
                        if ($block->getId() === BlockLegacyIds::FIRE) {
                            $player->getWorld()->setBlock($block->getPosition(), BlockFactory::getInstance()->get(BlockLegacyIds::AIR, 0));
                            return;
                        }

                        $pass = false;
                        if (
                            ($item instanceof Pickaxe && $target->getBreakInfo()->getToolType() === BlockToolType::PICKAXE) ||
                            ($item instanceof Axe && $target->getBreakInfo()->getToolType() === BlockToolType::AXE) ||
                            ($item instanceof Shovel && $target->getBreakInfo()->getToolType() === BlockToolType::SHOVEL) ||
                            $item instanceof Sword ||
                            ($item instanceof Hoe && $target->getBreakInfo()->getToolType() === BlockToolType::HOE)
                        ) $pass = true;


                        if ($pass) {
                            if (!$player->isCreative()) {
                                $breakTime = ceil($target->getBreakInfo()->getBreakTime($player->getInventory()->getItemInHand()) * 20);
                                CustomItemManager::scheduleTask(Position::fromObject($pos, $player->getWorld()), $player->getInventory()->getItemInHand(), $player, $breakTime, $player->getInventory()->getHeldItemIndex());
                                $player->getWorld()->broadcastPacketToViewers($pos, LevelSoundEventPacket::nonActorSound(LevelSoundEvent::BREAK_BLOCK, $pos, false));
                            }
                        }
                    } elseif ($action->getActionType() === PlayerAction::ABORT_BREAK) {
                        $player->getWorld()->broadcastPacketToViewers($pos, LevelEventPacket::create(LevelEvent::BLOCK_STOP_BREAK, 0, $pos->asVector3()));
                        CustomItemManager::stopTask($player, Position::fromObject($pos, $player->getWorld()));
                    }
                }

            } catch (Exception) {

            }
        }
    }
}