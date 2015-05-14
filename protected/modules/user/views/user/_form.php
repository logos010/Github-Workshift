<fieldset>
    <legend></legend>
    <p class="msg info">All fields highlighted <strong>*</strong> are required.</p>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false));
    ?>
    <div class="note">
        <?php echo Yum::requiredFieldNote(); ?>

        <?
        $models = array($model, $passwordform);
        if (isset($profile) && $profile !== false)
            $models[] = $profile;
        echo CHtml::errorSummary($models);
        ?>
    </div>
    
    <!-- Left column -->
    <div class="col50">
        <p class="nomt">
            <?php
                echo $form->labelEx($model, 'username', array('style' => 'font-weight: bold'));echo '<br/>';
                echo $form->textField($model, 'username', array('class' => 'input-text', 'size' => '40'));
                echo $form->error($model, 'username');
            ?>
        </p>
        
        <p class="nomt">
            <p> Leave password <em> empty </em> to 
                <?php                
                echo $model->isNewRecord ? 'generate a random Password' : 'keep it <em> unchanged </em>';
                ?> 
            </p>
            <?php $this->renderPartial('/user/passwordfields', array('form' => $passwordform)); ?>
        </p>
        
        <p class="nomt">
            <?php
                if (Yum::hasModule('profile'))
                    $this->renderPartial('application.modules.profile.views.profile._form', array(
                        'profile' => $profile));
            ?>
        </p>
    </div>
    
<?php 
    $isAdmin = ServiceUtil::getRole(true);
    if (!$isAdmin):
            $hide = 'hidden';
    else:
            $hide = '';
    endif;  
?>
    
    <!-- Right column -->    
    <div class="col50 f-right <?php echo $hide ?>">
        <?php 
            $disabled = ($isAdmin <= 2) ? 1 : 0;             
        ?>
        <p class="nomt">
            <?php
            echo $form->labelEx($model, 'superuser');
            echo $form->dropDownList($model, 'superuser', YumUser::itemAlias('AdminStatus'), array('class' => 'input-text'));
            echo $form->error($model, 'superuser');
            ?>
        </p>

        <p class="nomt">
            <?php
            echo $form->labelEx($model, 'status');
            echo $form->dropDownList($model, 'status', YumUser::itemAlias('UserStatus'), array('class' => 'input-text'));
            echo $form->error($model, 'status');
            ?>
        </p>

        <p class="nomt">
            <?php
                if (Yum::hasModule('role')) {
                    Yii::import('application.modules.role.models.*');
            ?>
            <p> <?php echo Yum::t('User belongs to these roles'); ?> </p>
            <?php
                $this->widget('YumModule.components.Relation', array(
                    'model' => $model,
                    'relation' => 'roles',
                    'style' => 'dropdownlist',
                    'fields' => 'title',
                    'showAddButton' => false,
                    'htmlOptions' => array(
                        'class' => 'input-text'
                    )
                ));
                ?>
            <?php } ?>
        </p>
        
        <div class="row buttons">
            <?php
            echo CHtml::submitButton($model->isNewRecord ? Yum::t('Create') : Yum::t('Save'), array('class' => 'input-submit'));
            ?>
        </div>
        
    </div> <!-- /col50 -->

    <?php $this->endWidget(); ?>
</fieldset>
<div style="clear:both;"></div>

<?php
Yii::app()->clientScript->registerScript("admin_only", "
    var is_admin = ".$disabled.";
    if (!is_admin){
        $('#YumUser_superuser,#YumUser_status,#YumUser_YumRole').attr('disabled', 'disabled');
    }
");
