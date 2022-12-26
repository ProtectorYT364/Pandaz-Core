<?php

declare(strict_types=1);

namespace Zinkil\pc\listeners;

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\item\Item;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBurnEvent;
use pocketmine\event\block\BlockFormEvent;
use pocketmine\event\block\LeavesDecayEvent;
use pocketmine\event\block\BlockSpreadEvent;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\inventory\ChestInventory;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\block\Dirt;
use pocketmine\block\Liquid;
use pocketmine\block\Anvil;
use pocketmine\block\Bed;
use pocketmine\block\BrewingStand;
use pocketmine\block\BurningFurnace;
use pocketmine\block\Button;
use pocketmine\block\Chest;
use pocketmine\block\CraftingTable;
use pocketmine\block\Door;
use pocketmine\block\EnchantingTable;
use pocketmine\block\EnderChest;
use pocketmine\block\FenceGate;
use pocketmine\block\Furnace;
use pocketmine\block\IronDoor;
use pocketmine\block\IronTrapDoor;
use pocketmine\block\Lever;
use pocketmine\block\TrapDoor;
use pocketmine\block\TrappedChest;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\player\GameMode;
use pocketmine\world\Position;
use pocketmine\math\Vector3;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\inventory\PlayerInventory;
use pocketmine\event\inventory\InventoryTransactionEvent;
use Zinkil\pc\Core;

class WorldListener implements Listener{
	
	public $plugin;
	
	protected $opened;
	
	public function __construct(Core $plugin){
		$this->plugin=$plugin;
		$this->opened=[];
	}
	public function onSlotChange(InventoryTransactionEvent $event){
		$item=null;
		$player=null;
		$transaction=$event->getTransaction();
		$player=$transaction->getSource();
		$level=$player->getWorld()->getName();
		if($level==$this->plugin->getLobby()){
			$event->cancel();
		}
	}
	public function onInteract(PlayerInteractEvent $event){
		$player=$event->getPlayer();
		$b=$event->getBlock();
		$item=$event->getItem();
		if($b instanceof Anvil or $b instanceof Bed or $b instanceof BrewingStand or $b instanceof BurningFurnace or $b instanceof Button or $b instanceof Chest or $b instanceof CraftingTable or $b instanceof Door or $b instanceof EnchantingTable or $b instanceof EnderChest or $b instanceof FenceGate or $b instanceof Furnace or $b instanceof IronDoor or $b instanceof IronTrapDoor or $b instanceof Lever or $b instanceof TrapDoor or $b instanceof TrappedChest){
			$event->cancel();
		}
	}
	public function onLevelChange(EntityTeleportEvent $event){
		$player=$event->getEntity();
        if($event->getTo()->getWorld() !== $event->getFrom()->getWorld())
		if(!$player instanceof Player) return;
		if($player->getGamemode()===GameMode::CREATIVE()) return;
		$duel=null;
		if($this->plugin->getDuelHandler()->isInDuel($player)) $duel=$this->plugin->getDuelHandler()->getDuel($player);
		if($this->plugin->getDuelHandler()->isInBotDuel($player)) $duel=$this->plugin->getDuelHandler()->getBotDuel($player);
		if($duel===null) return;
		if($duel->isDuelRunning()){
			$event->cancel();
		}
		$level=$player->getWorld()->getName();
		if($level!=="lobby"){
			$player->setFlying(false);
			$player->setAllowFlight(false);
			$player->setScale(1);
		}
	}
	public function onLeaveDecay(LeavesDecayEvent $event){
		$block=$event->getBlock();
		$level=$block->getWorld()->getName();
		$event->cancel();
	}
	public function onBurn(BlockBurnEvent $event){
		$block=$event->getBlock();
		$level=$block->getWorld()->getName();
		$event->cancel();
	}
	public function onPlace(BlockPlaceEvent $event){
		$player=$event->getPlayer();
		$block=$event->getBlock();
		if($this->plugin->getDuelHandler()->isInDuel($player)){
			$duel=$this->plugin->getDuelHandler()->getDuel($player);
			if($duel!==null and $duel->isDuelRunning() and $duel->canBuild()){
				$toohigh=$duel->isBlockTooHigh($block->getY());
				if($toohigh===false){
					$duel->addBlock($block->getX(), $block->getY(), $block->getZ());
				}else{
					$event->cancel();
				}
			}else{
				$event->cancel();
			}
			return;
		}
		if($this->plugin->getDuelHandler()->isInPartyDuel($player)){
			$pduel=$this->plugin->getDuelHandler()->getPartyDuel($player);
			if($pduel!==null and $pduel->isDuelRunning() and $pduel->canBuild()){
				$toohigh=$pduel->isBlockTooHigh($block->getY());
				if($toohigh===false){
					$pduel->addBlock($block->getX(), $block->getY(), $block->getZ());
				}else{
					$event->cancel();
				}
			}else{
				$event->cancel();
			}
			return;
		}
		if($player->getName()!="ZINKIL YT"){
			$event->cancel();
		}
		if($player->getGamemode()==1 and $player->hasPermission("pc.can.build")){
			return;
		}else{
			$event->cancel();
		}
	}
	public function onBreak(BlockBreakEvent $event){
		$player=$event->getPlayer();
		$block=$event->getBlock();
		$vector3=new Vector3($block->getX(), $block->getY(), $block->getZ());
		if($this->plugin->getDuelHandler()->isInDuel($player)){
			$duel=$this->plugin->getDuelHandler()->getDuel($player);
			if($duel!==null and $duel->isDuelRunning() and $duel->canBuild()){
				if($duel->isBedwars()){
					if($duel->removeBlock($block->getX(), $block->getY(), $block->getZ())===false){
						$event->cancel();
					}else{
						if($block instanceof Bed){
							$event->setDrops([]);
							$duel->addBed($vector3);
						}
						if(in_array($vector3, $duel->blocks)) $duel->removeBlock($block->getX(), $block->getY(), $block->getZ());
					}
				}else{
					if($duel->removeBlock($block->getX(), $block->getY(), $block->getZ())===false){
						$event->cancel();
					}else{
						if(in_array($vector3, $duel->blocks)) $duel->removeBlock($block->getX(), $block->getY(), $block->getZ());
					}
				}
			}else{
				$event->cancel();
			}
			return;
		}
		if($this->plugin->getDuelHandler()->isInPartyDuel($player)){
			$pduel=$this->plugin->getDuelHandler()->getPartyDuel($player);
			if($pduel!==null and $pduel->isDuelRunning() and $pduel->canBuild()){
				if($pduel->removeBlock($block->getX(), $block->getY(), $block->getZ())===false){
					$event->cancel();
				}else{
					if(in_array($vector3, $pduel->blocks)) $pduel->removeBlock($block->getX(), $block->getY(), $block->getZ());
				}
			}else{
				$event->cancel();
			}
			return;
		}
		if($player->getName()!="ZINKIL YT"){
			$event->cancel();
		}
		if($player->getGamemode()==1 and $player->hasPermission("pc.can.break")){
			return;
		}else{
			$event->cancel();
		}
	}
	public function onBlockSpread(BlockSpreadEvent $event):void{
		$state=$event->getNewState();
		$block=$event->getBlock();
		$arena=$this->plugin->getArenaHandler()->getArenaClosestTo($block);
		if($arena===null){
			$event->cancel();
			return;
		}
		$this->plugin->getDuelHandler()->isArenaInUse($arena->getName());
		$duel=$this->plugin->getDuelHandler()->getDuel($arena->getName(), true);
		$pduel=$this->plugin->getDuelHandler()->getPartyDuel($arena->getName(), true);
		if($duel!==null){
			if($duel->isDuelRunning()){
				$duel->addBlock($block->getX(), $block->getY(), $block->getZ());
			}else{
				$event->cancel();
			}
		}
		if($pduel!==null){
			if($pduel->isDuelRunning()){
				$pduel->addBlock($block->getX(), $block->getY(), $block->getZ());
			}else{
				$event->cancel();
			}
		}
	}
	public function onBlockForm(BlockFormEvent $event):void{
		$state=$event->getNewState();
		$block=$event->getBlock();
		$arena=$this->plugin->getArenaHandler()->getArenaClosestTo($block);
		if($state instanceof Dirt){
			$event->cancel();
			return;
		}
		if($arena===null){
			$event->cancel();
			return;
		}
		$this->plugin->getDuelHandler()->isArenaInUse($arena->getName());
		$duel=$this->plugin->getDuelHandler()->getDuel($arena->getName(), true);
		$pduel=$this->plugin->getDuelHandler()->getPartyDuel($arena->getName(), true);
		if($duel!==null){
			if($duel->isDuelRunning()){
				$duel->addBlock($block->getX(), $block->getY(), $block->getZ());
			}else{
				$event->cancel();
			}
		}
		if($pduel!==null){
			if($pduel->isDuelRunning()){
				$pduel->addBlock($block->getX(), $block->getY(), $block->getZ());
			}else{
				$event->cancel();
			}
		}
	}
	public function onBucketFill(PlayerBucketFillEvent $event):void{
		$player=$event->getPlayer();
		$block=$event->getBlockClicked();
		$item=$event->getItem();
		$level=$player->getWorld()->getName();
		if($level==$this->plugin->getLobby()){
			if(!$player->isCreative()){
				$event->cancel();
			}
		}
		if($this->plugin->getDuelHandler()->isInDuel($player)){
			$duel=$this->plugin->getDuelHandler()->getDuel($player);
			if($duel!==null and $duel->isDuelRunning() and $duel->canBuild()){
				/*
				
				THIS function is supposed to prevent water/lava that was not
				placed in a duel to not be able to be collected into a bucket, but it
				cancels the event completely so keep DISABLED

				if($duel->removeBlock($block)===false){
					$event->cancel();
					$player->sendMessage("block isnt part of duel");
				}else{*/
					$duel->removeBlock($block->getX(), $block->getY(), $block->getZ());
				//}
			}else{
				$event->cancel();
			}
			return;
		}
		if($this->plugin->getDuelHandler()->isInPartyDuel($player)){
			$pduel=$this->plugin->getDuelHandler()->getPartyDuel($player);
			if($pduel!==null and $pduel->isDuelRunning() and $pduel->canBuild()){
				$pduel->removeBlock($block->getX(), $block->getY(), $block->getZ());
			}else{
				$event->cancel();
			}
			return;
		}
	}
	public function onBucketEmpty(PlayerBucketEmptyEvent $event):void{
		$player=$event->getPlayer();
		$block=$event->getBlockClicked();
		$item=$event->getItem();
		$level=$player->getWorld()->getName();
		if($level==$this->plugin->getLobby()){
			if(!$player->isCreative()){
				$event->cancel();
			}
		}
		if($this->plugin->getDuelHandler()->isInDuel($player)){
			$duel=$this->plugin->getDuelHandler()->getDuel($player);
			if($duel!==null and $duel->isDuelRunning() and $duel->canBuild()){
				$toohigh=$duel->isBlockTooHigh($block->getY());
				if($toohigh===false){
					$duel->addBlock($block->getX(), $block->getY(), $block->getZ());
				}else{
					$event->cancel();
				}
			}else{
				$event->cancel();
			}
			return;
		}
		if($this->plugin->getDuelHandler()->isInPartyDuel($player)){
			$pduel=$this->plugin->getDuelHandler()->getPartyDuel($player);
			if($pduel!==null and $pduel->isDuelRunning() and $pduel->canBuild()){
				$toohigh=$pduel->isBlockTooHigh($block->getY());
				if($toohigh===false){
					$pduel->addBlock($block->getX(), $block->getY(), $block->getZ());
				}else{
					$event->cancel();
				}
			}else{
				$event->cancel();
			}
			return;
		}
	}
	public function onExhaust(PlayerExhaustEvent $event){
		$player=$event->getPlayer();
		$level=$player->getWorld()->getName();
		if($level==$this->plugin->getLobby()){
			$event->cancel();
		}
	}
	public function onPrimedExplosion(ExplosionPrimeEvent $event){
		$event->setBlockBreaking(false);
	}
}
