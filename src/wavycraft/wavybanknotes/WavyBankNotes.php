<?php

declare(strict_types=1);

namespace wavycraft\wavybanknotes;

use pocketmine\plugin\PluginBase;

use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\enchantment\Enchantment;

use pocketmine\data\bedrock\EnchantmentIdMap;

use CortexPE\Commando\PacketHooker;

class WavyBankNotes extends PluginBase {

    protected static self $instance;

    public const FAKE_ENCH_ID = -1;

    protected function onLoad() : void{
        self::$instance = $this;
    }

    protected function onEnable() : void{
        $this->saveDefaultConfig();
        $this->saveResource("messages.yml");

        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }

        $this->getServer()->getCommandMap()->register("WavyBankNotes", new BankNotesCommand($this, "banknote", "Create a bank note"));

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

        EnchantmentIdMap::getInstance()->register(
            self::FAKE_ENCH_ID,
            new Enchantment("Glow", 1, ItemFlags::ALL, ItemFlags::NONE, 1)
        );
    }

    public static function getInstance() : self{
        return self::$instance;
    }
}
