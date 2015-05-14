<?php
$this->breadcrumbs=array(
	'Salaries'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Salary', 'url'=>array('index')),
	array('label'=>'Create Salary', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('salary-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'salary-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'user_id' => array(
                    'header' => 'User',
                    'value' => '$data->users->username'
                ),
		'create_time' => array(
                    'name' => 'create_time',
                    'value' => 'date("d-m-Y" ,$data->create_time)'
                ),
		'amount' => array(
                    'name' => 'amount',
                    'value' => 'number_format($data->amount, 0, " ", " ")',
                ),
		'active' => array(
                    'name' => 'active',
                    'value' => '($data->active) ? "Yes" : "No"',
                    'filter' => array(
                        '1' => 'Yes',
                        '0' => 'No'                    )
                    
                ),
		array(
                    'class'=>'CButtonColumn',
                    'template' => '{update}'
		),
	),
)); ?>
