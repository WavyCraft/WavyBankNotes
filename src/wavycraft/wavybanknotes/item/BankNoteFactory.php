<?php

declare(strict_types=1);

namespace wavycraft\wavybanknotes\item;

use pocketmine\player\Player;

use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat as TextColor;

use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\enchantment\EnchantmentInstance;

use pocketmine\data\bedrock\EnchantmentIdMap;

use wavycraft\wavybanknotes\WavyBankNotes;

final class BankNoteFactory {
    use SingletonTrait;

    public function createBankNote(Player $player, int $amount) : Item{
        $config = WavyBankNotes::getInstance()->getConfig();
        $bankNote = StringToItemParser::getInstance()->parse($config->get("bank_note_item"));
        $customName = $config->get("bank_note_name");
        $customName = str_replace("{amount}", (string)number_format($amount), $customName);
        $bankNote->setCustomName(TextColor::colorize($customName));
        $lore = $config->get("bank_note_lore");
        $lore = array_map(function($line) use ($amount) {
            return TextColor::colorize(str_replace("{amount}", (string)number_format($amount), $line));
        }, $lore);
        $bankNote->setLore($lore);
        $bankNote->getNamedTag()->setInt("Amount", $amount);
        $bankNote->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(WavyBankNotes::FAKE_ENCH_ID), 1));
        return $bankNote;
    }
}
