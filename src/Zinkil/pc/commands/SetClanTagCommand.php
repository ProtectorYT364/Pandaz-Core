<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;

class SetClanTagCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
		    {
		  parent::__construct($name, $description);
		  parent::setAliases(["setclantag"]);
		    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("pc.command.announce")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		if(!isset($args[0])){
			$player->sendMessage("§cYou must provide a player.");
			return;
		}
		if($this->plugin->getServer()->getPlayer($args[0])===null){
			$player->sendMessage("§cPlayer not found.");
			return;
		}
		if(count($args) < 2){
			$player->sendMessage("§cYou must provide a message.");
			return;
		}
		$target=$this->plugin->getServer()->getPlayer(array_shift($args));
		$message=implode(" ", $args);
		$sn=$player->getDisplayName();
		$tn=$target->getDisplayName();
		if($target instanceof Player){
			if($message=="off"){
				$player->sendMessage("§aYou cleared ".$tn."'s clan tag.");
				$target->sendMessage("§aYour clan tag was cleared.");
				$target->setClanTag("");
			}else{
				$player->sendMessage("§aYou set ".$tn."'s clan tag to ".$message.".");
				$target->sendMessage("§aYour clan tag was set to ".$message.".");
				$target->setClanTag($message." ");
			}
		}
	}
}
