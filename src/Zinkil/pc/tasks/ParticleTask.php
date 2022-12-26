<?php

declare(strict_types=1);

namespace Zinkil\pc\tasks;

use pocketmine\scheduler\Task;
use pocketmine\level\particle\FlameParticle;
use Zinkil\pc\Core;

class ParticleTask extends Task{
	
	public function __construct(Core $plugin){
		$this->plugin=$plugin;
	}
	public function onRun():void{
		$players=$this->plugin->getServer()->getOnlinePlayers();
		foreach($players as $player){
			$player->getWorld()->addParticle(new FlameParticle($player->asVector3()->add(0, 0, 0)), $player->getWorld()->getPlayers());
			$player->getWorld()->addParticle(new FlameParticle($player->asVector3()->add(0, 0.8, 0)), $player->getWorld()->getPlayers());
			$player->getWorld()->addParticle(new FlameParticle($player->asVector3()->add(0, 1.8, 0)), $player->getWorld()->getPlayers());
		}
	}
}
