<?php

declare(strict_types=1);

namespace Zinkil\pc\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\lang\Translatable;
use Zinkil\pc\Core;
use Zinkil\pc\Utils;

class ExecCommand extends Command{
	
	private $plugin;
	
	public function __construct(string $name, Translatable|string $description = "")
    {
        parent::__construct($name, $description);
        parent::setAliases(["exec"]);
    }
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if($player instanceof Player){
			return;
		}
		if(!$player->hasPermission("pc.command.exec")){
			return;
		}
		Utils::offerVoteRewards(implode(" ", $args));
	}
}
