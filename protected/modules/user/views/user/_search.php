<?php
//    Yii::app()->clientScript->registerScript('searchCheckin', "
//        $('#searchForm form').live('submit', function(){        
//            $.fn.yiiGridView.update('".$grid_name."', {
//                data: $(this).serialize()
//            });
//            return false;
//        });
//    ");

    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $form = $this->beginWidget('CActiveForm', array(
        'action'=>Yii::app()->createUrl($this->route, array('id' => $id)),
        'method'=>'get',
        'id' => 'searchForm'
    ));
 ?>   

<?php if (ServiceUtil::getRole(true) == 1): 
        $id = isset($_GET['CheckinDairy']['user_id']) ? $_GET['CheckinDairy']['user_id'] : 0;
    ?>
    User: <?php echo $form->dropDownList($model, 'user_id', ServiceUtil::getUserList(), array('prompt' => 'NULL', 'options' => array(
        $id => array(
            'selected' => (isset($_GET['CheckinDairy']['user_id'])  && $_GET['CheckinDairy']['user_id'] == $id) ? true : false 
        ),        
    ))); ?>
<?php endif; ?>    
    Month: <select id="checkin_month" name="checkin_month">
        <script type="text/javascript">
            var months;            
            var currentMonth = <?php echo (isset($_GET['checkin_month'])) ? $_GET['checkin_month'] : CURRENT_MONTH; ?>;
            var selected = '';
            for (var i=1; i<=12; i++){        
                selected = (i==currentMonth) ? ' selected ' : '';
                months += "<option value="+i+selected+">"+i+"</option>";
            }
            document.write(months);
        </script>        
    </select>
    Year: <select id="checkin_year" name="checkin_year">
        <script type="text/javascript">
            var years;
            var currentYear = <?php echo (isset($_GET['checkin_year'])) ? $_GET['checkin_year'] : CURRENT_YEAR ?>;
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
    
    Holiday:<select id="holiday" name="holiday">
        <option value="all" <?php  echo (isset($_GET['holiday']) && $_GET['holiday'] == 'all') ? 'selected' : '' ?>>All</option>
        <option value="0" <?php  echo (isset($_GET['holiday']) && $_GET['holiday'] == '0') ? 'selected' : '' ?>>Normal</option>
        <option value="1" <?php  echo (isset($_GET['holiday']) && $_GET['holiday'] == '1') ? 'selected' : '' ?>>Weekend</option>
        <option value="2" <?php  echo (isset($_GET['holiday']) && $_GET['holiday'] == '2') ? 'selected' : '' ?>>P.Holiday</option>
        <option value="3" <?php  echo (isset($_GET['holiday']) && $_GET['holiday'] == '3') ? 'selected' : '' ?>>S.Holiday</option>
    </select>
    
    <?php echo CHtml::submitButton('Submit', array('class' => 'input-submit')); ?>
    <?php $this->endWidget();?>
