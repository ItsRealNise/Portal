<?php
declare(strict_types=1);

namespace ItsRealNise\Portal\task;

use pocketmine\scheduler\Task;
use ItsRealNise\Portal\player\PlayerSessionManager;

class SessionManagerTask extends Task {
    
    public function onRun(int $currentTick){
        foreach(PlayerSessionManager::$ticking as $playerID){
            PlayerSessionManager::$players[$playerID]->tick();
        }
    }
    
}
