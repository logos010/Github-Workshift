<p class="form">
    <?php    
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'permission-create-form',
        'enableAjaxValidation' => true,
            ));
    ?>

<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<p class="nomb">
    <label class="form-label"> <?php echo Yum::t('Grant this permission to a user'); ?> </label>
    <?php
    echo $form->radioButtonList($model, 'type', array(
        'user' => Yum::t('User'),
//        'role' => Yum::t('Role')), array('template' => '<div class="checkbox">{input}</div>{label}'
        ));
    ?>
<?php echo $form->error($model, 'type'); ?>
</p>

<div id="assignment_user">
    <p class="nomb">
        <?php echo $form->labelEx($model, 'principal_id', array('class' => 'form-label')); ?><br/>
        <?php echo $form->dropDownList($model, 'principal_id', ServiceUtil::getUserList(), array('class' => 'input-text, permission_user')); ?>
        <?php echo $form->error($model, 'principal_id'); ?>
    </p>   
</div>

<div id="assignment_role" style="display: none;">
<p class="nomb">
    <?php echo $form->labelEx($model, 'principal_id', array('class' => 'form-label')); ?><br/>
    <?php echo $form->dropDownList($model, 'principal_id', ServiceUtil::getRoleList(), array('disabled' => 'disabled', 'class' => 'input-text, permission_role', 'id' => 'principal_role')); ?>
    <?php echo $form->error($model, 'principal_id'); ?>
</p>
</div>

<p class="nomb">    
<?php echo $form->labelEx($model, 'action', array('class' => 'form-label')); ?><br/>
<a href="javascript:void(0)" name="action_checkboxcolumn" id="action_checkboxcolumn" value="1"><?php echo Yum::t('Check All') ?></a><br/>
<img src='<?php echo Yii::app()->baseUrl."/images/loading.gif"; ?>' class="loading"/>
<div style="line-height: 25px;" class="action_checkbox_columns">    
    <?php echo ServiceUtil::generatePermissionCheckBox(Yii::app()->user->id);?>
</div>
<?php echo $form->error($model, 'action'); ?>
</p>

<p class="nomb">
<?php echo $form->labelEx($model, 'comment', array('class' => 'form-label')); ?><br/>
<?php echo $form->textArea($model, 'comment', array('class' => 'input-text')); ?>
<?php echo $form->error($model, 'comment'); ?>
</p>

<p class="nomb buttons">
<?php echo CHtml::submitButton('Submit', array('class' => 'input-submit')); ?>
</p>

<?php $this->endWidget(); ?>

</p><!-- form -->

<?php
Yii::app()->clientScript->registerScript('checkall_action', "
    $().ready(function(){
        //disable user group temporary
        $('#YumPermission_type_1').hide();
        permission_type = ''; 
        
        $('#YumPermission_type_0').click(function() {
        permission_type = 'user';
        $('#assignment_role').hide();
        $('.permission_role').attr('disabled', 'disabled');
        $('.permission_user').removeAttr('disabled');
        $('#assignment_user').show();});

        $('#YumPermission_type_1').click(function() {
        permission_type = 'role';
        $('#assignment_role').show();
        $('.permission_user').attr('disabled', 'disabled');
        $('.permission_role').removeAttr('disabled');
        $('#assignment_user').hide();});
        
        $('#action_checkboxcolumn').live('click', function(){
            if ($(this).attr('value') == 1){
                $('input[type=checkbox]').attr('checked', true);
                $(this).attr('value', 0);
                $(this).text('Uncheck All');
            }else if ($(this).attr('value') == 0){
                $('input[type=checkbox]').attr('checked', false);
                $(this).attr('value', 1);
                $(this).text('Check All')
            }
        });
        
        //event for printcipal change
        $('#YumPermission_principal_id, #principal_role').live('change', function(){
            var permission_url = '".Yii::app()->createUrl('role/permission/getPermissionByUser/id/')."';
            var id = $(this).val();
            var type = 'user';
            $.ajax({
                url: permission_url+'/'+id+'/type/'+permission_type,
                type: 'POST',
                beforeSend: function(){
                    $('img.loading').show();
                },
                success: function (data){
                    $('.action_checkbox_columns').html(data);
                },
                complete: function(){
                    $('img.loading').hide();
                }
            });
        });
    });
");



