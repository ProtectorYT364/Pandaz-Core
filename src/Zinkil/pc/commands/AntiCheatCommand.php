<?php

namespace Zinkil\pc\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;
use Zinkil\pc\CPlayer;
use Zinkil\pc\Utils;

class AntiCheatCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
    {
        parent::__construct($name, $description);
        parent::setAliases(["anticheat"]);
    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player instanceof Player){
			return;
		}
		if(!$player->hasPermission("pc.command.anticheat")){
			$player->sendMessage("§cYou can't execute this command.");
			return;
		}
		if(!$player->isAntiCheatOn()){
            $this->plugin->getStaffUtils()->anticheat($player, true);
		}else{
			$this->plugin->getStaffUtils()->anticheat($player, false);
		}
	}
}
