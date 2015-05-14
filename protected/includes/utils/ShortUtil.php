<?php

/**
 * Description of ShortUtil
 *
 * @author HEO
 */
class ShortUtil {

    public static function app() {
        return Yii::app();
    }
    
    public static function user() {
        return Yii::app()->user;
    }
    
    public static function module($m){
        return ShortUtil::app()->getModule($m);
    }
    
    public static function language() {
        return Yii::app()->language;
    }

    public static function webroot() {
        return Yii::getPathOfAlias('webroot');
    }
    
    public static function baseUrl() {
        return Yii::app()->baseUrl;
    }
    
    public static function homeUrl() {
        return Yii::app()->homeUrl;
    }
    
    public static function themePath() {
        return Yii::app()->theme->baseUrl;
    }
    
    public static function clientScript() {
        return Yii::app()->clientScript;
    }
    
    public static function registerScriptFile($url, $position = CClientScript::POS_HEAD) {
        ShortUtil::clientScript()->registerScriptFile($url, $position);
    }
    
    public static function registerScript($id, $script, $position = CClientScript::POS_READY) {
        ShortUtil::clientScript()->registerScript($id, $script, $position);
    }
    
    public static function registerCssFile($url, $media = '') {
        ShortUtil::clientScript()->registerCssFile($url, $media);
    }
    
    public static function registerCss($id, $css, $media='') {
        ShortUtil::clientScript()->registerCss($id, $css, $media='');
    }
   
}
