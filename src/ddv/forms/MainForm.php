<?php
namespace ddv\forms;

use std, gui, framework, ddv;
use php\gui\event\UXMouseEvent;
use php\gui\UXMenuItem;
use php\gui\UXContextMenu;
use php\io\Stream;
use php\util\Scanner;
use php\io\IOException;
use php\lib\fs;

class MainForm extends AbstractForm {

    /**
     * @event construct 
     */
    function doConstruct(UXEvent $e = null) {
        if(fs::isFile("EyeSaver.conf")) { 
            try {
                $file = Stream::of("EyeSaver.conf");
                $scanner = new Scanner($file);
            
                $this->form("MainForm")->timerMain->interval = $scanner->nextLine() * 60 * 1000;
                $GLOBALS["waitSec"] = $scanner->nextLine();
                        
                $file->close();
            } catch (IOException $e) {
                alert('Не удалость прочитать файл с настройками!');
            }
        } else {
            Stream::putContents("EyeSaver.conf", "20\n30");
            $this->form("MainForm")->timerMain->interval = 20 * 60 * 1000;
            $GLOBALS["waitSec"] = 30;
        }
        
        $this->contextMenu = new UXContextMenu();
        
        $GLOBALS['itemContinue'] = new UXMenuItem('Продолжить'); 
        $GLOBALS['itemContinue']->enabled = false;
        $GLOBALS['itemContinue']->on('action', function () {
            $this->form("MainForm")->timerMain->enabled = true;
            $GLOBALS['itemContinue']->enabled = false;
            $GLOBALS['itemPause']->enabled = true;
        });                                  
        $this->contextMenu->items->add($GLOBALS['itemContinue']);   

        $GLOBALS['itemPause'] = new UXMenuItem('Приостановить');
        $GLOBALS['itemPause']->on('action', function () {
            $this->form("MainForm")->timerMain->enabled = false;
            $GLOBALS['itemContinue']->enabled = true;
            $GLOBALS['itemPause']->enabled = false;
        });                                                                   
        $this->contextMenu->items->add($GLOBALS['itemPause']);  
        
        $this->contextMenu->items->add(UXMenuItem::createSeparator());       

        $GLOBALS['itemSettings'] = new UXMenuItem('Настройка');
        $GLOBALS['itemSettings']->on('action', function () {
            $this->form("Settings")->show();
        });                                                                   
        $this->contextMenu->items->add($GLOBALS['itemSettings']);   
    
        $this->contextMenu->items->add(UXMenuItem::createSeparator());       

        $GLOBALS['itemExit'] = new UXMenuItem('Выход');
        $GLOBALS['itemExit']->on('action', function () {
            $this->systemTray->free();
            app()->shutdown();
        });                                                                   
        $this->contextMenu->items->add($GLOBALS['itemExit']); 
        
        $this->systemTray->on('click', function (UXMouseEvent $e) { 
            if ($e->button == 'SECONDARY') {
            $this->contextMenu->show($this, $e->screenX, $e->screenY);    
            }       
        });
    }
        
}