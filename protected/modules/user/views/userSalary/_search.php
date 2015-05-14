<?php
    Yii::app()->clientScript->registerScript('searchCheckin', "
        $('#searchForm form').live('submit', function(){        
            $.fn.yiiGridView.update('".$grid_name."', {
                data: $(this).serialize()
            });
            return false;
        });
    ");
    

    $form = $this->beginWidget('CActiveForm', array(
        'action'=>Yii::app()->createUrl($this->route, array('id' => $_GET['id'])),
        'method'=>'get',
        'id' => 'searchForm'
    ));    
 ?>   

<?php if (ServiceUtil::getRole(true) == 1): 
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
    ?>
    User: <?php echo $form->dropDownList($model, 'user_id', ServiceUtil::getUserList(), array('prompt' => 'All' ,'options' => array(
        $id => array(
            'selected' => true
        ),
    ))); ?>
<?php endif; ?>    
    Month: <select id="checkin_month" name="UserSalary[month]">
        <script type="text/javascript">
            var months;
            var currentMonth = <?php echo CURRENT_MONTH; ?>;
            var selected = '';
            for (var i=1; i<=12; i++){        
                selected = (i==currentMonth) ? ' selected ' : '';
                months += "<option value="+i+selected+">"+i+"</option>";
            }
            document.write(months);
        </script>        
    </select>
    Year: <select id="checkin_year" name="UserSalary[year]">
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
    
        <?php echo CHtml::submitButton('Submit'); ?>
<?php $this->endWidget();?>
