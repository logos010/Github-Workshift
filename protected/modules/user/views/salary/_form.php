<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salary-form',
	'enableAjaxValidation'=>false,
)); 
    Yii::app()->clientScript->registerCoreScript('jquery');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl."/js/jquery.number_format.js", CClientScript::POS_END);
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<p class="nomb">                
		<?php echo $form->labelEx($model,'user_id', array('style' => 'font-weight: bold')); ?>		
                <?php echo $form->dropDownList($model, 'user_id', ServiceUtil::getUserList(), array('class' => 'input-text', 'maxlength'=>40));  ?>
		<?php echo $form->error($model,'user_id'); ?>
	</p>

	<p class="nomb">
		<?php echo $form->labelEx($model,'amount', array('style' => 'font-weight: bold')); ?>
		<?php 
                    echo $form->textField($model,'amount', array(
                        'onkeyup' => '
                                var format_number = $().number_format($(this).val().replace(/\s/g, ""), {
                                    numberOfDecimals:0,
                                    decimalSeparator: "," ,
                                    thousandSeparator: " "
                                });
                                $(this).val(format_number);
                            ',
                    )); 
                ?>
            
           
		<?php echo $form->error($model,'amount', array('style' => 'font-weight: bold')); ?>
	</p>

	<p class="nomb">
		<?php echo $form->labelEx($model,'active', array('style' => 'font-weight: bold')); ?>
		<?php echo $form->checkbox($model,'active', array('checked' => true, 'class' => 'input-text')); ?>
		<?php echo $form->error($model,'active'); ?>
	</p>

	<p class="nomb">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'input-submit')); ?>
	</p>

<?php $this->endWidget(); ?>

</div><!-- form -->