<?php 

$this->breadcrumbs = array(
	Yum::t("Change password"));

if(isset($expired) && $expired)
	$this->renderPartial('password_expired');
?>

<div class="form">
<?php echo CHtml::beginForm(); ?>
	<?php echo Yum::requiredFieldNote(); ?>
	<?php echo CHtml::errorSummary($form); ?>

	<?php if(!Yii::app()->user->isGuest) {
		echo '<p class="nomt">';
		echo CHtml::activeLabelEx($form,'currentPassword', array('style' => 'font-weight: bold')); echo '<br/>';
		echo CHtml::activePasswordField($form,'currentPassword', array('size' => 45, 'class' => 'input-text')); 
		echo '</p>';
	} ?>

<?php $this->renderPartial(
		'application.modules.user.views.user.passwordfields', array(
			'form'=>$form)); ?>

	<div class="row submit">
	<?php echo CHtml::submitButton(Yum::t("Save"), array('class' => 'input-submit')); ?>
	</div>

<?php echo CHtml::endForm(); ?>
</div><!-- form -->
