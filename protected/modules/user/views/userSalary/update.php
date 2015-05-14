<?php
$this->breadcrumbs=array(
	'User Salaries'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UserSalary', 'url'=>array('index')),
	array('label'=>'Create UserSalary', 'url'=>array('create')),
	array('label'=>'View UserSalary', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UserSalary', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>