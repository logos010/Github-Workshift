<?php

class SiteController extends ControllerBase {

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $users = $this->getCurrentUsers();
        $end_time = '';

        $session = ServiceUtil::getTimeSession();        
        if ($session == 'MORNING')
            $end_time = time();   // auto make MORNING end time at 12:30:00 AM
        elseif ($session == 'AFTERNOON')
            $end_time = time();   // auto make AFTERNOON end time at 18:30:00 PM

        if (!is_null($users)) {
            foreach ($users as $user => $value) {
                $checkout_user = $this->loadCheckedinUserByID($value->id, $value->user_id);
                if (!is_null($checkout_user))
                    $checkout_user->end_time = (string)$end_time;
                $checkout_user->update(array('end_time')); 
                unset($checkout_user);
            }
        }
    }

    /**
     * Auto logout all user at 21:30:00
     * This action will run daily at 21:30 by CRONJOB which is set on the server
     */
    public function actionLogoutAllUsers() {
        $users = $this->getCurrentUsers();

        if (date('H:i', time()) == '21:30') { // check time is 21:30
            if (!is_null($users)) {  // if there are users still did not logout at night
                foreach ($users as $k => $value) {
                    $checkout_user = $this->loadCheckedinUserByID($value->id, $value->user_id);                    
                    if (!is_null($checkout_user))
                        $checkout_user->end_time = (string)mktime(21, 30, 00, CURRENT_MONTH, CURRENT_DAY, CURRENT_YEAR); // auto logout with end time at 21:30:00 PM
                    $checkout_user->update(array('end_time'));
                    unset($checkout_user);
                }
            }
        }
    }

    /**
     * get current users in day
     * return list of users
     */
    public function getCurrentUsers() {
        $users = CheckinDairy::model()->findAll(array(
            'condition' => 'checkin_date=:to_day',
            'params' => array(
                ':to_day' => date('d-m-Y', time())
            )
                ));
        return $users;
    }

    /**
     * Load user by ID for update end_time
     * return a user
     */
    public function loadCheckedinUserByID($checkin_id, $user_id) {
        $user = CheckinDairy::model()->find(array(
            'condition' => 'user_id=:user_id AND id=:checkin_id',
            'params' => array(
                ':user_id' => $user_id,
                ':checkin_id' => $checkin_id
            )
                ));
        return $user;
    }
    
    /**
     * Destroy all sessions of users in local state - which caused error when users did not logout 
     * at the time before, therefor, when the next time load the page, logout function might cause error
     */
    public function actionDestroyAllSessions(){
        Yii::app()->session->destroy();
        printf("All sessions are destroyed, click %s to return login page", "<a href='".Yii::app()->baseUrl."/index.php/user/auth'>a</a>");
    }

}