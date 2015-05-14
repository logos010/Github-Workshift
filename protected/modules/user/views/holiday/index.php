<?php
$this->breadcrumbs = array(
    'Holidays',
);

$this->menu = array(
    array('label' => 'Create Holiday', 'url' => array('create')),
    array('label' => 'Manage Holiday', 'url' => array('admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('holiday_grid', {
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

<div>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'holiday_grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'pager' => array(
            'maxButtonCount' => '7',
        ),
        'columns' => array(
            'date' => array(
                'name' => 'date',
                'filter' => false
            ),
            'year' => array(
                'name' => 'year',
                'filter' => false
            ),
            'name' => array(
                'name' => 'name',
                'filter' => false
            ),
            array(
                'class' => 'CButtonColumn',
                'visible' => 0
            )
        ),
    ));
    ?>
</div>
