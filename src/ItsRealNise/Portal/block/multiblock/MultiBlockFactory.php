<?php
declare(strict_types=1);

// Files from multiblock namespace are borrowed from Muqsit's DimensionPortals plugin that was ported from MiNET

namespace ItsRealNise\Portal\block\multiblock;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\Obsidian;
use ItsRealNise\Portal\block\Portal;
use ItsRealNise\Portal\TableSpoon;

/**
 * Class MultiBlockFactory
 * @package ItsRealNise\Portal\block\multiblock
 */
final class MultiBlockFactory {
    
    /** @var MultiBlock[] */
    private static $blocks = [];
    
    public static function init(): void{
        TableSpoon::getInstance()->getServer()->getPluginManager()->registerEvents(new MultiBlockEventHandler(), TableSpoon::getInstance());
        self::initNether();
    }
    
    private static function initNether(): void{
        self::register(new NetherPortalFrameMultiBlock(), new Obsidian());
        self::register(new NetherPortalMultiBlock(), new Portal());
    }
    
    public static function register(MultiBlock $multiBlock, Block $block): void{
        self::$blocks[$block->getId() . ":" . $block->getDamage()] = $multiBlock;
        foreach(BlockFactory::getBlockStatesArray() as $state){
            if($state->getId() === $block->getId()){
                self::$blocks[$state->getId() . ":" . $state->getDamage()] = $multiBlock;
            }
        }
    }
    
    /**private static function initEnd(): void{
        self::register(new EndPortalFrameMultiBlock(), new EndPortalFrame());
        self::register(new EndPortalMultiBlock(), new EndPortal());
    }*/
    
    public static function get(Block $block): ?MultiBlock{
        return self::$blocks[$block->getId() . ":" . $block->getDamage()] ?? null;
    }
}
