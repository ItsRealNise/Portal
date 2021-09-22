<?php
declare(strict_types=1);

namespace YaN\Portal;

use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use YaN\Portal\block\BlockManager;
use YaN\Portal\block\multiblock\MultiBlockFactory;
use YaN\Portal\player\PlayerSession;
use YaN\Portal\player\PlayerSessionManager;
use YaN\Portal\task\DelayedLevelLoadTask;

/**
 * Class TableSpoon
 * @package YaN
 */
class TableSpoon extends PluginBase {
    
    public const CONFIG_VERSION = "1.1.0";
    
    /** @var Config */
    public static $cacheFile;
    /** @var Level */
    public static $netherLevel;
    /** @var array */
    public static $settings;
    /** @var Level */
    public static $overworldLevel;
    /** @var TableSpoon */
    private static $instance;
    /** @var PlayerSession[] */
    private $sessions = [];
    
    public static function getInstance(): TableSpoon{
        return self::$instance;
    }
    
    public function onLoad(){
        $this->getLogger()->info("Loading Resources...");
        $this->saveDefaultConfig();
        self::$cacheFile = new Config($this->getDataFolder() . "cache.json", Config::JSON);
        self::$settings = $this->getConfig()->getAll();
        self::$instance = $this;
    }
    
    public function onEnable(){
        $this->initManagers();
    }
    
    /**private function checkConfigVersion(){
        if(version_compare(self::CONFIG_VERSION, $this->getConfig()->get("VERSION"), "gt")){
            $this->getLogger()->warning("You've updated TableSpoon but have an outdated config! Please delete your old config for new features to be enabled and to prevent unwanted errors!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
    }*/
    
    private function initManagers(){
        $this->getScheduler()->scheduleTask(new DelayedLevelLoadTask());
        PlayerSessionManager::init();
        BlockManager::init();
        MultiBlockFactory::init();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
    
    public function onDisable(){
        self::$cacheFile->save();
    }
    
    public function createSession(Player $player): bool{
        if(!isset($this->sessions[$player->getId()])){
            $this->sessions[$player->getId()] = new PlayerSession($player);
            return true;
        }
        return false;
    }
    
    public function destroySession(Player $player): bool{
        if(isset($this->sessions[$player->getId()])){
            unset($this->sessions[$player->getId()]);
            return true;
        }
        return false;
    }
    
    /**
     * @param int $id
     * @return PlayerSession|null
     */
    public function getSessionById(int $id){
        if(isset($this->sessions[$id])){
            return $this->sessions[$id];
        }else{
            return null;
        }
    }
    
    /**
     * @param string $name
     * @return PlayerSession|null
     */
    public function getSessionByName(string $name){
        foreach($this->sessions as $session){
            if($session->getPlayer()->getName() == $name){
                return $session;
            }
        }
        return null;
    }
}
