<?
$this->breadcrumbs=array(
	Yum::t('Actions')=>array('index'),
	$model->title,
);

?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'comment',
		'subject',
	),
)); ?>
