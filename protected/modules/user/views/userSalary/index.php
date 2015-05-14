<?php
$this->breadcrumbs=array(
	'User Salaries',
);

$this->menu=array(
	array('label'=>'Create UserSalary', 'url'=>array('create')),
	array('label'=>'Manage UserSalary', 'url'=>array('admin')),
);
?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
