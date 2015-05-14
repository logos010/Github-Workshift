<?php
$this->breadcrumbs=array(
	'User Salaries'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UserSalary', 'url'=>array('index')),
	array('label'=>'Create UserSalary', 'url'=>array('create')),
	array('label'=>'Update UserSalary', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserSalary', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserSalary', 'url'=>array('admin')),
);
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'salary_index',
		'month',
		'year',
		'total',
	),
)); ?>
