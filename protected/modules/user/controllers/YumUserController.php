<?php

Yii::import('application.modules.user.controllers.YumController');

class YumUserController extends YumController {

    public $defaultAction = 'login';

//    public function accessRules() {
//        return array(
//            array('allow',
//                'actions' => array('index', 'view', 'login'),
//                'users' => array('*'),
//            ),
//            array('allow',
//                'actions' => array('profile', 'logout', 'changepassword', 'passwordexpired', 'delete', 'browse', 'checkinlist', 'salaryList'),
//                'users' => array('@'),
//            ),
//            array('allow',
//                'actions' => array('admin', 'delete', 'create', 'update', 'list', 'assign', 'generateData', 'csv', 'checkinlist', 'salaryCalculateByUser', 'salaryList'),
//                'expression' => 'Yii::app()->user->isAdmin()'
//            ),
//            array('allow',
//                'actions' => array('create'),
//                'expression' => 'Yii::app()->user->can("user_create")'
//            ),
//            array('allow',
//                'actions' => array('admin'),
//                'expression' => 'Yii::app()->user->can("user_admin")'
//            ),
//            array('deny', // deny all other users
//                'users' => array('*'),
//            ),
//        );
//    }

    public function actionGenerateData() {
        if (Yum::hasModule('role'))
            Yii::import('application.modules.role.models.*');
        if (isset($_POST['user_amount'])) {
            for ($i = 0; $i < $_POST['user_amount']; $i++) {
                $user = new YumUser();
                $user->username = sprintf('Demo_%d_%d', rand(1, 50000), $i);
                $user->roles = array($_POST['role']);
                $user->salt = YumEncrypt::generateSalt();
                $user->password = YumEncrypt::encrypt($_POST['password'], $user->salt);
                $user->createtime = time();
                $user->status = $_POST['status'];

                if ($user->save()) {
                    if (Yum::hasModule('profile')) {
                        $profile = new YumProfile();
                        $profile->user_id = $user->id;
                        $profile->timestamp = time();
                        $profile->firstname = $user->username;
                        $profile->lastname = $user->username;
                        $profile->privacy = 'protected';
                        $profile->email = 'e@mail.de';
                        $profile->save();
                    }
                }
            }
        }
        $this->render('generate_data');
    }

    public function actionIndex() {
        // If the user is not logged in, so we redirect to the actionLogin,
        // which will render the login Form

        if (Yii::app()->user->isGuest)
            $this->actionLogin();
        else
            $this->actionList();
    }

    public function actionStats() {
        $this->redirect($this->createUrl('/user/statistics/index'));
    }

    public function actionPasswordExpired() {
        $this->actionChangePassword($expired = true);
    }

    public function actionLogin() {
        // Do not show the login form if a session expires but a ajax request
        // is still generated
        if (Yii::app()->user->isGuest && Yii::app()->request->isAjaxRequest)
            return false;
        $this->redirect(array('/user/auth'));
    }

    public function actionLogout() {
        $this->redirect(array('//user/auth/logout'));
    }

    public function beforeAction($action) {
        if (!Yii::app()->user instanceof YumWebUser)
            throw new CException(Yum::t('Please make sure that Yii uses the YumWebUser component instead of CWebUser in your config/main.php components section. Please see the installation instructions.'));
        if (Yii::app()->user->isAdmin())
            $this->layout = Yum::module()->adminLayout;
        else
            $this->layout = Yum::module()->layout;
//        return parent::beforeAction($action);
        if (Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->createUrl('/user/auth'));

        if (ServiceUtil::getRole(true) <= 2) {// Admin - 1 and SupperAdmin - 2
            return true;
        } else {
            $action_name = $this->id . '_' . $this->action->id;
            $action_allowed = ServiceUtil::userPermissionByActionName($action_name);

            if (!$action_allowed)
                $this->redirect($this->createUrl('user/index'));

            return true;
        }
    }

    /**
     * Change password
     */
    public function actionChangePassword($expired = false) {
        $this->title = 'Change Pass';
        $id = Yii::app()->user->id;

        $user = YumUser::model()->findByPk($id);
        if (!$user)
            throw new CHttpException(403, Yum::t('User can not be found'));
        else if ($user->status <= 0)
            throw new CHttpException(404, Yum::t('User is not active'));

        $form = new YumUserChangePassword;
        $form->scenario = 'user_request';

        if (isset($_POST['YumUserChangePassword'])) {
            $form->attributes = $_POST['YumUserChangePassword'];
            $form->validate();

            if (!YumEncrypt::validate_password($form->currentPassword, YumUser::model()->findByPk($id)->password, YumUser::model()->findByPk($id)->salt))
                $form->addError('currentPassword', Yum::t('Your current password is not correct'));

            if (!$form->hasErrors()) {
                if (YumUser::model()->findByPk($id)->setPassword($form->password, YumUser::model()->findByPk($id)->salt)) {
                    Yum::setFlash('The new password has been saved');
                    Yum::log(Yum::t('User {username} has changed his password', array(
                                '{username}' => Yii::app()->user->name)));
                } else {
                    Yum::setFlash('There was an error saving the password');
                    Yum::log(Yum::t(
                                    'User {username} tried to change his password, but an error occured', array(
                                '{username}' => Yii::app()->user->name)), 'error');
                }

                $this->redirect(Yum::module()->returnUrl);
            }
        }

        if (Yii::app()->request->isAjaxRequest)
            $this->renderPartial('changepassword', array(
                'form' => $form,
                'expired' => $expired));
        else
            $this->render('changepassword', array(
                'form' => $form,
                'expired' => $expired));
    }

    // Redirects the user to the specified profile
    // if no profile is specified, redirect to the own profile
    public function actionProfile($id = null) {
        $this->redirect(array('//profile/profile/view',
            'id' => $id ? $id : Yii::app()->user->id));
    }

    /**
     * Displays a User
     */
    public function actionView() {
        $model = $this->loadUser();
        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new User.
     */
    public function actionCreate() {
        $model = new YumUser;
        if (Yum::hasModule('profile'))
            $profile = new YumProfile;
        $passwordform = new YumUserChangePassword;

        // When opening a empty user creation mask, we most probably want to
        // insert an _active_ user
        if (!isset($model->status))
            $model->status = 1;

        if (isset($_POST['YumUser'])) {
            $model->salt = YumEncrypt::generateSalt();
            $model->attributes = $_POST['YumUser'];

            if (Yum::hasModule('role'))
                $model->roles = Relation::retrieveValues($_POST);

            if (Yum::hasModule('profile') && isset($_POST['YumProfile']))
                $profile->attributes = $_POST['YumProfile'];

            if (isset($_POST['YumUserChangePassword'])) {
                if ($_POST['YumUserChangePassword']['password'] == '') {
                    $password = YumUser::generatePassword();
                    $model->setPassword($password, $model->salt);
                    Yum::setFlash(Yum::t('The generated Password is {password}', array(
                                '{password}' => $password)));
                } else {
                    $passwordform->attributes = $_POST['YumUserChangePassword'];

                    if ($passwordform->validate())
                        $model->setPassword($_POST['YumUserChangePassword']['password'], $model->salt);
                }
            }

            $model->activationKey = YumEncrypt::encrypt(microtime() . $model->password, $model->salt);

            if ($model->username == '' && isset($profile))
                $model->username = $profile->email;

            $model->validate();

            if (isset($profile))
                $profile->validate();

            if (!$model->hasErrors() && !$passwordform->hasErrors()) {
                $model->save();
                if (isset($profile)) {
                    $profile->user_id = $model->id;
                    $profile->save(array('user_id'), false);
                }
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'passwordform' => $passwordform,
            'profile' => isset($profile) ? $profile : null,
            'is_admin' => (ServiceUtil::getRole(true) <= 2) ? true : false
        ));
    }

    public function actionUpdate() {
        $model = $this->loadUser();
        $passwordform = new YumUserChangePassword();

        if (isset($_POST['YumUser'])) {
            if (!isset($model->salt) || empty($model->salt))
                $model->salt = YumEncrypt::generateSalt();

            $model->attributes = $_POST['YumUser'];
            if (Yum::hasModule('role')) {
                Yii::import('application.modules.role.models.*');
                // Assign the roles and belonging Users to the model
                $model->roles = Relation::retrieveValues($_POST);
            }

            if (Yum::hasModule('profile')) {
                $profile = $model->profile;

                if (isset($_POST['YumProfile']))
                    $profile->attributes = $_POST['YumProfile'];
            }

            // Password change is requested ?
            if (isset($_POST['YumUserChangePassword']) && $_POST['YumUserChangePassword']['password'] != '') {
                $passwordform->attributes = $_POST['YumUserChangePassword'];
                if ($passwordform->validate())
                    $model->setPassword($_POST['YumUserChangePassword']['password'], $model->salt);
            }

            if (!$passwordform->hasErrors() && $model->save()) {
                if (isset($profile))
                    $profile->save();

                $this->redirect(array('//user/user/view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'passwordform' => $passwordform,
            'profile' => isset($profile) ? $profile : false,
        ));
    }

    /**
     * Deletes a user by setting the status to 'deleted'
     */
    public function actionDelete($id = null) {
        if (!$id)
            $id = Yii::app()->user->id;

        $user = YumUser::model()->findByPk($id);

        if (Yii::app()->user->isAdmin()) {
            //This is necesary for handling human stupidity.
            if ($user && ($user->id == Yii::app()->user->id)) {
                Yum::setFlash('You can not delete your own admin account');
                $this->redirect(array('//user/user/admin'));
            }

            if ($user->delete()) {
                Yum::setFlash('The User has been deleted');
                if (!Yii::app()->request->isAjaxRequest)
                    $this->redirect('//user/user/admin');
            }
        } else if (isset($_POST['confirmPassword'])) {
            if (YumEncrypt::validate_password($_POST['confirmPassword'], $user->password, $user->salt)) {
                if ($user->delete()) {
                    Yii::app()->user->logout();
                    $this->actionLogout();
                } else
                    Yum::setFlash('Error while deleting Account. Account was not deleted');
            } else {
                Yum::setFlash('Wrong password confirmation! Account was not deleted');
            }
            $this->redirect(Yum::module()->deleteUrl);
        }

        $this->render('confirmDeletion', array('model' => $user));
    }

    public function actionBrowse() {
        $search = '';
        if (isset($_POST['search_username']))
            $search = $_POST['search_username'];

        $criteria = new CDbCriteria;

        /* 		if(Yum::hasModule('profile')) {
          $criteria->join = 'LEFT JOIN '.Yum::module('profile')->privacysettingTable .' on t.id = privacysetting.user_id';
          $criteria->addCondition('appear_in_search = 1');
          } */

        $criteria->addCondition('status = 1 or status = 2 or status = 3');
        if ($search)
            $criteria->addCondition("username = '{$search}'");

        $dataProvider = new CActiveDataProvider('YumUser', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
        )));

        $this->render('browse', array(
            'dataProvider' => $dataProvider,
            'search_username' => $search ? $search : '',
        ));
    }

    public function actionList() {
        $criteria = new CDBCriteria();
        if (ServiceUtil::getRole(true) != 1) {   //does not login as admin            
            $criteria->condition = 'id =:id';
            $criteria->params = array(
                ':id' => Yii::app()->user->id
            );
        }

        $dataProvider = new CActiveDataProvider('YumUser', array(
            'pagination' => array(
                'pageSize' => Yum::module()->pageSize,
            ),
            'criteria' => $criteria,
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionAdmin() {
        if (Yum::hasModule('role'))
            Yii::import('application.modules.role.models.*');

        $this->layout = Yum::module()->adminLayout;

        $model = new YumUser('search');

        if (isset($_GET['YumUser']))
            $model->attributes = $_GET['YumUser'];

        $this->render('admin', array('model' => $model));
    }

    /**
     * Loads the User Object instance
     * @return YumUser
     */
    public function loadUser($uid = 0) {
        if ($this->_model === null) {
            if ($uid != 0)
                $this->_model = YumUser::model()->findByPk($uid);
            elseif (isset($_GET['id']))
                $this->_model = YumUser::model()->findByPk($_GET['id']);
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested User does not exist.');
        }
        return $this->_model;
    }

    /**
     * Load user checkin data
     */
    public function actionCheckinList($id = 0) {
        $model = new CheckinDairy('search');

        //only user can see they checkin list
        if (ServiceUtil::getRole(true) == 1)
            $model->user_id = $id;
        else
            $model->user_id = Yii::app()->user->id;

        if (isset($_GET['CheckinDairy'])) {
            $model->attributes = $_GET['CheckinDairy'];
        }

        $this->render('checkinList', array(
            'model' => $model,
        ));
    }

    public function actionSalaryCalculateByUser($id, $month, $year) {
        $this->title = 'Salary Calculate';
        $criteria = new CDbCriteria();
        $criteria->condition = 'user_id=:userID AND substr(checkin_date, 4, 2)=:month AND substr(checkin_date, 7, 4)=:year';
        $criteria->params = array(
            ':userID' => $id,
            ':month' => $month <= 10 ? '0' . $month : $month,
            ':year' => $year
        );

        $workDays = CheckinDairy::model()->findAll($criteria);

        $this->render('salaryCalculate', array(
            'workList' => $workList
        ));
    }

    public function actionSalaryList() {
        $this->title = 'Salary List';

        //ajax search
        $id = isset($_GET['uid']) ? (int) $_GET['uid'] : ((ServiceUtil::getRole(true) == 1) ? 0 : Yii::app()->user->id);
        $month = isset($_GET['month']) ? (int) $_GET['month'] : CURRENT_MONTH;
        $year = isset($_GET['year']) ? (int) $_GET['year'] : CURRENT_YEAR;


        $criteria = new CDbCriteria();
        $criteria->select = 't.user_id, user.username as Uname, t.checkin_date, str_to_date( t.checkin_date, "%d-%m-%Y" ) AS cdate, t.start_time, t.end_time, sum(t.end_time - t.start_time) AS sum_time, t.holiday_type';
        $criteria->join = 'INNER JOIN user ON user.id = t.user_id';
        $criteria->condition = 'substr(t.checkin_date, 7, 4) =:year and t.user_id =:id ';
        $criteria->params = array(
            ':id' => $id,
            ':year' => $year
        );
        $criteria->group = 'cdate';

        /**
         * if current month is Jan-2014, then the select query month should be the previous month and year
         * By default, the calculation of salary is calculated by 26 of previous month to 25 of this month
         * Ex: salary of April should be: 26-March-2013 to 25-April-2013
         */
        if (($month - 1) == 0) {
            $criteria->having = "cdate BETWEEN '" . ($year - 1) . "-12-26' AND '" . $year . "-" . ($month) . "-25'";
        } else
            $criteria->having = "cdate BETWEEN '" . $year . "-" . ($month - 1) . "-26' AND '" . $year . "-" . ($month) . "-25'";

        $criteria->order = 'cdate';
//        echo '<pre>';print_r($criteria);echo '</pre>';

        $checkin_model = CheckinDairy::model();
        $dataProvider = new CActiveDataProvider('CheckinDairy', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 31
            )
        ));
        $workTime = ServiceUtil::workingHoursInMonth($id, $month, $year);

        $this->render('salaryList', array(
            'dataProvider' => $dataProvider,
            'workTimeTable' => $workTime
        ));
    }

    /**
     * Auto logout user if the time up is coming
     */
    public function actionAutoLogout() {
        $this->actionLogout();
    }

    /*
     * Export excel for user checkin list      
     * Solution link: http://www.yiiframework.com/extension/yiiexcel/
     */

    public function CheckinListExport($uid, $month, $year, $objExcel) {
        $criteria = new CDbCriteria();
        $criteria->condition = "user_id=:id and substr(t.checkin_date, 7, 4) =:year and substr(checkin_date, 4, 2)=:month ";
        $criteria->params = array(
            ':id' => $uid,
            ':month' => $month <= 10 ? '0' . $month : $month,
            ':year' => $year,
        );

        $checkinList = CheckinDairy::model()->findAll($criteria);

        //init column array
        $i = 1; //The order numnber, begin with 1
        $j = 3; //The number of the started row in excel table
        $colCheckinList = array(
            0 => 'A',
            1 => 'B',
            2 => 'C',
            3 => 'D',
            4 => 'E'
        );

        if ($checkinList !== null) {
            foreach ($checkinList as $k => $v) {
                $objExcel->setCellValue($colCheckinList[0] . $j, $i++);
                $objExcel->setCellValue($colCheckinList[1] . $j, $v->checkin_date);
                $objExcel->setCellValue($colCheckinList[2] . $j, date("H:i:s", $v->start_time));
                $objExcel->setCellValue($colCheckinList[3] . $j, date("H:i:s", $v->end_time));
                $objExcel->setCellValue($colCheckinList[4] . $j++, $v->session);
            }
        }
    }

    /**
     * Export salary list
     */
    public function actionExportSalary() {
        $uid = $_GET['uid'];
        $month = $_GET['month'];
        $year = $_GET['year'];

        //create criteria material
        $criteria = new CDbCriteria();
        $criteria->select = 't.user_id, user.username as Uname, t.checkin_date, str_to_date( t.checkin_date, "%d-%m-%Y" ) AS cdate, t.start_time, t.end_time, sum(t.end_time - t.start_time) AS sum_time, t.holiday_type';
        $criteria->join = 'INNER JOIN user ON user.id = t.user_id';
        $criteria->condition = 'substr(t.checkin_date, 7, 4) =:year and t.user_id =:id ';
        $criteria->params = array(
            ':id' => $uid,
            ':year' => $year
        );
        $criteria->group = 'cdate';
        $criteria->having = "cdate BETWEEN '" . $year . "-" . ($month - 1) . "-26' AND '" . $year . "-" . ($month) . "-25'";
        $criteria->order = 'cdate';

        //gerenate select data
        $checkin_model = CheckinDairy::model()->findAll($criteria);

        // EXCEP EXPORT BEGIN
        $inputFileName = Yii::getPathOfAlias('application.vendors') . DIRECTORY_SEPARATOR . "phpexcel" . DIRECTORY_SEPARATOR . "ExportSample.xlsx";

        //load sample excel
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        //set column and row
        $objPHPExcel->getActiveSheet(0)->getColumnDimension()->setAutoSize(false);
        $objPHPExcel->getActiveSheet(0)->getDefaultColumnDimension()->setWidth(17);
        $objPHPExcel->getActiveSheet(0)->getDefaultRowDimension()->setRowHeight(20);
        //create writer
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
        //get active sheet
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);

        //Excel column name in array
        $i = 1;   //The order numnber, begin with 1
        $j = 5;   //The number of the started row in excel table
        $colName = array(
            0 => 'A',
            1 => 'B',
            2 => 'C',
            3 => 'D'
        );
        //set cell attribute
        $styleArray = array(
            'font' => array(
                'color' => array(
                    'argb' => 'FFFFFF'
                ),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'startcolor' => array(
                    'argb' => '4BACC6', //normal_type color
                ),
                'endcolor' => array(
                    'argb' => '4BACC6', //normal_type color
                ),
            ),
        );

        //get user name 
        $user = YumUser::model()->find('id =' . $uid);
        $username = $user->profile->firstname . "_" . $user->profile->lastname;
        $objWorksheet->setCellValue('B2', $username);
        //assign report date
        $objWorksheet->setCellValue('B3', date('d-m-Y H:i', time()));

        //assign data into excel template
        foreach ($checkin_model as $k => $v) {
//            echo $v->checkin_date." ".round((float)$v->sum_time/3600, 2)." ".$v->holiday_type."<br/>";
            $objWorksheet->setCellValue($colName[0] . $j, $i++);
            $objWorksheet->setCellValue($colName[1] . $j, $v->checkin_date);
            $objWorksheet->setCellValue($colName[2] . $j, round((float) $v->sum_time / 3600, 2));
            switch ($v->holiday_type) {
                case 0: //normal
                    $objWorksheet->getStyle($colName[3] . $j)->applyFromArray($styleArray);
                    break;
                case 1: //weekend
                    $styleArray['fill']['startcolor']['argb'] = 'F79646';
                    $styleArray['fill']['endcolor']['argb'] = 'F79646';
                    $objWorksheet->getStyle($colName[3] . $j)->applyFromArray($styleArray);
                    break;
                case 2: //public holiday
                    $styleArray['fill']['startcolor']['argb'] = 'FF0000';
                    $styleArray['fill']['endcolor']['argb'] = 'FF0000';
                    $objWorksheet->getStyle($colName[3] . $j)->applyFromArray($styleArray);
                    break;
                case 3: //weekend meet holiday
                    $styleArray['fill']['startcolor']['argb'] = '00B050';
                    $styleArray['fill']['endcolor']['argb'] = '00B050';
                    $objWorksheet->getStyle($colName[3] . $j)->applyFromArray($styleArray);
                    break;
                default :
                    $objWorksheet->getStyle($colName[3] . $j)->applyFromArray($styleArray);
            }
            $objWorksheet->setCellValue($colName[3] . $j, ServiceUtil::getHolidayName($v->holiday_type));
            $j++;
        }

        //add salary statistic table
        $this->salaryExportStatistic($uid, $month, $year, $objWorksheet);

        //change excel sheet to checkin list
        $objWorkSheet_CheckinList = $objPHPExcel->setActiveSheetIndex(1);
        //add checkin list information
        $this->CheckinListExport($uid, $month, $year, $objWorkSheet_CheckinList);

        //change back to the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //salary export folder
        $excelFolderDirectory = Yii::getPathOfAlias('webroot') .
        DIRECTORY_SEPARATOR . "protected" .
        DIRECTORY_SEPARATOR . "vendors" .
        DIRECTORY_SEPARATOR . "salary_export";
        
        if (!is_dir($excelFolderDirectory))
            mkdir($excelFolderDirectory, 0777, TRUE);
        else {
            $yearFolder = date('Y', time());
            $monthFolder = date('m', time());

            if (!is_dir($excelFolderDirectory . DIRECTORY_SEPARATOR . $yearFolder)) {   //check year folder
                mkdir($excelFolderDirectory . DIRECTORY_SEPARATOR . $yearFolder, 0777, TRUE);
            }
            if (!is_dir($excelFolderDirectory . DIRECTORY_SEPARATOR . $yearFolder . DIRECTORY_SEPARATOR . $monthFolder))   //check month folder
                mkdir($excelFolderDirectory . DIRECTORY_SEPARATOR . $yearFolder . DIRECTORY_SEPARATOR . $monthFolder, 0777, TRUE);

            $objWriter->save(
                    $excelFolderDirectory .
                    DIRECTORY_SEPARATOR .
                    $yearFolder .
                    DIRECTORY_SEPARATOR .
                    $monthFolder .
                    DIRECTORY_SEPARATOR .
                    $username . "_" . date('Y-m-d', time()) . ".xlsx"
            );
        }

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$username. "_" .date('Y-m-d', time()).'.xlsx"');
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/download');
        header('Content-Description: File Transfer');
        $objWriter->save('php://output'); 

        Yii::app()->end();
    }

    /**
     * Salry excel statistic table
     * * */
    public function salaryExportStatistic($id, $month, $year, $objExcel) {
        //select user salary information in given month & year
        $criteria = new CDbCriteria();
        $criteria->condition = 'user_id=:id and month =:month and year =:year';
        $criteria->params = array(
            ':id' => $id,
            ':month' => $month,
            ':year' => $year
        );
        $userSalary = UserSalary::model()->find($criteria);

        //init store variables
        $totalHours = 0;
        $roundTotalHours = 0;
        $totalDays = 0;
        $salaryAmount = ServiceUtil::getUserSalary($id);    //get user salary mode
        //get total working hours i
        $workTime = ServiceUtil::workingHoursInMonth($id, $month, $year);

        if ($userSalary === NULL) {     //if userSalary is null, save user salary 
            //get user salary record
            $salaryIndex = Salary::model()->find(array(
                'condition' => 'user_id =:id',
                'params' => array(
                    ':id' => $id
                )
            ));

            if ($salaryIndex === null)
                $salaryIndex = 0;

            //save user salary by month if it was not exited
            $userSalary = new UserSalary();
            $userSalary->user_id = $id;
            $userSalary->salary_index = $salaryIndex->id;
            $userSalary->month = $month;
            $userSalary->year = $year;
            $userSalary->total = doubleval($totalHours * $salaryAmount);

            $userSalary->save();
        }

        //assign statistic value beside salary excel
        $i = 5;
        foreach ($workTime as $k => $v) {
            $totalDays += $v['day'];
            $totalHours += $v['hour'];

            $objExcel->setCellValue('G' . $i, $v['day']);
            $objExcel->setCellValue('H' . $i++, $v['hour']);
        }

        $roundTotalHours = round($totalHours);
        $objExcel->setCellValue('G8', $totalDays);
        $objExcel->setCellValue('H8', $totalHours);
        $objExcel->setCellValue('G9', number_format($salaryAmount, 0, ' ', ',') . ' VND');
        $objExcel->setCellValue('G10', number_format($roundTotalHours * $salaryAmount, 0, ' ', ',') . ' VND');
    }

}
