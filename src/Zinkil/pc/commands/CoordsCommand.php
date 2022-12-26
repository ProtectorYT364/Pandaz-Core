<?php

namespace Zinkil\pc\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;
use Zinkil\pc\CPlayer;
use Zinkil\pc\Utils;

class CoordsCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
    {
        parent::__construct($name, $description);
        parent::setAliases(["coords"]);
    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player instanceof Player){
			return;
		}
		if(!$player->hasPermission("pc.command.coords")){
			$player->sendMessage("§cYou can't execute this command.");
			return;
		}
		if(!$player->isOp()){
			if($player->isTagged()){
				$player->sendMessage("§cYou cannot use this command while in combat.");
				return;
			}
		}
		if(!$player->isCoordins()){
			$this->plugin->getStaffUtils()->coords($player, true);
		}else{
			$this->plugin->getStaffUtils()->coords($player, false);
		}
	}
}
