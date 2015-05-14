<?php
$this->breadcrumbs=array(
	'Holidays'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Holiday', 'url'=>array('index')),
	array('label'=>'Create Holiday', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('holiday-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="search-form">
    <?php $this->renderPartial('_search', array(
        'model' => $model
    )) ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'holiday-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'date',
		'year',
		'name',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
