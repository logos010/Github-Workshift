<?php
$this->breadcrumbs=array(
	'User Salaries'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List UserSalary', 'url'=>array('index')),
	array('label'=>'Create UserSalary', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-salary-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="search-form">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
        'grid_name' => 'user-salary-grid'
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-salary-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'user_id',
		'salary_index',
		'month',
		'year',
		'total',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
