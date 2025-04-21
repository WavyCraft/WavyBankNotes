# Description
Add in bank notes to your server using **WavyBankNotes**!

This plugin requires [WavyEconomy](https://github.com/WavyCraft/WavyEconomy/tree/main) as for this isnt a complete economy plugin but an addon for WavyEconomy!

# Features
- Configurable bank note item
- Configurable messages. Check out `messages.yml`

# API
**How create a bank note:**
```php
/** $player is instance of pocketmine/player/Player **/

/** Import this class **/
use wavycrafr\wavybanknotes\item\BankNoteFactory;

$item = BankNoteFactory::getInstance()->createBankNote($player, 100);

$player->getInventory()->addItem($item);

Its that simple!
```
