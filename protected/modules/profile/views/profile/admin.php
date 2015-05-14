<?

$this->breadcrumbs = array(
    'States' => array(Yii::t('app', 'index')),
    Yii::t('app', 'Manage'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('states-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php
Yum::renderFlash();
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'profiles-grid',
    'dataProvider' => $dataProvider,
    'filter' => $model,
    'columns' => array(
        'id',
        'firstname',
        'lastname',
        'email',
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}',
            'buttons' => array(
                'update' => array(
                    'url' => 'Yii::app()->createUrl("profile/profile/update/", array("id" => $data->user_id))'
                )
            )
        ),
    ),
));
?>


