<?php

/**
 * This is the model class for table "user_salary".
 *
 * The followings are the available columns in table 'user_salary':
 * @property integer $id
 * @property integer $user_id
 * @property integer $salary_index
 * @property string $month
 * @property string $year
 * @property double $total
 */
class UserSalary extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserSalary the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_salary';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, salary_index, month, year, total', 'required'),
			array('user_id, salary_index', 'numerical', 'integerOnly'=>true),
			array('total', 'numerical'),
			array('month', 'length', 'max'=>2),
			array('year', 'length', 'max'=>4),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, salary_index, month, year, total', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'salary_index' => 'Salary Index',
			'month' => 'Month',
			'year' => 'Year',
			'total' => 'Total',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('salary_index',$this->salary_index);
		$criteria->compare('month',$this->month,true);
		$criteria->compare('year',$this->year,true);
		$criteria->compare('total',$this->total);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}