<?php

Yii::import('application.modules.admin.models.Config');

/**
 * Description of ConfigUtil
 *
 * @author HEO
 */
class ConfigUtil {

    public static function get($name, $attribute = null) {
        $config = Config::model()->find('name = :name', array(':name' => $name));
        if ($attribute != null){
            $data = unserialize($config->value);
            $data = $data[$attribute];
            return $data;
        }
        return $config->value;
    }

}
