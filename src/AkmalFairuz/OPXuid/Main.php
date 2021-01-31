<?php

namespace AkmalFairuz\OPXuid;

use AkmalFairuz\OPXuid\command\XUIDDeopCommand;
use AkmalFairuz\OPXuid\command\XUIDOpCommand;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    /** @var Config */
    private $config;

    /** @var array */
    private $operators;

    public function onEnable()
    {
        $this->saveResource("config.yml");
        $this->saveResource("ops.txt");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->operators = explode("\n", file_get_contents($this->getDataFolder() . "ops.txt"));

        $map = $this->getServer()->getCommandMap();
        if($this->config->get("disable_op_command")) {
            $cmd = $map->getCommand("op");
            if($cmd !== null) {
                $cmd->unregister($map);
            }

            $cmd = $map->getCommand("deop");
            if($cmd !== null) {
                $cmd->unregister($map);
            }
        }
        $map->register("xuidop", new XUIDOpCommand("xuidop", $this));
        $map->register("xuiddeop", new XUIDDeopCommand("xuiddeop", $this));

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    public function isOp(Player $player) : bool {
        if($player->getXuid() == "") return false;
        return in_array($player->getXuid(), $this->operators);
    }

    /**
     * @param Player|string $xuid
     * @param bool $op
     */
    public function setOp($xuid, bool $op = true) {
        if($xuid instanceof Player) {
            if($op === false) {
                $xuid->setOp(false);
            } else {
                $xuid->setOp(true);
            }
            $xuid = $xuid->getXuid();
        }
        if($op !== false) {
            $this->operators[] = $xuid;
        } else {
            foreach ($this->operators as $key => $operator) {
                if($operator === $xuid) {
                    unset($this->operators[$key]);
                }
            }
        }
        file_put_contents($this->getDataFolder() . "ops.txt", implode("\n", $this->operators));
    }

    public function isWaterdogServer() : bool {
        return $this->config->get("waterdog_support");
    }
}
