<?php

declare(strict_types=1);

namespace mohamed205\Verificato;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Internet;
use pocketmine\utils\TextFormat;

class Main extends PluginBase{

    public function onEnable(): void
    {

    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if($command->getName() == "link") {
            $this->getServer()->getAsyncPool()->submitTask(new class ($sender->getName()) extends AsyncTask {


                public function __construct(public string $player) {}

                public function onRun(): void{
                    $response = Internet::getURL("localhost:8000/api/reqcode?username=" . $this->player);
                    $this->setResult(json_decode($response->getBody(), true)["code"]);
                }

                public function onCompletion(): void{
                    $player = Server::getInstance()->getPlayerExact($this->player);
                    if($player !== null) {
                        $player->sendMessage(TextFormat::GREEN . "Uw verificatiecode is " . $this->getResult());
                    }
                }
            });
            return true;
        }
        return false;
    }

}
