<?php

namespace Blubberboy333\PerWorldProtect;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
	public function onEnable(){
		$this->saveDefaultConfig();2
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->serverWorlds = array();
		foreach(glob($this->getDataPath()."worlds*") as $i){
			$name = basename($i);
			array_push($name);
		}
		foreach($this->getConfig()->getAll() as $i){
			if(!(in_array($i, $serverWorlds)){
				if(!($i == "Message")){
					$this->getLogger()->info(TextFormat::YELLOW.$i." isn't a world! Please fix the config!");
				}
			}
		}
		$this->getLogger()->info(TextFormat::GREEN."Done!");
	}
	
	public function onCommand(Command $command, CommandSender $sender, $label, array $args){
		if(strtolower($command->getName()) == "pwp"){
			if($sender->hasPermission("pwp") || $sender->hasPemission("pwp.cmd")){
				if(isset($args[0])){
					if(in_array($args[0], $this->serverWorlds)){
						$this->getConfig()->set($args[0], "true");
						$this->getConfig()->save();
						$sender->sendMessage(TextFormat::GREEN."Done!");
						if($sender instanceof Player){
							$this->getLogger()->info(TextFormat::GREEN.$sender->getName()." added "$args[0]." to the protected worlds list");
						}
						return true;
					}else{
						$sender->sendMessage(TextFormat::YELLOW."There is no world by that name!");
						return true;
					}
				}else{
					$sender->sendMessage("You need to specify a world!")
					return false;
				}
			}else{
				$sender->sendMessage(TextFormat::RED."You don't have permission to use that command!");
				return true;
			}
		}
	}
	
	public function onBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		if(!($player->isOp())){
			$level = $player->getLevel()->getName();
			if(isset($this->getConfig()->get($level)){
				if($level == true){
					$player->sendMessage($this->getConfig()->get("Message"));
					$event->setCancelled(true);
				}
			}
		}
	}
	
	public function onPlace(BlockPlaceEvent $event){
		$player = $event->getPlayer();
		if(!($player->isOp())){
			$level = $player->getLevel()->getName();
			if(isset($this->getConfig()->get($level)){
				if($level == true){
					$player->sendMessage($this->getConfig()->get("Message"));
					$event->setCancelled(true);
				}
			}
		}
	}
}
