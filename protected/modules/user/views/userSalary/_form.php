<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-salary-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<p class="nomb">
		<?php echo $form->labelEx($model,'user_id', array('style' => 'font-weight: bold')); ?><br/>
		<?php echo $form->textField($model,'user_id'); ?><br/>
		<?php echo $form->error($model,'user_id'); ?>
	</p>

	<p class="nomb">
		<?php echo $form->labelEx($model,'salary_index', array('style' => 'font-weight: bold')); ?><br/>
		<?php echo $form->textField($model,'salary_index', array('size'=>45,'maxlength'=>40, 'class' => 'input-text')); ?><br/>
		<?php echo $form->error($model,'salary_index'); ?>
	</p>

	<p class="nomb">
		<?php echo $form->labelEx($model,'month', array('style' => 'font-weight: bold')); ?><br/>
		<?php echo $form->textField($model,'month', array('size'=>45,'maxlength'=>40, 'class' => 'input-text')); ?><br/>
		<?php echo $form->error($model,'month'); ?>
	</p>

	<p class="nomb">
		<?php echo $form->labelEx($model,'year', array('style' => 'font-weight: bold')); ?><br/>
		<?php echo $form->textField($model,'year', array('size'=>45,'maxlength'=>40, 'class' => 'input-text')); ?><br/>
		<?php echo $form->error($model,'year'); ?>
	</p>

	<p class="nomb">
		<?php echo $form->labelEx($model,'total', array('style' => 'font-weight: bold')); ?><br/>
		<?php echo $form->textField($model,'total', array('size'=>45,'maxlength'=>40, 'class' => 'input-text')); ?><br/>
		<?php echo $form->error($model,'total'); ?>
	</p>

	<p class="nomb">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'input-submit')); ?>
	</p>

<?php $this->endWidget(); ?>

</div><!-- form -->