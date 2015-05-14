<?php
$this->breadcrumbs=array(
	'Salaries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Salary', 'url'=>array('index')),
	array('label'=>'Manage Salary', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>