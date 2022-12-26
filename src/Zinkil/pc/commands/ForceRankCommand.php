<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;

class ForceRankCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
		    {
	  parent::__construct($name, $description);
		parent::setAliases(["forcerank"]);
		    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("pc.command.forcerank")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		if(!isset($args[0])){
			return;
		}
		if(!isset($args[1])){
			return;
		}
		$this->plugin->getDatabaseHandler()->setRank($args[0], $args[1]);
		$this->plugin->getLogger()->notice($args[0]." was forced the ".$args[1]." rank.");
	}
}
