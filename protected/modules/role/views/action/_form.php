<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'action-form',
	'enableAjaxValidation'=>false,
)); 

echo Yum::requiredFieldNote();
?>

	<?php echo $form->errorSummary($model); ?>

	<p class="nomt">
		<?php echo $form->labelEx($model,'title', array('style' => 'font-weight:bold')); ?><br/>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>255, 'class' => 'input-text')); ?>
		<?php echo $form->error($model,'title'); ?>
	</p>

	<p class="nomt">
		<?php echo $form->labelEx($model,'comment', array('style' => 'font-weight:bold')); ?><br/>
		<?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50, 'class' => 'input-text')); ?>
		<?php echo $form->error($model,'comment'); ?>
	</p>

	<p class="nomt">
		<?php echo $form->labelEx($model,'subject', array('style' => 'font-weight:bold')); ?><br/>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>255, 'class' => 'input-text')); ?>
		<?php echo $form->error($model,'subject'); ?>
	</p>

	<p class="nomt">
	<?php echo CHtml::submitButton($model->isNewRecord 
			? Yum::t('Create') 
			: Yum::t('Save'), array('class' => 'input-submit')); ?>
	</p>

<?php $this->endWidget(); ?>

</div><!-- form -->
