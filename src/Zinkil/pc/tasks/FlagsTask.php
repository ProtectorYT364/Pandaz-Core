<?php

declare(strict_types=1);

namespace Zinkil\pc\tasks;

use pocketmine\scheduler\Task;
use Zinkil\pc\Core;
use Zinkil\pc\Utils;
use Zinkil\pc\CPlayer;

class FlagsTask extends Task{
	
	public function __construct(Core $plugin){
		$this->plugin=$plugin;
	}
	public function onRun():void{
        $reach=0;
        $cps=0;
		$players=$this->plugin->getServer()->getOnlinePlayers();
		foreach($players as $player){
			if($player instanceof CPlayer){
                $reach=$player->getReachFlags();
                $cps=$player->getCpsFlags();
                if($reach + $cps > 70){
                    $reason="Unfair Advantage";
				    $pointsgiven=5;
				    $now=time();
				    $day=7 * 86400;
				    $hour=0 * 3600;
				    $minute=0 * 60;
				    $duration=$now + $day + $hour + $minute;
                    $this->plugin->getDatabaseHandler()->temporaryBanPlayer(Utils::getPlayerName($player), $reason, $duration, "Anti-Cheat", $pointsgiven);
			    	$target=$this->plugin->getServer()->getPlayerExact(Utils::getPlayerName($player));
				    $target->kick("§cYou have been temporarily banned.\n§fReason: ".$reason."\n§fContact us: ". $this->plugin->getDiscord(), false);
                    $this->plugin->getServer()->broadcastMessage("§4".Utils::getPlayerName($player)." was temporarily banned by Anti-Cheat.");
                    $message=$this->plugin->getStaffUtils()->sendStaffNoti("temporaryban");
				    $message=str_replace("{name}", "Anti-Cheat", $message);
			    	$message=str_replace("{target}", Utils::getPlayerName($player), $message);
			    	$message=str_replace("{reason}", $reason, $message);
				    foreach(Core::getInstance()->getServer()->getOnlinePlayers() as $online){
				    	if($online->hasPermission("pc.staff.notifications")){
                            $online->sendMessage($message);
                        }
					}
				}
            }
            $player->setCpsFlags(0);
            $player->setReachFlags(0);
		}
	}
}
