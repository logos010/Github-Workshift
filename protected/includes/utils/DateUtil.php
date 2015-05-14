<?php

/**
 * Description of DateUtil
 *
 * @author HEO
 */
class DateUtil {

    public static function getMonths() {
        $months = array();
        for ($i = 1; $i <= 12; $i++)
            $months[$i] = strftime('%B', mktime(0, 0, 0, $i, 1));
        return $months;
    }
    
    public static function getDays() {
        $days = array();
        for ($i = 1; $i <= 31; $i++)
            $days[$i] = strftime('%d', mktime(0, 0, 0, $month, $i));
        return $days;
    }
    
    public static function getYears($limit = 100) {
        $years = array();
        $current = date('Y');
        $stop = $current - $limit;
        for ($i = $current; $i >= $stop; $i--)
            $years[$i] = $i;
        return $years;
    }
    
    public static function convertDateTime($datetime, $type='ymd', $separate="/"){
        if ($type == 'ymd'){
            return substr($datetime, 6, 4).$separate.substr($datetime, 3, 2).$separate.substr($datetime, 0, 2);
        }
    }

}
