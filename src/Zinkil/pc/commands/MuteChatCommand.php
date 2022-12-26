<?php

namespace Zinkil\pc\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;
use Zinkil\pc\Utils;

class MuteChatCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
		    {
		  parent::__construct($name, $description);
		  parent::setAliases(["muteall"]);
		    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("pc.command.mutechat")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		if(!isset($args[0])){
			$player->sendMessage("§cProvide an argument: on:off");
			return true;
		}
		if(isset($args[0])){
			switch($args[0]){
				case "on":
				if(Utils::getGlobalMute()===true){
					$player->sendMessage("§cChat is already silenced.");
					return;
				}
				Utils::setGlobalMute(true);
				$this->plugin->getServer()->broadcastMessage("§b".$player->getName()." silenced chat.");
				break;
				case "off":
				if(Utils::getGlobalMute()===false){
					$player->sendMessage("§cChat is not silenced.");
					return;
				}
				Utils::setGlobalMute(false);
				$this->plugin->getServer()->broadcastMessage("§b".$player->getName()." unsilenced chat.");
				break;
				default:
				$player->sendMessage("§cProvide a valid argument: on:off");
			}
		}
	}
}
