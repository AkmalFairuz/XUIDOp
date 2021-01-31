<?php

namespace AkmalFairuz\OPXuid\command;

use AkmalFairuz\OPXuid\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class XUIDDeopCommand extends PluginCommand
{
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $plugin = $this->getPlugin();
        if($sender instanceof Player) {
            /** @var Main $plugin */
            if(!$plugin->isOp($sender)) {
                return;
            }
        }
        if(count($args) < 2) {
            $this->invalid($sender);
            return;
        }
        if($args[0] === "player") {
            $target = $plugin->getServer()->getPlayerExact($args[1]);
            if(!$target instanceof Player) {
                $sender->sendMessage(TextFormat::RED . "Player not found!");
                return;
            }
            $plugin->setOp($target, false);
            $sender->sendMessage(TextFormat::GREEN . "You remove player '".$target->getName()."' from operator!");
            return;
        } elseif ($args[0] === "xuid") {
            $plugin->setOp($args[1], false);
            $sender->sendMessage(TextFormat::GREEN . "You remove xuid '".$args[1]."' from operator!");
            return;
        }
        $this->invalid($sender);
    }

    private function invalid(CommandSender $sender) {
        $sender->sendMessage(TextFormat::RED."Usage: /xuiddeop <xuid/player> <xuid / player name>\nFor example: '/xuiddeop player AkmalFairuz' or '/xuiddeop xuid 123456789'");
    }
}