<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
            ));
    ?>


    <p class="nomb">
        <?php echo $form->label($model, 'year', array('class' => 'form-label')); ?>        
            <select id="Holiday_year" name="Holiday[year]" class="input-text">
                <script type="text/javascript">
                    var years;
                    var currentYear = <?php echo CURRENT_YEAR ?>;
                    var b=<?php echo BEGIN_YEAR; ?>;
                    var c = <?php echo date('Y', time()); ?>;   //current year
                    var n = ((c-b==0) ? b+1: (c-b)+1+b);
                    var selected = '';
                    for(b; b<=n; b++){
                        selected = (b==currentYear) ? ' selected ' : '';
                        years += "<option value="+b+selected+">"+b+"</option>";
                    }
                    document.write(years);
                </script>    
            </select>
        <?php echo CHtml::submitButton('Search', array('class' => 'input-submit')); ?>
</p>

<?php $this->endWidget(); ?>

</div><!-- search-form -->