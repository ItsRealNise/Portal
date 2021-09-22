<?php
declare(strict_types=1);

// Files from multiblock namespace are borrowed from Muqsit's DimensionPortals plugin that was ported from MiNET

namespace ItsRealNise\Portal\block\multiblock;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\Player;
use ItsRealNise\Portal\player\PlayerSessionManager;
use ItsRealNise\Portal\TableSpoon;

/**
 * Class PortalMultiBlock
 * @package ItsRealNise\Portal\block\multiblock
 */
abstract class PortalMultiBlock implements MultiBlock {
    
    /**
     * PortalMultiBlock constructor.
     */
    public function __construct(){
    }
    
    final public function getTeleportationDuration(Player $player): int{
        return $player->isAdventure() || $player->isSurvival() ? 80 : 1;
    }
    
    abstract public function getTargetWorldInstance(): Level;
    
    public function onPlayerMoveInside(Player $player, Block $block): void{
        if(!TableSpoon::$settings["dimensions"]["nether"]["enabled"]) return;
        PlayerSessionManager::get($player)->onEnterPortal($this);
    }
    
    public function onPlayerMoveOutside(Player $player, Block $block): void{
        if(!TableSpoon::$settings["dimensions"]["nether"]["enabled"]) return;
        PlayerSessionManager::get($player)->onLeavePortal();
    }
}
