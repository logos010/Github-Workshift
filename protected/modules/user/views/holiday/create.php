<?php
$this->breadcrumbs=array(
	'Holidays'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Holiday', 'url'=>array('index')),
	array('label'=>'Manage Holiday', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>