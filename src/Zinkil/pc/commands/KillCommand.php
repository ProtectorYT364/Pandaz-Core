<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;
use Zinkil\pc\CPlayer;
use Zinkil\pc\Utils;

class KillCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
		    {
		  parent::__construct($name, $description);
		  parent::setAliases(["ka"]);
		    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->isOp()){
			if($player->isTagged()){
				$player->sendMessage("§cYou cannot use this command while in combat.");
				return;
			}
		}
		if(!isset($args[0])){
			if($player instanceof Player){
				$player->attack(new EntityDamageEvent($player, EntityDamageEvent::CAUSE_SUICIDE, 1000));
				return;
			}
		}else{
			if(!$player->isOp()){
				$player->sendMessage("§cYou cannot kill another player.");
				return;
			}
			if($this->plugin->getServer()->getPlayer($args[0])===null){
				$player->sendMessage("§cPlayer not found.");
				return;
			}
			$target=$this->plugin->getServer()->getPlayer($args[0]);
			if($target instanceof Player){
				$target->attack(new EntityDamageEvent($target, EntityDamageEvent::CAUSE_SUICIDE, 1000));
			}
		}
	}
}
