<?php
namespace ddv\forms;

use std, gui, framework, ddv;
use php\lib\fs;

class Settings extends AbstractForm {

    /**
     * @event button.action 
     */
    function doButtonAction(UXEvent $e = null) {    
        if(is_numeric($this->main->text) && is_numeric($this->wait->text)) {
            $this->form("MainForm")->timerMain->interval = $this->main->text * 60 * 1000;
            $GLOBALS["waitSec"] = $this->wait->text;
            
            Stream::putContents("EyeSaver.conf", $this->main->text . "\n" . $GLOBALS["waitSec"]);
            
            $this->form("Settings")->hide();
        } else {
            alert("Введены не числа!");
        }
    }

    /**
     * @event construct 
     */
    function doConstruct(UXEvent $e = null) {    
        $this->main->text = $this->form("MainForm")->timerMain->interval / 60 / 1000;
        $this->wait->text = $GLOBALS["waitSec"];
    }

}
