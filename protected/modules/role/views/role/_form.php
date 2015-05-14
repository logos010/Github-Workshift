<p class="form">

<?php echo CHtml::beginForm(); ?>

<?php echo Yum::requiredFieldNote(); ?>

<?php echo CHtml::errorSummary($model); ?>

<p class="nomb">
    <?php echo CHtml::activeLabelEx($model,'title', array('class' => 'form-label')); ?><br/>
    <?php echo CHtml::activeTextField($model,'title',array('size'=>40,'maxlength'=>20, 'class' => 'input-text')); ?>
    <?php echo CHtml::error($model,'title'); ?>
</p>

<p class="nomb">
    <?php echo CHtml::activeLabelEx($model,'description', array('class' => 'form-label')); ?><br/>
    <?php echo CHtml::activeTextArea($model,'description',array('rows'=>6, 'cols'=>50, 'class' => 'input-text')); ?>
    <?php echo CHtml::error($model,'description'); ?>
</p>

<?php if(Yum::hasModule('membership')) { ?>
    <p class="nomb">
        <?php echo CHtml::activeLabelEx($model,'membership_priority', array('class' => 'form-label')); ?>
        <?php echo CHtml::activeTextField($model, 'membership_priority', array('class' => 'input-text')); ?>
    <p class="hint">
        <?php echo Yum::t('Leave empty or set to 0 to disable membership for this role.'); ?>
        <?php echo Yum::t('Set to >0 to enable membership for this role and set a priority.'); ?>
        <?php echo Yum::t('Higher is usually more worthy. This is used to determine downgrade possibilities.'); ?>
    </p>
    </p>
    <p class="nomb">
        <?php echo CHtml::activeLabelEx($model,'price', array('class' => 'form-label')); ?><br/>
        <?php echo CHtml::activeTextField($model, 'price', array('class' => 'input-text')); ?>
        <?php echo CHtml::Error($model, 'price'); ?>
    </p>
    <p class="hint"> 
        <?php echo Yum::t('How expensive is a membership? Set to 0 to disable membership for this role'); ?>
    </p>

    <p class="nomb">
        <?php echo CHtml::activeLabelEx($model,'duration', array('class' => 'form-label')); ?><br/>
        <?php echo CHtml::activeTextField($model, 'duration', array('class' => 'input-text')); ?>
        <?php echo CHtml::Error($model, 'duration'); ?>
    </p>
    <p class="hint"> 
        <?php echo Yum::t('How many days will the membership be valid after payment?'); ?>
    </p>
    <p style="clear: both;"> </p>
<?php } ?>

<p class="nomb">
<?php echo CHtml::label(Yum::t('These users have been assigned to this role'), ''); ?> 

<?php 
$this->widget('YumModule.components.Relation', array(
			'model' => $model,
			'relation' => 'users',
			'style' => 'dropdownlist',
			'fields' => 'username',
			'htmlOptions' => array(
				'checkAll' => Yum::t('Choose All'),
				'template' => '<p style="float:left;margin-right:5px;">{input}</p>{label}'),
			'showAddButton' => false
			));  
?>
</p>

<p class="row buttons">
<?php echo CHtml::submitButton($model->isNewRecord 
		? Yum::t('Create role') 
		: Yum::t('Save role')); ?>
</p>

<?php echo CHtml::endForm(); ?>
</p><!-- form -->

