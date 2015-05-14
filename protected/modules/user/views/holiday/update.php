<?php
$this->breadcrumbs=array(
	'Holidays'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Holiday', 'url'=>array('index')),
	array('label'=>'Create Holiday', 'url'=>array('create')),
	array('label'=>'View Holiday', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Holiday', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>