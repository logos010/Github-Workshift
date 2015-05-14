
<?php
//echo '<pre>';print_r(YumUser::getRoles()); echo '</pre>';
$this->title = Yum::t('Manage roles'); 

$this->breadcrumbs=array(
	Yum::t('Roles')=>array('index'),
	Yum::t('Manage'),
);

?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		array(
			'name' => 'title',
			'type' => 'raw',
			'value'=> 'CHtml::link(CHtml::encode($data->title),
				array("//role/role/view","id"=>$data->id))',
		),		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
