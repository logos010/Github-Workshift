<?php

/**
 * Description of ServiceUtil
 *
 * @author HEO
 */
class ServiceUtil {

    public static function call($target, $param) {
        $tmp = explode('/', $target);
        if (count($tmp) != 3)
            throw new Exception("Invalid target {$target}. Please follow the pattern module.service.function");
        $module = $tmp[0];
        $class = $tmp[1];
        $function = $tmp[2];
        Yii::import("$module.services.$class", true);
        if (!class_exists($class))
            throw new Exception("{$class} service is not found while trying to call service {$target}");
        $service = new $class();
        if (!is_callable(array($service, $function)))
            throw new Exception("{$function} is not found in service {$class}");
        $service->init($module);
        $result = call_user_func(array($service, $function), $param);
        $result->target = $target;
        return $result;
    }

    public static function getWorkSessions() {
        $criteria = new CDbCriteria();
        $criteria->distinct = true;
        $criteria->select = 'session';

        $sessions = CheckinDairy::model()->findAll($criteria);
        return $sessions;
    }
    
    /**
     * Get time session to identify Morning or Afternoon login session
     */
    public static function getTimeSession() {   //if time is 13:00 PM still counted as monring session
        if (((int) date('H', time())) <= 12 && ((int) date('i', time())) <= 59) {
            return 'MORNING';
        } elseif (
                    ( ((int) date('H', time())) >= 13 && ((int) date('i', time())) >= 0 )
                    && ((int) date('H', time()) <= 18)
                ){   //if time is after 13:00 PM to 18:30 PM, count as afternoon session
            return 'AFTERNOON';
        }
    }

    public static function getTotalWorkDays() {
        $currentMonth = date('m', time());
        $currentYear = date('Y', time());

        $previousMonth = $currentMonth - 1;
        $currentWorkedDays = 25;
        $previousWorkedDays = self::getPreviousWorkedDayByMonth($currentMonth, $currentYear);

        echo $previousWorkedDays . "<br/>";
        echo $currentWorkedDays . "<br/>";
        echo $previousWorkedDays + $currentWorkedDays;

        $num = cal_days_in_month(CAL_GREGORIAN, $currentMonth, date('Y', time()));

        echo "<br/>There was $num days";
    }

    private static function getPreviousWorkedDayByMonth($currentMonth, $currentYear) {
        if ($currentMonth - 1 == 0) {
            $month = 12;    //apply for the month is Jan of new year, the previous month should be Dec and the year also be minus 1
            $year = $currentYear - 1;
        } else {
            $month = $currentMonth - 1;
            $year = $currentYear;
        }

        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        return $num - 25;
    }

    public static function workingHoursInMonth($id, $month, $year) {
        $criteria = new CDbCriteria();
        $criteria->select = 'str_to_date( t.checkin_date, "%d-%m-%Y" ) AS cdate, checkin_date, start_time, end_time';
        $criteria->condition = "user_id =:userID AND substr(checkin_date, 7,4)=:year AND str_to_date( t.checkin_date, '%d-%m-%Y' ) BETWEEN '".$year."-".($month-1)."-26' AND '".$year."-".($month)."-25'";
        $criteria->params = array(
            ':userID' => $id,
            ':year' => $year
        );        
        $criteria->order = 'cdate';
        
        $workTime = CheckinDairy::model()->findAll($criteria);
        $morningSession = 0;
        $afternoonSession = 0;
        $session = 1;   //1 for morning, 0 for afternoon
        $normalHours = 0;   $normalDay = 0;
        $weekendHours = 0;  $weekDay = 0;
        $holidayHours = 0;  $holidayDay = 0;
                
        foreach ($workTime as $key => $value) {
//            var_dump(strtotime(date('H:i:s', $value->end_time))."-".strtotime(date('H:i:s', $value->start_time)));            
            $total = strtotime(date('H:i:s', $value->end_time)) - strtotime(date('H:i:s', $value->start_time));            

            if (date('N', strtotime($value->checkin_date)) >= 6){   //6: Satureday, 7: Sunday
//                $weekendHours += (float) round(($total / 60 / 60), 2) + 4;
                $weekendHours += 4;
                $weekDay += 0.5;
            }
            elseif (date('N', strtotime($value->checkin_date)) >= 6 || self::isHoliday($value->checkin_date)){  //weekend and public holiday
//                $holidayHours += (float) round(($total / 60 / 60), 2) + 8;
                $holidayHours += 8;
                $holidayDay += 0.5;
            }
            else{
                $normalHours += (float) round(($total / 60 / 60), 2);
                $normalDay += 0.5;  
            }
        }
        return array(
            'holiday' => array('day' => $holidayDay, 'hour' => $holidayHours),
            'weekend' => array('day' => $weekDay, 'hour' => $weekendHours),
            'normal' => array('day' => $normalDay, 'hour' => $normalHours)
        );
    }
    
    public static function isHoliday($date){
        $date = strtotime($date);
        $d = date('d/m/Y', $date);
                
        $criteria = new CDbCriteria();
        $criteria->condition = 'CONCAT(date,"/",year)=:holiday';
        $criteria->params = array(
            ':holiday' => $d
        );
       
        $holiday = Holiday::model()->find($criteria);
        if ($holiday !== null)
            return true;
        else    return false;
    }
    
    /**
     * the first param is according the checkin date which is formated as dd-mm-yyyy
     */
//    public static function isHoliday($full_date){
//        echo $date = preg_replace('/\-/', '/', substr($full_date, 0, 4)) ;
//        echo $year = substr($full_date, 6, 4);
//        
//        $criteria = new CDbCriteria();
//        $criteria->condition = 'date=:date and year=:year';
//        $criteria->params = array(
//            ':date' => $date,
//            ':year' => $year
//        );
//        
//        $holiday = Holiday::model()->find($criteria);
//        if ($holiday !== null)
//            return true;
//        else    return false;
//    }

    /* draws a calendar */
    public static function draw_calendar($month, $year) {
        /* draw table */
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
//        $calendar .= date('F Y', strtotime());

        /* table headings */
        $headings = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">', $headings) . '</td></tr>';

        /* days and weeks vars now ... */
        $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
        $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();

        /* row for week one */
        $calendar.= '<tr class="calendar-row">';

        /* print "blank" days until the first of the current week */
        for ($x = 0; $x < $running_day; $x++):
            $calendar.= '<td class="calendar-day-np"> </td>';
            $days_in_this_week++;
        endfor;

        /* keep going with days.... */
        for ($list_day = 1; $list_day <= $days_in_month; $list_day++):
            $calendar.= '<td class="calendar-day">';

            /* weekend/holiday day colors */
            $dmy = $list_day . "-" . $month . "-" . $year;
            if (date('N', strtotime($dmy)) >= 6)
                $colorStyle = ' weekend';
            elseif (self::isHoliday($dmy))
                $colorStyle = ' holiday';
            else
                $colorStyle = '';

            /* add in the day number */
            $calendar.= '<div class="day-number' . $colorStyle . '">' . $list_day . '</div>';

            /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! * */
            $calendar.= str_repeat('<p> </p>', 2);

            $calendar.= '</td>';
            if ($running_day == 6):
                $calendar.= '</tr>';
                if (($day_counter + 1) != $days_in_month):
                    $calendar.= '<tr class="calendar-row">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
            endif;
            $days_in_this_week++;
            $running_day++;
            $day_counter++;
        endfor;

        /* finish the rest of the days in the week */
        if ($days_in_this_week < 8):
            for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar.= '<td class="calendar-day-np"> </td>';
            endfor;
        endif;

        /* final row */
        $calendar.= '</tr>';

        /* end the table */
        $calendar.= '</table>';

        /* all done, return result */
        return $calendar;
    }
    
    public static function getUserList($id=null){
        $criteria = new CDbCriteria();
        if ($id !== null){
            $criteria->condition = 'id=:id';
            $criteria->params = array(
                ':id' => $id,
            );
        }        
        $criteria->addCondition('id != 1');
                
        $users = YumUser::model()->findAll($criteria);
        return $listData = CHtml::listData($users, 'id', 'username');
    }
    
    public static function getRoleList($id=null){
        $criteria = new CDbCriteria();
        if ($id !== null){
            $criteria->condition = 'id=:id';
            $criteria->params = array(
                ':id' => $id,
            );
        }        
        $criteria->addCondition('id != 1');
                
        $roles = YumRole::model()->findAll($criteria);
        return $listData = CHtml::listData($roles, 'id', 'title');
    }
    
    public static function getHolidayName($holiday_type, $style=false){
        if ($holiday_type == 1){
            return $style ? '<span class="weekend">Weekend</span>' : 'Weekend';
        }else if($holiday_type == 2){
            return $style ? '<span class="holiday">P.Holiday</span>' : 'P.Holiday';
        }else if($holiday_type == 3){
            return $style ? '<span class="holiday">S.Holiday</span>' : 'S.Holiday';
        }else return 'Normal';
    }
    
    public static function getHolidays($date=null){        
        if ($year === NULL)
            $year = CURRENT_YEAR;
        
        $criteria = new CDbCriteria();
        if ($date !== null){
            $criteria->condition = 'date =:date';
            $criteria->params = array(
                ':date' => $date
            );
        }
        $criteria->addCondition('year='.$year);
                
        $holidays = Holiday::model()->findAll($criteria);
        if (is_array($holidays))
            return $holidays;
        else return null;
    }
    
    /**
     * return role id if TRUE is given in the param
     */
    public static function getRole($get_role_id){
        Yii::app()->cache->flush();
        $userModel = new YumUser();
        $user_id = $userModel->getID();
        
        $user = YumUser::model()->find(array(
            'condition' => 'id=:id',
            'params' => array(
                ':id' => $user_id,
            )
        ));
        
        if ($user !== null ){
            $role = UserRole::model()->find(array(
                'condition' => 'user_id=:id',
                'params' => array(
                    ':id' => $user->id
                )
            ));
            
            if ($get_role_id == false){  //return role name
                $role_name = $user->roles;
                
                foreach ($role_name as $k => $v){
                    return $v->title;
                }
            }return $role->role_id;            
        }
    }
    
    public static function getUserSalary($id){
        $criteria = new CDbCriteria();
        $criteria->condition = "user_id=:id and active = 1";
        $criteria->params = array(
            ':id' => $id,
        );
        $user_salary = Salary::model()->find($criteria);
        return (float)$user_salary->amount;
    }
    
    /*generate permission checkbox in create permission page*/
    public static function generatePermissionCheckBox($id, $type='user'){
        $id = isset($_POST['id']) ? $_POST['id'] : $id;        
        
        $criteria = new CDbCriteria();
        $criteria->condition = "t.id NOT IN (SELECT action from permission WHERE principal_id=:id AND type=:type) ";
        $criteria->params = array(
            ':id' => $id,
            ':type' => $type
        );
        $criteria->order = 'title DESC';
        
        $actions = YumAction::model()->findAll($criteria);
        $checkbox = null;        
        foreach ($actions as $key => $value){
            $checkbox .= CHtml::checkBox('YumPermission[action][]', false, array('value' => $value->id ,'id' => 'YumPermission_action_'.$value->id, 'class' => 'input-text'));
            $checkbox .= " ".CHtml::label($value->comment, 'YumPermission_action_'.$value->id);
            $checkbox .= '<br/>';
        }
        return $checkbox;
    }
    
    /*
     * Check user permission based on 'Permission' table
     */
    public static function userPermissionByActionName($action_name){
        $action = YumAction::model()->find(array(
            'condition' => 'title =:actionName',
            'params' => array(
                ':actionName' => $action_name,
            ),
        ));
        
        if ($action_name == 'user_login' || $action_name == 'user_logout')
            return true;
        else{
            if ($action === null)
                return false;
            else{            
                $permission = YumPermission::model()->find(array(
                    'condition' => 'action=:actionID && principal_id=:userID',
                    'params' => array(
                        ':actionID' => $action->id,
                        ':userID' => Yii::app()->user->getID()
                    )
                ));

                return ($permission === null) ? false : true;
            }    
        }
            
    }
    
    /**
     * Return current action name follow formatted: module_name/controller_name 
     */
    public static function getCurrentActionName($controllerObject){
        $module_name = $controllerObject->id;
        $cotroller_name = Yii::app()->controller->action->id;
        return $module_name."_".$cotroller_name;
    }
    
}
