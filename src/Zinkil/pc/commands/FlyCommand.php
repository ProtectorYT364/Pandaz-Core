<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;
class FlyCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
    {
        parent::__construct($name, $description);
        parent::setAliases(["fly"]);
    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("pc.command.fly")){
			if($this->plugin->getDatabaseHandler()->voteAccessExists($player)){
			}else{
				$player->sendMessage("§cYou cannot execute this command.");
				return;
			}
		}
		if($this->plugin->getDuelHandler()->isInDuel($player) or $this->plugin->getDuelHandler()->isInPartyDuel($player) or $this->plugin->getDuelHandler()->isInBotDuel($player)){
			$player->sendMessage("§cYou cannot use this command while in a duel.");
			return;
		}
		$level=$player->getWorld()->getName();
		if($level!=="lobby"){
			$player->sendMessage("§cYou cannot enable fly here.");
			return;
		}
		if($player->getAllowFlight()===false){
			$player->setFlying(true);
			$player->setAllowFlight(true);
			$player->sendMessage("§aYou enabled flight.");
		}else{
			$player->setFlying(false);
			$player->setAllowFlight(false);
			$player->sendMessage("§aYou disabled flight.");
		}
	}
}
