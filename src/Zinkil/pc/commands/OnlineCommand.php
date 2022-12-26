<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;

class OnlineCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
		    {
		  parent::__construct($name, $description);
		  parent::setAliases(["online", "list"]);
		    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("pc.command.online")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
			if($online->getDisplayName()!=$online->getName()){
				$onlinePlayers[]=$online->getName()." §7(".$online->getDisplayName().")§r";
			}else{
				$onlinePlayers[]=$online->getName();
			}
		}
		$count=count($this->plugin->getServer()->getOnlinePlayers());
		$max=$this->plugin->getServer()->getMaxPlayers();
		if($count==0){
			$player->sendMessage("§cThere are no players online.");
		}else{
			$player->sendMessage("§b".$count."/".$max." §3-§r ".implode(", ", $onlinePlayers));
		}
	}
}
