<?php

declare(strict_types=1);

namespace Zinkil\pc\tasks;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\scheduler\Task;
use Zinkil\pc\Core;
use Zinkil\pc\CPlayer;
use Zinkil\pc\Utils;

class SetDayTask extends Task{
	
	public function __construct(Core $plugin){
		$this->plugin=$plugin;
	}
	public function onRun():void{
		foreach(Server::getInstance()->getWorldManager()->getWorlds() as $level){
			$level->setTime(1000);
			$level->stopTime();
		}
	}
}
