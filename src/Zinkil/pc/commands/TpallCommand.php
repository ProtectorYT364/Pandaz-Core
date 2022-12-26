<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\world\Position;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;

class TpallCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
		    {
		  parent::__construct($name, $description);
		  parent::setAliases(["tpall"]);
		    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("pc.command.tpall")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
			if($online->getName()!=$player->getName() and count($this->plugin->getServer()->getOnlinePlayers()) > 1){
				$online->teleport(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getWorld()));
			}
		}
		$player->sendMessage("§aAll players have been teleported to you.");
		$message=$this->plugin->getStaffUtils()->sendStaffNoti("tpall");
		$message=str_replace("{name}", $player->getName(), $message);
		foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
			if($online->hasPermission("pc.staff.notifications")){
				$online->sendMessage($message);
			}
		}
	}
}
