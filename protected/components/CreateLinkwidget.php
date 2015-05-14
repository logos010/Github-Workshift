<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CreateLinkwidget
 *
 * @author Lawrence
 */
class CreateLinkwidget  extends CWidget{
        
    public $module;
    public $controller;
    
    public function init(){
    }
    
    public function run(){
        $this->render('displayCreateLink', array(
            'module' => $this->module,
            'controller' => $this->controller
        ));
    }
}

?>
