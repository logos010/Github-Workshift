<?
$this->breadcrumbs=array(
	'Actions',
);

$this->menu=array(
	array('label'=>'Create Action', 'url'=>array('create')),
	array('label'=>'Manage Action', 'url'=>array('admin')),
);
?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
