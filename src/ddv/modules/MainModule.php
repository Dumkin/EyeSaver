<?php
namespace ddv\modules;

use std, gui, framework, ddv;

class MainModule extends AbstractModule {

    /**
     * @event timerMain.action 
     */
    function doTimerMainAction(ScriptEvent $e = null) {    
        $this->timerWait->start();
        $this->form("Info")->show();
        $GLOBALS["left"] = $GLOBALS["waitSec"];
        $this->form("Info")->InfoLabel->text = "Сфокусируйтесь на объект в 5 метрах от вас\nПоморгайте 20 раз\n\nОсталось " . $GLOBALS["left"] . " секунд.";
        $GLOBALS["success"] = false;
    }

    /**
     * @event timerWait.action 
     */
    function doTimerWaitAction(ScriptEvent $e = null) {    
        if ($GLOBALS["left"] > 0) {
            $GLOBALS["left"]--;
            
            $this->form("Info")->InfoLabel->text = "Сфокусируйтесь на объект в 5 метрах от вас\nПоморгайте 20 раз\n\nОсталось " . $GLOBALS["left"] . " секунд.";
            $GLOBALS["success"] = false;
        } else {
            $this->form("Info")->InfoLabel->text = "Кликните для продолжения";
            $GLOBALS["success"] = true;
            $this->timerWait->stop();
        }
    }

}
