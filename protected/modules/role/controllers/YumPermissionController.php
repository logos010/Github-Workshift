<?

Yii::import('application.modules.user.controllers.YumController');
Yii::import('application.modules.user.models.*');
Yii::import('application.modules.role.models.*');

class YumPermissionController extends YumController {

    public $defaultAction = 'admin';

    public function init() {
        parent::init();
        $this->title = 'Permission Management';
    }

//    public function accessRules() {
//        return array(
//            array('allow',
//                'actions' => array('admin', 'create', 'index', 'delete', 'getPermissionByUser'),
//                'users' => array('admin')
//            ),
//            array('deny', // deny all other users
//                'users' => array('*'),
//            ),
//        );
//    }

    public function actionIndex() {
        $this->actionAdmin();
    }

    public function actionDelete() {
        $permission = YumPermission::model()->findByPk($_GET['id']);
        if ($permission->delete())
            Yum::setFlash(Yum::t('The permission has been removed'));
        else
            Yum::setFlash(Yum::t('Error while removing the permission'));

        $this->redirect(array('//role/permission/admin'));
    }

    public function actionAdmin() {
        $this->layout = Yum::module('role')->layout;
        $model = new YumPermission('search');
        $model->unsetAttributes();

        if (isset($_GET['YumPermission']))
            $model->attributes = $_GET['YumPermission'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionCreate() {
        $this->title = 'Create Permission';
        $this->layout = Yum::module()->adminLayout;
        $model = new YumPermission;

        $this->performAjaxValidation($model, 'permission-create-form');

        if (isset($_POST['YumPermission'])) {            
            if (is_array($_POST['YumPermission']['action']) && count($_POST['YumPermission']['action']) > 1) {
                $actions = $_POST['YumPermission']['action'];

                foreach ($actions as $action) {
                    $model = new YumPermission;
                    $this->performAjaxValidation($model, 'permission-create-form');

                    $model->attributes = $_POST['YumPermission'];
                    $model->subordinate_id = 0;
                    $model->action = $action;
                    
                    if (!$this->savePermission($model, $_POST['YumPermission'])) {
                        $model->getErrors();
                    }
                }
//                $this->redirect(array('admin'));
            } else {  //insert only 1 permission
                $model->attributes = $_POST['YumPermission'];
                $model->subordinate_id = 0;
                $model->action = $_POST['YumPermission']['action'][0];
                if ($this->savePermission($model, $_POST['YumPermission']))
                    $this->redirect(array('admin'));                
            }
        }
        $model->type = 'user'; // preselect 'user'
        $this->render('create', array('model' => $model));
    }

    public function savePermission($model, $permission) {
        if ($model->validate()) {
            if ($permission['YumPermission']['type'] == 'user') {
//                $model->subordinate = $permission['YumPermission']['subordinate_id'];                
                $model->principal = $permission['YumPermission']['principal_id'];
            } else if ($permission['YumPermission']['type'] == 'role') {
//                $model->subordinate_role = $permission['YumPermission']['subordinate_id'];
                $model->principal_role = $permission['YumPermission']['principal_id'];
            }
            //temporary set default because it is disable from create permission from
            $model->template = 0;
            $model->subordinate_id = 0;
            
            if ($model->save())
                return true;
            else
                return false;
        }
    }

    public function actionGetPermissionByUser($id) {
        echo ServiceUtil::generatePermissionCheckBox((int) $id);
    }
    
    public function generatePermissionByUser(){
        $permission = '';
    }

}
