<?php

declare(strict_types=1);

namespace wavycraft\wavybanknotes\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use pocketmine\utils\Config;

use wavycraft\wavybanknotes\WavyBankNotes;
use wavycraft\wavybanknotes\item\BankNoteFactory;

use wavycraft\wavyeconomy\api\WavyEconomyAPI;

use terpz710\messages\Messages;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\IntegerArgument;

class BankNoteCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("wavybanknotes.cmd");

        $this->registerArgument(0, new IntegerArgument("amount"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        $config = new Config(WavyBankNotes::getInstance()->getDataFolder() . "messages.yml");

        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game!");
            return;
        }

        if (!isset($args["amount"])) {
            $sender->sendMessage((string) new Messages($config, "command-usage-message"));
            return;
        }

        $amount = (int)$args["amount"];

        if ($amount <= 0) {
            $sender->sendMessage((string) new Messages($config, "negative-number-message"));
            return;
        }

        $api = WavyEconomyAPI::getInstance();

        if ($api->getBalance($sender->getName()) < $amount) {
            $sender->sendMessage((string) new Messages($config, "not-enough-money-message"));
            return;
        }

        $api->removeMoney($sender->getName(), $amount);
        $item = BankNoteFactory::getInstance()->createBankNote($sender, $amount);
        $sender->getInventory()->addItem($item);
        $sender->sendMessage((string) new Messages($config, "created-banknote-message", ["{amount}"], [number_format($amount)]));
    }
}
