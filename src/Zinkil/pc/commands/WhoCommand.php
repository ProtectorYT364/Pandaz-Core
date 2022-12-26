<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;

class WhoCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
		    {
		  parent::__construct($name, $description);
		  parent::setAliases(["pinfo"]);
		    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("pc.command.who")){
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
		$target=$this->plugin->getServer()->getPlayer($args[0]);
		$rank=$target->getRank();
		if($target->isOp()){
			$op="True";
		}else{
			$op="False";
		}
		$displayname=$target->getDisplayName();
		$controls=$this->plugin->getPlayerControls($target);
		$device=$this->plugin->getPlayerDevice($target);
		$os=$this->plugin->getPlayerOs($target);
		$ip=$target->getAddress();
		$cid=$target->getClientId();
		$ping=$target->getPing();
		$level=$target->getWorld()->getName();
		$xuid=$target->getXuid();
		$uniqueid=$target->getUniqueId();
		$player->sendMessage("\n\n§bDisplay Name:§6 ".$displayname."\n§bIP:§6 ".$ip."\n§bClientID:§6 ".$cid."\n§bRank:§6 ".$rank."\n§bOP:§6 ".$op."\n§bDevice:§6 ".$device."\n§bOS:§6 ".$os."\n§bControls:§6 ".$controls."\n§bPing:§6 ".$ping."\n§bWorld:§6 ".$level."\n§bXuid:§6 ".$xuid."\n§bUnique ID:§6 ".$uniqueid."\n\n");
	}
}
