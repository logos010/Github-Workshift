<div class="form">
    
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'holiday-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

        <p class="nomb">
            <?php echo $form->labelEx($model,'date', array('style' => 'font-weight: bold')); ?>            
            <br/>
            <?php echo $form->textField($model,'date', array('size'=>45,'maxlength'=>40, 'class' => 'input-text')); ?><br/>
            <span class="smaller low">Format as dd/mm. Ex: 30/04, 08/03</span><br/>
            <?php echo $form->error($model,'date'); ?>
        </p>
	
        <p class="nomb">
            <?php echo $form->labelEx($model,'year', array('style' => 'font-weight: bold')); ?>
            <br/>
            <?php echo $form->textField($model,'year',array('size'=>45,'maxlength'=>4, 'class' => 'input-text')); ?><br/>            
            <span class="smaller low">Format as yyyy. Ex: 2013</span><br/>
            <?php echo $form->error($model,'year'); ?>
        </p>

	<p class="nomb">
		<?php echo $form->labelEx($model,'name', array('style' => 'font-weight: bold')); ?><br/>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255,'class' => 'input-text')); ?>
		<?php echo $form->error($model,'name'); ?>
	</p>

	<p class="nomb">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'input-submit')); ?>
	</p>

<?php $this->endWidget(); ?>

</div><!-- form -->