<?php

// Copyright Zeyroz#0001

namespace zeyroz\lobby;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\Form;
use jojoe77777\FormAPI\FormAPI;
use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\Server;
use libpmquery\PMQuery;

class Main extends PluginBase implements Listener{

    /** @var string[] */
    private $config;

    public function onEnable(): void
    {
        $this->config = $this->getConfig()->getAll();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->notice("§d§lLe plugin LobbyCommand ON [Crée par Zeyroz#0001]");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if($command->getName() === "lobby"){
            if($sender instanceof Player){
                $this->openForm($sender);
            }else{
                $sender->sendMessage($this->config["notaplayer"]);
            }
        }return true;
    }

    public function openForm(Player $player) {

        $form = new SimpleForm(function (Player $player, $data){
            if ($data === null) {
                return true;
            }
            switch ($data) {
                case 0:
                    $player->transfer($this->config["ip"], $this->config["port"]);
					break;
                case 1:
                    break;
            }
        }
        );
        $query = $this->config["query"];
        if($query == "on") {
            $query2 = PMQuery::query($this->config["ip"], $this->config["port"]);
            $playernumber = $query2['Players'];
        }else{
            $playernumber = "x";
        }
        $form->setTitle($this->config["title"]);
        $form->setContent(str_replace(array("{connected}" ,"&n") , array($playernumber , "\n"), $this->config["content"]));
        $form->addButton($this->config["buttonyes"]);
        $form->addButton($this->config["buttonno"]);
        $form->sendToPlayer($player);
    }
}