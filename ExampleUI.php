<?php

namespace me\frogas\examples;

use pocketmine\Player;
use pocketmine\event\Listener;
//Import class MenuForm!
use pocketmine\form\MenuForm;

class ExampleUI extends PluginBase implements Listener {
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function sendManage(Player $player){
        $player->sendForm(new MenuForm("YOUR TITLE", "YOUR CONTENT", [
                ["text" => "Your Button 1"],
                ["text" => "Your Button 2"]
            ], function(Player $player, int $selected) : void {
                if($selected == 0){ // For response 'Your Button 1'
                    $player->sendMessage("You've selected Your button " . $selected);
                }
            })
        );
    }
}
