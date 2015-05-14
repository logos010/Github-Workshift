<?php

/**
 * Description of GenericService
 *
 * @author HEO
 */
class GenericService extends CComponent {

    /**
     * Service result object
     * @var ServiceResult
     */
    protected $result;

    public function __construct() {
        $this->result = new ServiceResult();
    }

    public function init($module) {
        Yii::import("$module.models.*");
    }

}
