<?php

class UserSalaryController extends YumController {

    public function init() {
        parent::init();
        $this->defaultAction = 'index';
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
//    public function accessRules() {
//        return array(
//            array('allow', // allow all users to perform 'index' and 'view' actions
//                'actions' => array('index', 'view'),
//                'users' => array('*'),
//            ),
//            array('allow', // allow authenticated user to perform 'create' and 'update' actions
//                'actions' => array('create', 'update'),
//                'users' => array('@'),
//            ),
//            array('allow', // allow admin user to perform 'admin' and 'delete' actions
//                'actions' => array('admin', 'delete', 'calculateSalary'),
//                'users' => array('admin'),
//            ),
//            array('deny', // deny all users
//                'users' => array('*'),
//            ),
//        );
//    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->title = 'Create User Salary';
        
        $model = new UserSalary;        

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['UserSalary'])) {
            $model->attributes = $_POST['UserSalary'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->title = 'Update User Salary';
        
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['UserSalary'])) {
            $model->attributes = $_POST['UserSalary'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->title = "User Salary";
        
        $dataProvider = new CActiveDataProvider('UserSalary');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $this->title = 'User Salary Management';
        
        $model = new UserSalary('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserSalary']))
            $model->attributes = $_GET['UserSalary'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = UserSalary::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-salary-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    public function actionCalculateSalary(){
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        if ($id == 0)  //in case user was not admin level
            $id = Yii::app()->user->id;
                
        $month = isset($_GET['month']) ? $_GET['month'] : CURRENT_MONTH;
        $year = isset($_GET['year']) ? $_GET['year'] : CURRENT_YEAR;
        
        //init store variables
        $totalHours = 0;
        $roundTotalHours = 0;
        $totalDays = 0;
        $salaryAmount = ServiceUtil::getUserSalary($id);
        
        $criteria = new CDbCriteria();
        $criteria->condition = 'user_id=:id and month =:month and year =:year';
        $criteria->params = array(
            ':id'   => $id,
            ':month' => $month,
            ':year' => $year
        );

        $workTime = ServiceUtil::workingHoursInMonth($id, $month, $year);
        
        $userSalary = UserSalary::model()->find($criteria);
        
        if ($userSalary !== null){  //already have a salary record in user_salary table
            echo "<h3>Salary Statistic</h3>";
        }else{
            foreach ($workTime as $k => $v){
                $totalDays += $v['day'];
                $totalHours += $v['hour'];          
            }
            
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
            $userSalary->total = doubleval($totalHours*$salaryAmount);
            
            $userSalary->save();                  
        }
        
        $table = '<table width=30% class="salary"><tr><td>Type</td><td>Days</td><td>Hours</td></tr>';
        $i=0;
        foreach ($workTime as $k => $v){            
            if ($i==0)
                $class = 'holiday'; 
            elseif ($i==1)
                $class = 'weekend'; 
            else $class = '';
            
            $totalDays += $v['day'];
            $totalHours += $v['hour'];            
            $table .= '<tr style="center"><td>'.$k.'</td><td class='.$class.'>'.$v['day'].'</td><td class='.$class.'>'.$v['hour'].'</td></tr>';
            $i++;
        }
        $roundTotalHours = round($totalHours);
        $table .= '<tr><td>Total:</td><td>'.$totalDays.' Days</td><td>'.$roundTotalHours.'h ('.$totalHours.')</td></tr>';
        $table .= '<tr><td>Basic Salary</td><td colspan="2" align="center">'.number_format($salaryAmount, 0, ',', '.').' VND</td></tr>';
        $table .= '<tr><td>Sum</td><td colspan="2" align="center">'.number_format($roundTotalHours*$salaryAmount, 0, ' ', ' ').' VND</tr></table>';
        echo $table;
    }

}
