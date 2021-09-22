<?php
declare(strict_types=1);

namespace ItsRealNise\Portal\block;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

/**
 * Class BlockManager
 * @package ItsRealNise\Portal\block
 */
class BlockManager {
    public static function init(): void{
        BlockFactory::registerBlock(new Portal(), true);
        BlockFactory::registerBlock(new Obsidian(), true);
    }
}
