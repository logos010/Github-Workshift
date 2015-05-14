<?php

/**
 * This is the model class for table "checkin_dairy".
 *
 * The followings are the available columns in table 'checkin_dairy':
 * @property integer $id
 * @property integer $user_id
 * @property string $checkin_date
 * @property string $start_time
 * @property string $end_time
 * @property string $session
 * @property integer $holiday_type
 */
class CheckinDairy extends CActiveRecord {

    public $sum_time;
    public $Uname;
    public $cdate;  //checkin date in mysql format yyyy-mm-dd used with YumUserController/salaryList    

    /**
     * Returns the static model of the specified AR class.
     * @return CheckinDairy the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'checkin_dairy';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, checkin_date, start_time, session', 'required'),
            array('user_id, holiday_type', 'numerical', 'integerOnly' => true),
            array('checkin_date, start_time, end_time, session', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, user_id, checkin_date, start_time, end_time, session, holiday_type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'YumUser', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'checkin_date' => 'Checkin Date',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'session' => 'Session',
            'holiday_type' => 'Holiday Type',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('checkin_date', $this->checkin_date, true);
        $criteria->compare('start_time', $this->start_time, true);
        $criteria->compare('end_time', $this->end_time, true);
        $criteria->compare('session', $this->session, true);

        $month = isset($_GET['checkin_month']) ? $_GET['checkin_month'] : (int) date('m', time());
        $year = isset($_GET['checkin_year']) ? $_GET['checkin_year'] : (int) date('Y', time());
        
        //just get all infomration from 26 previous month to 25 current month
        if (($month-1) ==0)
            $criteria->addCondition("str_to_date( t.checkin_date, '%d-%m-%Y' ) BETWEEN '" . ($year -1) . "-12-26' AND '" . $year . "-" . ($month) . "-25'", "AND");
        else
            $criteria->addCondition("str_to_date( t.checkin_date, '%d-%m-%Y' ) BETWEEN '" . $year . "-" . ($month - 1) . "-26' AND '" . $year . "-" . ($month) . "-25'", "AND");
                
        if (isset($_GET['holiday']) && $_GET['holiday'] != 'all')
            $criteria->addCondition("holiday_type = " . $_GET['holiday']);
        
        $criteria->order = 'checkin_date';
        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 50
                    )
                ));
    }

}