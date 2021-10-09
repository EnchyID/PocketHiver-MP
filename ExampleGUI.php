<?php

namespace me\frogas\examples;

use pocketmine\Player;
use pocketmine\event\Listener;
//Required class!
//Import class ChestInventory or DoubleChestInventory!
use pocketmine\inventory\api\ChestInventory; //Set it to 'ChestInventory' or 'DoubleChestInventory, I can make chest gui!
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;

class ExampleGUI extends PluginBase implements Listener {
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function sendManage(Player $player){
        $inventory = new ChestInventory() //Set it to 'ChestInventory'or 'DoubleChestInventory', I can make chest gui!
        $inventory->setName("YOUR TITLE"); //This is name of chest or doublechest!
        $inventory->setViewOnly(); //boolean 'true' or 'false'
        //You can choose 'setItem' use slot or 'addItem'no slot!
        $inventory->setItem(/* slot max 26 for Chest, 53 for DoubleChest */, 0, Item::get(297, 0, 1)->setCustomName(/* Name for item */"bread"));
        $inventory->addItem(Item::get(297, 0, 1)->setCustomName("bread"));
        $inventory->setClickCallback([$this, "clickFunction"]); //return $this->clickFunction(options);
        $inventory->setCloseCallback([$this, "closeFunction"]); //return $this->closeFunction(options);
        $inventory->send($player);
    }

    public function clickFunction(Player $player, Inventory $inventory, Item $source, Item $target, int $slot){
        if($source->getCustomName($target)){
            $player->sendMessage("You've clicked " . $source->getCustomName($target) . "!");
            return true;
        }
    }

    public function closeFunction(Player $player, Inventory $inventory){
        $player->removeWindow($inventory);
    }

    //In this line there must be this function, you can copy this function
    public function onInventoryTransaction(InventoryTransactionEvent $event) : void{
        $transaction = $event->getTransaction();
        $player = $transaction->getSource();
        foreach($transaction->getActions() as $action){
            if($action instanceof SlotChangeAction){
                $inventory = $action->getInventory();
                if($inventory instanceof ChestInventory){
                    $event->setCancelled($inventory->isViewOnly());
                    $clickCallback = $inventory->getClickCallback();
                    if($clickCallback !== null){
                        $clickCallback($player, $inventory, $action->getSourceItem(), $action->getTargetItem(), $action->getSlot());
                    }
                }
            }
        }
    }
}
