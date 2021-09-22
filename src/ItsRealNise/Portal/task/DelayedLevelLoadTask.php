<?php
declare(strict_types=1);

namespace ItsRealNise\Portal\task;

use pocketmine\level\Level;
use pocketmine\scheduler\Task;
use ItsRealNise\Portal\level\LevelManager;
use ItsRealNise\Portal\TableSpoon;

/**
 * Class DelayedLevelLoadTask
 * @package ItsRealNise\Portal\task
 */
class DelayedLevelLoadTask extends Task {
    
    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick){
        LevelManager::init();
        if(TableSpoon::$overworldLevel instanceof Level) return;
        TableSpoon::getInstance()->getScheduler()->scheduleTask(new $this());
    }
}
