<?php

declare(strict_types=1);

namespace Zinkil\pc\tasks\onetime;

use pocketmine\world\World;
use pocketmine\scheduler\Task;
use Zinkil\pc\Core;
use Zinkil\pc\Utils;

class OpenArenaTask extends Task{
	
	private $arena;
	
	public function __construct(Core $plugin, string $arena){
		$this->plugin=$plugin;
		$this->arena=$arena;
	}
	public function onRun():void{
		$this->plugin->getArenaHandler()->setArenaOpen($this->arena);
	}
}
