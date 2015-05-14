<?php
$this->breadcrumbs=array(
	'User Salaries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UserSalary', 'url'=>array('index')),
	array('label'=>'Manage UserSalary', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>