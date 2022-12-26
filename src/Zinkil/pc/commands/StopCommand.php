<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;
use Zinkil\pc\tasks\onetime\RestartTask;

class StopCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
		    {
		  parent::__construct($name, $description);
		  parent::setAliases(["stop"]);
		    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("pc.command.stop")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		$this->plugin->getServer()->broadcastMessage("§bPandaz will now preform a restart.");
		$this->plugin->getScheduler()->scheduleDelayedRepeatingTask(new RestartTask($this->plugin), 60, 1);
	}
}
