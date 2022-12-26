<?php

declare(strict_types=1);

namespace Zinkil\pc\tasks;

use pocketmine\scheduler\Task;
use Zinkil\pc\Core;
use Zinkil\pc\CPlayer;

class PlayerTask extends Task{
	
	public function __construct(Core $plugin){
		$this->plugin=$plugin;
	}
	public function onRun():void{
		$players=$this->plugin->getServer()->getOnlinePlayers();
		foreach($players as $player){
			if($player instanceof CPlayer){
				$player->update();
			}
		}
	}
}
