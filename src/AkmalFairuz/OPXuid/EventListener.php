<?php

namespace AkmalFairuz\OPXuid;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;

class EventListener implements Listener
{
    /** @var Main */
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerLoginEvent $event
     * @priority LOWEST
     */
    public function onLogin(PlayerLoginEvent $event) {
        $player = $event->getPlayer();
        if($this->plugin->isOp($player)) {
            $player->setOp(true);
        } else {
            $player->setOp(false);
        }
    }

    /**
     * @param DataPacketReceiveEvent $event
     * @throws \ReflectionException
     * @priority NORMAL
     */
    public function onPacketReceive(DataPacketReceiveEvent $event): void {
        $packet = $event->getPacket();
        if($packet instanceof LoginPacket) {
            if($this->plugin->isWaterdogServer()) {
                if (isset($packet->clientData["Waterdog_XUID"])) {
                    $class = new \ReflectionClass($event->getPlayer());

                    $prop = $class->getProperty("xuid");
                    $prop->setAccessible(true);
                    $prop->setValue($event->getPlayer(), $packet->clientData["Waterdog_XUID"]);
                }
            }
        }
    }
}