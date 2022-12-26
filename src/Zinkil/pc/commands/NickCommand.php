<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use pocketmine\entity\Skin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;
use Zinkil\pc\CPlayer;
use Zinkil\pc\Utils;

class NickCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
		    {
		  parent::__construct($name, $description);
		  parent::setAliases(["nick"]);
		    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player instanceof Player){
			return;
		}
		if(!$player->hasPermission("pc.command.nick")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		if(!$player->isOp()){
			if($player->isTagged()){
				$player->sendMessage("§cYou cannot use this command while in combat.");
				return;
			}
		}
		if(!isset($args[0])){
			$player->sendMessage("§cYou must provide a nick.");
			return;
		}
		switch($args[0]){
			case "off":
			$player->setDisplayName($player->getName());
			$player->sendMessage(TF::GREEN."Your are no longer nicked.");
			break;
			default:
			$nick=$args[0];
			foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
				if(strtolower($nick)==strtolower($online->getDisplayName())){
					$player->sendMessage(TF::RED."You cannot use that nick.");
					return;
				}
			}
			if(strlen($nick) < 3){
				$player->sendMessage(TF::RED."Your nick must have more than 3 characters.");
				return;
			}
			if(strlen($nick) > 12){
				$player->sendMessage(TF::RED."Your nick must not have more than 12 characters.");
				return;
			}
			$player->setDisplayName($nick);
			$player->sendMessage(TF::GREEN."You are now nicked as ".$nick.".");
			break;
		}
	}
}
