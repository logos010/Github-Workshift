<style>
    /* calendar */
table.calendar		{ border-left:1px solid #999; }
tr.calendar-row	{  }
td.calendar-day	{ min-height:80px; font-size:11px; position:relative; } * html div.calendar-day { height:80px; }
td.calendar-day:hover	{ background:#eceff5; }
td.calendar-day-np	{ background:#eee; min-height:80px; } * html div.calendar-day-np { height:80px; }
td.calendar-day-head { background:#ccc; font-weight:bold; text-align:center; width:120px; padding:5px; border-bottom:1px solid #999; border-top:1px solid #999; border-right:1px solid #999; }
div.day-number		{ background:#999; padding:5px; color:#fff; font-weight:bold; float:right; margin:-5px -5px 0 0; width:20px; text-align:center; }
div.weekend {background: red}
div.holiday {background: darkred}
/* shared */
td.calendar-day, td.calendar-day-np { width:120px; padding:5px; border-bottom:1px solid #999; border-right:1px solid #999; }
</style>

<div>
    <?php   $id = (isset($_GET['id'])) ? $_GET['id'] : 0;
        $month = (isset($_GET['month'])) ? $_GET['month'] : CURRENT_MONTH;
        $year = (isset($_GET['year'])) ? $_GET['year'] : CURRENT_YEAR; ?>
    
    User: <?php echo CHtml::dropDownList('uid', '', ServiceUtil::getUserList(), array('prompt' => 'NULL', 'options' => array(
        $id => array(
            'selected' => true
        ),        
    ))); ?>
    Month: <select id="checkin_month" name="checkin_month">
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
    Year: <select id="checkin_year" name="checkin_year">
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
</div>

<div class="clear"></div>

    <?php
    echo date('F Y',mktime(0, 0, 0, $month, 1, $year));
    //print calendar table
    echo ServiceUtil::draw_calendar($month, $year);
    $month = (isset($_GET['month'])) ? $_GET['month'] : CURRENT_MONTH;    
    $workTime =  ServiceUtil::workingHoursInMonth((int)$_GET['id'], (int)$_GET['month'], (int)$_GET['year']); 
    
    ?>
    <div class="clear"></div>
    <table>
        <tr>
            <td>Type</td>
            <td>Days</td>
            <td>Working Hours</td>
            <td>Salary</td>
        </tr>
        <tr>
            <td>Normal</td>
            <td><?php echo $workTime['normal']['day']; ?></td>
            <td><?php echo $workTime['normal']['hour']; ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Weekend</td>
            <td><?php echo $workTime['weekend']['day']; ?></td>
            <td><?php echo $workTime['weekend']['hour']; ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Holiday</td>
            <td><?php echo $workTime['holiday']['day']; ?></td>
            <td><?php echo $workTime['holiday']['hour']; ?></td>
            <td></td>
        </tr>
    </table>