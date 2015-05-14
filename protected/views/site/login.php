<?php $this->widget('ext.blueprintgrid.JBlueprintGrid'); ?>
<?php
$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
    'Login',
);
?>
ihihihihi
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
<link href="<?php echo Yii::app()->baseUrl ?>/css/login-box.css" rel="stylesheet" type="text/css" />

<div class='container'>    
    <div class="span-6 last prepend-9 append-9">
        <div id="login-box">
            <H2>Login</H2>    
            <br />
            <br />
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                    ));
            ?>
            <div id="login-box-name" style="margin-top:20px;">Username:</div>
            <div id="login-box-field" style="margin-top:20px;">
                <?php echo $form->textField($model, 'username', array('class' => 'form-login')); ?>
                <?php echo $form->error($model, 'username'); ?>
            </div>

            <div id="login-box-name">Password:</div>
            <div id="login-box-field">
                <?php echo $form->passwordField($model, 'password', array('class' => 'form-login')); ?>
                <?php echo $form->error($model, 'password'); ?>
            </div>
            <br />

            <span class="login-box-options"><input type="checkbox" name="1" value="1"> Remember Me <a href="#" style="margin-left:30px;">Forgot password?</a></span>
            <br />
            <br />
            <a href="#"><img src="images/login-btn.png" width="103" height="42" style="margin-left:90px;" /></a>           
            <?php $this->endWidget(); ?>
        </div> <!--end form -->
    </div>
</div>