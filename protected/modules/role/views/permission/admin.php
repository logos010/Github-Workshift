<?

$this->breadcrumbs = array(
    Yum::t('Permissions') => array('index'),
    Yum::t('Manage'),
);
?>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'action-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'filter' => false,
            'name' => 'type',
            'value' => '$data->type',
        ),
        array(
            'filter' => false,
            'name' => 'principal',
            'value' => '$data->type == "user" ? $data->principal->username : @$data->principal_role->title'
        ),
        'Action.title',
        'Action.comment',
        array(
            'class' => 'CButtonColumn',
            'template' => '{delete}',
        ),
    ),
        )
);
?>
