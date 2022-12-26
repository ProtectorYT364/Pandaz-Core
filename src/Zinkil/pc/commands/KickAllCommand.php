<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;

class KickAllCommand extends Command{
	
	
	public function __construct(string $name, Translatable|string $description = ""){
        parent::__construct($name, $description);
        parent::setAliases(["kickall"]);
    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->isOp()){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
        }
        foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
            if(!$online->isOp()){
                $online->kick("Everyone has been kicked, you may rejoin soon.", false);
            }
        }
	}
}
