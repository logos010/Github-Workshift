<?php
$this->title = Yum::t('Manage users');

$this->breadcrumbs = array(
	Yum::t('Users') => array('index'),
	Yum::t('Manage'));

echo Yum::renderFlash();

$this->widget('application.modules.user.components.CsvGridView', array(
	'dataProvider'=>$model->search(),
	'filter' => $model,
		'columns'=>array(
			array(
				'name'=>'id',
				'filter' => false,
				'type'=>'raw',
				'value'=>'CHtml::link($data->id,
				array("//user/user/update","id"=>$data->id))',
				),
			array(
				'name'=>'username',
				'type'=>'raw',
				'value'=>'CHtml::link(CHtml::encode($data->username),
				array("//user/user/view","id"=>$data->id))',
			),
			array(
				'name'=>'createtime',
				'filter' => false,
				'value'=>'date(UserModule::$dateFormat,$data->createtime)',
			),
			array(
				'name'=>'lastvisit',
				'filter' => false,
                                'value' => '($data->lastvisit == 0) ? "No Data" : date(UserModule::$dateFormat,$data->lastvisit)'
			),
			array(
				'name'=>'status',
				'filter' => false,
				'value'=>'YumUser::itemAlias("UserStatus",$data->status)',
			),
//			array(
//				'name'=>Yum::t('Roles'),
//				'type' => 'raw',
//				'visible' => Yum::hasModule('role'),
//				'filter' => false,
//				'value'=>'$data->getRoles()',
//			),
			array(
				'class'=>'CButtonColumn',
                                'template' => '{update}{worklist}{delete}',
                                'buttons' => array(
                                    'worklist' => array(
                                        'lable' => 'Worklist',
                                        'imageUrl' => Yii::app()->baseUrl."/images/work-list.ico",
                                        'url' => 'Yii::app()->controller->createUrl("checkinList", array("id"=>$data->id))',
                                        'options' => array(
                                            'target' => '_blank'
                                        ),
                                    )
                                ),
			),
))); ?>

<?php echo CHtml::link(Yum::t('Create new User'), array(
			'//user/user/create')); ?>

