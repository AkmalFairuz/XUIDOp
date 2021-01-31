<?php


namespace AkmalFairuz\OPXuid\command;


use AkmalFairuz\OPXuid\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class XUIDOpCommand extends PluginCommand
{
    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
    }

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
            $plugin->setOp($target);
            $sender->sendMessage(TextFormat::GREEN . "You set player '".$target->getName()."' operator!");
            return;
        } elseif ($args[0] === "xuid") {
            $plugin->setOp($args[1]);
            $sender->sendMessage(TextFormat::GREEN . "You set xuid '".$args[1]."' operator!");
            return;
        }
        $this->invalid($sender);
    }

    private function invalid(CommandSender $sender) {
        $sender->sendMessage(TextFormat::RED."Usage: /xuidop <xuid/player> <xuid / player name>\nFor example: '/xuidop player AkmalFairuz' or '/xuidop xuid 123456789'");
    }
}