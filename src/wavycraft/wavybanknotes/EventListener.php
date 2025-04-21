<?php

declare(strict_types=1);

namespace wavycraft\wavybanknotes;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\utils\Config;

use wavycraft\wavyeconomy\api\WavyEconomyAPI;

use terpz710\messages\Messages;

class EventListener implements Listener {

    public function interact(PlayerInteractEvent $event) : void{
        $player = $event->getPlayer();
        $item = $event->getItem();
        $action = $event->getAction();
        $config = new Config(WavyBankNotes::getInstance()->getDataFolder() . "messages.yml");

        if ($action === PlayerInteractEvent::LEFT_CLICK_BLOCK || $action === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            if ($item->getNamedTag()->getTag("Amount")) {
                $amount = $item->getNamedTag()->getInt("Amount");
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item);
                WavyEconomyAPI::getInstance()->addMoney($player->getName(), $amount);
                $player->sendMessage((string) new Messages($config, "claim-message", ["{amount}"], [number_format($amount)]));
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event) : void{
        if ($event->getItem()->getNamedTag()->getTag("Amount")) {
            $event->cancel();
        }
    }
}