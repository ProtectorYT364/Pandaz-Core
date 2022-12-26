<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;

class AnnounceCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
    {
        parent::__construct($name, $description);
        parent::setAliases(["announce"]);
    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("pc.command.announce")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		if($this->plugin->getDatabaseHandler()->isMuted($player->getName())){
			$player->sendMessage("§cYou are muted.");
			return;
		}
		$message=implode(" ", $args);
		$this->plugin->getServer()->broadcastMessage("§l§bPandaz » §r§c" . $message);
	}
}
