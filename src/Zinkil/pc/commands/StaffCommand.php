<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;
use Zinkil\pc\CPlayer;
use Zinkil\pc\Utils;

class StaffCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
	    {
	  parent::__construct($name, $description);
	  parent::setAliases(["staff"]);
	    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player instanceof Player){
			return;
		}
		if(!$player->hasPermission("pc.command.staff")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		if($this->plugin->getDuelHandler()->isInDuel($player) or $this->plugin->getDuelHandler()->isInBotDuel($player)){
			$player->sendMessage("§cYou cannot use this command while in a duel.");
			return;
		}
		if(!$player->isOp()){
			if($player->isTagged()){
				$player->sendMessage("§cYou cannot use this command while in combat.");
				return;
			}
		}
		if($player->isInParty()){
			$player->sendMessage("§cYou cannot use this command while in a party.");
			return;
		}
		if(!$player->isStaffMode()){
			$this->plugin->getStaffUtils()->staffMode($player, true);
		}else{
			$this->plugin->getStaffUtils()->staffMode($player, false);
		}
	}
}
