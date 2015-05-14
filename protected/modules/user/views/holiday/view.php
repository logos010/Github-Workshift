<?php
$this->breadcrumbs=array(
	'Holidays'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Holiday', 'url'=>array('index')),
	array('label'=>'Create Holiday', 'url'=>array('create')),
	array('label'=>'Update Holiday', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Holiday', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Holiday', 'url'=>array('admin')),
);
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'date',
		'year',
		'name',
	),
)); ?>
