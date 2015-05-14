<p class="nomt">
    <?php echo CHtml::activeLabelEx($form,'password', array('style' => 'font-weight: bold')); ?><br/> 
    <?php echo CHtml::activePasswordField($form,'password',array('size' => '40', 'class' => 'input-text')); ?>
</p>

<p class="nomt">
    <?php echo CHtml::activeLabelEx($form,'verifyPassword', array('style' => 'font-weight: bold')); ?><br/>
    <?php echo CHtml::activePasswordField($form,'verifyPassword', array('size' => '40', 'class' => 'input-text')); ?>    
</p>

