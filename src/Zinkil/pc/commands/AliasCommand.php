<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;

class AliasCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
    {
        parent::__construct($name, $description);
        parent::setAliases(["alias"]);
    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("pc.command.alias")){
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
		//if($target instanceof Player and $target->isOnline){
			$ip=$target->getAddress();
			$cid=$target->getClientId();
			$contentsip=file_get_contents($this->plugin->getDataFolder() . "aliases/" . $ip, true);
			$listip=implode(", ", array_unique(explode(", ", $contentsip)));
			
			$contentscid=file_get_contents($this->plugin->getDataFolder() . "aliases/" . $cid, true);
			$listcid=implode(", ", array_unique(explode(", ", $contentscid)));
			
			$player->sendMessage("§bAccounts under ".$target->getName()."'s IP: \n§7".$listip);
			$player->sendMessage("§bAccounts under ".$target->getName()."'s Client ID: \n§7".$listcid);
		//}
	}
}
