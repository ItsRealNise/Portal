<?php
declare(strict_types=1);

namespace ItsRealNise\Portal\player;

use pocketmine\entity\Vehicle;
use pocketmine\level\Location;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\Player;
use pocketmine\Server;
use ItsRealNise\Portal\block\multiblock\PortalMultiBlock;
use ItsRealNise\Portal\event\player\PlayerEnterPortalEvent;
use ItsRealNise\Portal\event\player\PlayerPortalTeleportEvent;
use ItsRealNise\Portal\TableSpoon;
use ItsRealNise\Portal\Utils;

 /** Class Session
 * @package ItsRealNise\Portal\Tablespon
 */
class PlayerSession {
    /** @var array */
    public $clientData = [];
    /** @var Player */
    private $player;
    private $inPortal;
    private $changingDimension = false;
    
    /**
     * Session constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
    }

    public function getPlayer(): Player{
        return $this->player;
    }
    
    public function getServer(): Server{
        return $this->player->getServer();
    }
    
    public function onEnterPortal(PortalMultiBlock $block): void{
        $ev = new PlayerEnterPortalEvent($this->player, $block, $block->getTeleportationDuration($this->player));
        $ev->call();
        if(!$ev->isCancelled()){
            $this->inPortal = new PlayerPortalInfo($block, $ev->getTeleportDuration());
            PlayerSessionManager::scheduleTicking($this->player);
        }
    }
    
    public function startDimensionChange(): void{
        $this->changingDimension = true;
    }
    
    public function endDimensionChange(): void{
        $this->changingDimension = false;
    }
    
    public function isChangingDimension(): bool{
        return $this->changingDimension;
    }
    
    public function tick(): void{
        if($this->inPortal->tick()){
            $this->teleport();
            $this->onLeavePortal();
        }
    }
    
    private function teleport(): void{
        $to = $this->inPortal->getBlock()->getTargetWorldInstance();
        $target = Location::fromObject(($this->player->getLevel() === $to ? TableSpoon::$overworldLevel : $to)->getSpawnLocation());
        ($ev = new PlayerPortalTeleportEvent($this->player, $this->inPortal->getBlock(), $target))->call();
        if(!$ev->isCancelled()){
            $pos = $ev->getTarget();
            if($target->getLevel() === TableSpoon::$netherLevel){
                $pos = Utils::genNetherSpawn($this->player->asPosition(), $target->getLevel());
            }
            $this->player->teleport($pos);
        }
    }
    
    public function onLeavePortal(): void{
        PlayerSessionManager::stopTicking($this->player);
        $this->inPortal = null;
    }
}
