<?php
namespace ddv\forms;

use std, gui, framework, ddv;


class Info extends AbstractForm
{

    /**
     * @event InfoLabel.click 
     */
    function doInfoLabelClick(UXMouseEvent $e = null)
    {    
        if ($GLOBALS["success"]) {
            $this->form("Info")->hide();
        }
    }

}
