<?php

$this->title = Yum::t('User checkin list');

$this->breadcrumbs = array(
	Yum::t('Users') => array('index'),
	Yum::t('Manage'));

echo Yum::renderFlash();
?>

<div id="search">
    <?php $this->renderPartial('_search', array(
        'model' => $model,
        'grid_name' => 'checkin_grid'
    )); ?>
</div>

<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'checkin_grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'pager'=>array(
            'maxButtonCount'=>'7',
        ),
        'columns' => array(   
            array(
                'name' => 'checkin_date',
                'value' => '$data->checkin_date'
            ),
            array(
                'name' => 'user_id',
                'value' => '$data->user->username'
            ),
            array(
                'name' => 'start_time',
                'value' => 'date("H:i:s", $data->start_time)'
            ),
            array(
                'name' => 'end_time',
                'value' => 'is_null($data->end_time) ? " - - - " : date("H:i:s", $data->end_time)'
            ),
            array(
                'name' => 'session',
                'filter' => CHtml::activeDropDownList($model, 'session', CHtml::listData(ServiceUtil::getWorkSessions(), 'session', 'session'), array('prompt' => 'All')),
            ),
        )
    ));
?>