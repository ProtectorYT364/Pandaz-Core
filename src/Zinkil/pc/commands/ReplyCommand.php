<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;
use Zinkil\pc\CPlayer;

class ReplyCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
		    {
		  parent::__construct($name, $description);
		  parent::setAliases(["r"]);
		    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player instanceof Player){
			return;
		}
		if(!$player->hasRe()){
			$player->sendMessage("§cYou have no one to reply to.");
			return;
		}
		if(count($args) < 1){
			$player->sendMessage("§cYou must provide a message.");
			return;
		}
		$target=$player->getRe();
		$message=implode(" ", $args);
		$sn=$player->getDisplayName();
		$tn=$target->getDisplayName();
		if($target instanceof Player){
			$player->sendMessage("§7(To §f".$tn."§7) ".$message);
			$target->sendMessage("§7(From §f".$sn."§7) ".$message);
			$target->setRe($player);
		}
		foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
			if($online->isMessages()){
				$online->sendMessage("§8[STAFF] §7(From §f".$sn." §7To §f".$tn."§7) ".$message);
			}
		}
	}
}
