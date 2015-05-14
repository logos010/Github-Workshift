<?
$this->breadcrumbs=array(
	Yum::t('Actions')=>array('index'),
	Yum::t('Manage'),
);
//echo $name = Yii::app()->user->data()->username;
//$a = YumUser::model()->find('username="'.$name.'"')->can('user_update');
//$b = YumUser::model()->find('username="'.$name.'"')->can('user_create');
//var_dump($a);
//var_dump($b);
ServiceUtil::getRole(Yii::app()->user->id);

//echo Yii::app()->user->id;
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'action-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'title',
		'comment',
		'subject',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
