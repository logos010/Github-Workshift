<?php

/**
 * Description of ControllerBase
 *
 * @author HAO
 */
class ControllerBase extends CController {
    const FLASH_ERROR = 'error';
    const FLASH_NOTICE = 'notice';
    const FLASH_SUCCESS = 'success';

    public $layout = '//layouts/0-1-0';
    public $menu = array();
    public $breadcrumbs = array();
    public $title = SITE_NAME;
    public $description = '';
    public $keyword = '';

    public function init() {
        Yii::app()->language = LOCALE;
//        Yii::app()->language = (isset(Yii::app()->request->cookies['language']->value)) ? Yii::app()->request->cookies['language']->value : LOCALE;
    }

    public function setFlash($flashes, $category = self::FLASH_SUCCESS) {
        ShortUtil::user()->setFlash($category, $flashes);
    }

    public function getFlash($category = self::FLASH_SUCCESS) {
        if ($this->hasFlash($category)) {
            $flashes = ShortUtil::user()->getFlash($category);
            echo '<div class="flash-' . $category . '">';
            if (is_array($flashes)) {
                echo '<ul>';
                foreach ($flashes as $flash)
                    echo '<li>' . $flash . '</li>';
                echo '</ul>';
            }else
                echo $flashes;
            echo '</div>';
            ShortUtil::app()->clientScript->registerScript('fade', "setTimeout(function() { $('.flash-" . $category . "').fadeOut('slow'); }, 5000);");
        }
    }

    public function hasFlash($category = self::FLASH_SUCCESS) {
        return ShortUtil::user()->hasFlash($category);
    }

    public function renderFlash() {
        $this->getFlash(self::FLASH_SUCCESS);
        $this->getFlash(self::FLASH_NOTICE);
        $this->getFlash(self::FLASH_ERROR);
    }
    
//    public function beforeAction($action){
//        if (Yii::app()->controller->module->id == 'admin'){
//            if (Yii::app()->user->isGuest) {
//                $this->redirect(Yii::app()->createUrl('user/login'));
//            }
//            
//            $userid = Yii::app()->user->id;
//            $role = Yii::app()->getModule('user')->getRole();
//            $actionName = Yii::app()->controller->getAction()->id;
//
//            $role = Util::isAllowProcess($userid, $role, 'Brand', $actionName);
//
//            if ($role == 'customer') {
//                if (count($role))
//                    return true;
//                else
//                    $this->render('role_limit');
//            }
//
//            return true;
//        }else
//            return true;
//    }

}
