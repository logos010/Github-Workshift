
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->baseUrl ?>/css/reset.css" /> <!-- RESET -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->baseUrl ?>/css/admin_main.css" /> <!-- MAIN STYLE SHEET -->
	<!--[if lte IE 6]><link rel="stylesheet" media="screen,projection" type="text/css" href="css/main-ie6.css" /><![endif]--> <!-- MSIE6 -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->baseUrl ?>/css/style.css" /> <!-- GRAPHIC THEME -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo Yii::app()->baseUrl ?>/css/mystyle.css" /> <!-- WRITE YOUR CSS CODE HERE -->
    </head>
    <body id="login">        
        <div id="main-02">
            <div id="login-top"></div>

            <div id="login-box">
                <h2>Login</h2>
                <?php                
                    if (!isset($model))
                        $model = new YumUserLogin();

                    $module = Yum::module();

                    $this->pageTitle = Yum::t('Login');
                    if (isset($this->title))
                        $this->title = Yum::t('Login');
                    $this->breadcrumbs = array(Yum::t('Login'));                    
                    
                    Yum::renderFlash();
                ?>
                
                <div class="form">
                    <p><?php echo Yum::t('Please fill out the following form with your login credentials:'); ?></p>

                    <?php echo CHtml::beginForm(array('//user/auth/login')); ?>

                    <?
                    if (isset($_GET['action']))
                        echo CHtml::hiddenField('returnUrl', urldecode($_GET['action']));
                    ?>

                    <?php echo CHtml::errorSummary($model); ?>

                    <div class="row">
                        <?php
                        if ($module->loginType & UserModule::LOGIN_BY_USERNAME
                                || $module->loginType & UserModule::LOGIN_BY_LDAP)
                            echo CHtml::activeLabelEx($model, 'username', array('style' => 'font-weight:bold; margin-right:23px'));
                        if ($module->loginType & UserModule::LOGIN_BY_EMAIL)
                            printf('<label for="YumUserLogin_username">%s <span class="required">*</span></label>', Yum::t('E-Mail address'));
                        if ($module->loginType & UserModule::LOGIN_BY_OPENID)
                            printf('<label for="YumUserLogin_username">%s <span class="required">*</span></label>', Yum::t('OpenID username'));
                        ?>

                        <?php echo CHtml::activeTextField($model, 'username', array('class' => 'input-text', 'size' => '45')) ?>
                    </div>
                    <!-- end username field -->
                    <div class="clear"></div>
                    <!-- Password Field -->
                    <div class="row">
                        <?php echo CHtml::activeLabelEx($model, 'password', array('style' => 'font-weight:bold')); ?>
                        <?php
                        echo CHtml::activePasswordField($model, 'password', array('class' => 'input-text', 'size' => '45'));
                        if ($module->loginType & UserModule::LOGIN_BY_OPENID)
                            echo '<br />' . Yum::t('When logging in with OpenID, password can be omitted');
                        ?>
                    </div>
                    <!--  end password field -->

                    <div class="clear">                      
                    </div>

                    <div class="row rememberMe">
                        <?php echo CHtml::activeCheckBox($model, 'rememberMe', array('style' => 'display: inline;')); ?>
                        <?php echo CHtml::activeLabelEx($model, 'rememberMe', array('style' => 'display: inline;')); ?>
                    </div>

                    <div class="row submit">
                        <?php echo CHtml::submitButton(Yum::t('Login'), array('class' => 'input-submit')); ?>
                    </div>
                    <?php echo CHtml::endForm(); ?>
                </div>



            </div> <!-- /login-box -->

            <div id="login-bottom"></div>

        </div> <!-- end main-->
        <?
        $form = new CForm(array(
                    'elements' => array(
                        'username' => array(
                            'type' => 'text',
                            'maxlength' => 32,
                        ),
                        'password' => array(
                            'type' => 'password',
                            'maxlength' => 32,
                        ),
                        'rememberMe' => array(
                            'type' => 'checkbox',
                        )
                    ),
                    'buttons' => array(
                        'login' => array(
                            'type' => 'submit',
                            'label' => 'Login',
                        ),
                    ),
                        ), $model);
        ?>


    </body>
</html>

