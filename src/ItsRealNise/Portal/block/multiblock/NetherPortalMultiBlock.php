<?php
declare(strict_types=1);

// Files from multiblock namespace are borrowed from Muqsit's DimensionPortals plugin that was ported from MiNET

namespace ItsRealNise\Portal\block\multiblock;

use pocketmine\block\Block;
use pocketmine\block\Obsidian;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\Player;
use ItsRealNise\Portal\TableSpoon;

/**
 * Class NetherPortalMultiBlock
 * @package ItsRealNise\Portal\block\multiblock
 */
class NetherPortalMultiBlock extends PortalMultiBlock {
    
    /** @var int */
    private $frameID;
    
    public function __construct(){
        parent::__construct();
        $this->frameID = (new Obsidian())->getId();
    }
    
    public function getTargetWorldInstance(): Level{
        return TableSpoon::$netherLevel;
    }
    
    public function update(Block $block): bool{
        return false;
    }
    
    public function isValid(Block $block): bool{
        $blockId = $block->getId();
        return $blockId === $this->frameID || $blockId === Block::PORTAL;
    }
    
    public function interact(Block $wrapping, Player $player, Item $item, int $face): bool{
        return false;
    }
}
