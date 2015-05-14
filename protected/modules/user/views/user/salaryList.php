<div id="search">
    <?php
        $id = (isset($_GET['id'])) ? $_GET['id'] : 0;
        $month = (isset($_GET['month'])) ? $_GET['month'] : CURRENT_MONTH;
        $year = (isset($_GET['year'])) ? $_GET['year'] : CURRENT_YEAR;
        
        Yii::app()->clientScript->registerScript('searchUserSalary', "
                    $('#search form').live('submit', function(){
                        $.fn.yiiGridView.update('salaryList_grid', {                        
                            data: $(this).serialize()
                        });
                });
        ");
        
        $form = $this->beginWidget('CActiveForm', array(
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'get',
        'htmlOptions' => array(),
        'id' => 'searchForm'
    )); ?>
    
    <?php if (ServiceUtil::getRole(true) == 1): 
        $id = isset($_GET['uid']) ? $_GET['uid'] : 0;
    ?>
    
    User: <?php echo CHtml::dropDownList('uid', '', ServiceUtil::getUserList(), array('prompt' => 'NULL', 'options' => array(
        $id => array(
            'selected' => (isset($_GET['uid'])  && $_GET['uid'] == $id) ? true : false 
        ),        
    ))); ?>
    
    <?php endif; ?>
    
    Month: <select id="month" name="month">
        <script type="text/javascript">
            var months;
            var currentMonth = <?php echo $month; ?>;
            var selected = '';
            for (var i=1; i<=12; i++){        
                selected = (i==currentMonth) ? ' selected ' : '';
                months += "<option value="+i+selected+">"+i+"</option>";
            }
            document.write(months);
        </script>
        </select>
    Year: <select id="year" name="year">
        <script type="text/javascript">
            var years;
            var currentYear = <?php echo $year; ?>;
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
    <?php echo CHtml::link('Export', Yii::app()->controller->createUrl("exportSalary"), array(
        'onClick' => 'return exportSalary()',
        'id' => 'exportExcel'
    )) ?>
    
    <?php $this->endWidget(); ?>
</div>

<?php

    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'salaryList_grid',
        'dataProvider' => $dataProvider,        
        'pager'=>array(
            'maxButtonCount'=>'7',
        ),        
        'columns' => array(            
            'checkin_date',            
            array(
                'header' => 'Total time (hours)',
                'value' => 'sprintf("%.1f", round((float)$data->sum_time/3600, 2))',
            ),
            'holiday_type' => array(
                'type' => 'raw',
                'name' => 'holiday_type',
                'value' => 'ServiceUtil::getHolidayName($data->holiday_type, true)'
            )
        ),
    ));
?>
<!-- Workers total work days and hours -->

<div style="display: none" id="salary">logos010</div>
<script type="text/javascript">
    function exportSalary(){
        if ($("#uid").val() === ''){
            alert ('User did not identify');
            return false;
        }
        
        link = $("#exportExcel").attr('href');
        
        //set new link to export excel        
        $("#exportExcel").attr('href', link+"?uid="+$("#uid").val()+"&month="+$("#month").val()+"&year="+$("#year").val()); 
    }
    
    $().ready(function(){
        if ($('#uid').val() === ''){
            return 0;
        }else{
            $.ajax({
              url : '<?php echo Yii::app()->createUrl('user/userSalary/CalculateSalary') ?>',
              type: 'get',
              data: 'id='+$("#uid").val()+'&month='+$("#month").val()+'&year='+$("#year").val(),
              success: function(data){
                  $("#salary").html(data);
                  $("#salary").show();
              }
          });  
        }
    });
</script>
