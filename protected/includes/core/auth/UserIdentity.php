<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    const ERROR_STATUS_INACTIVE = 3;
    const ERROR_STATUS_BANNED = 4;
    const ERROR_STATUS_REMOVED = 5;

    public $user;

    /**
     * Authenticates a user
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $this->user = User::model()->find('username = :username', array(':username' => $this->username));
        if (is_null($this->user)) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($this->user->password !== md5($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else if ($this->user->status == User::STATUS_INACTIVE) {
            $this->errorCode = self::ERROR_STATUS_INACTIVE;
        } else if ($this->user->status == User::STATUS_BANNED) {
            $this->errorCode = self::ERROR_STATUS_BANNED;
        } else if ($this->user->status == User::STATUS_REMOVED) {
            $this->errorCode = self::ERROR_STATUS_REMOVED;
        } else {
            $this->errorCode = self::ERROR_NONE;
            $this->setUserState();
        }
        return $this->errorCode;
    }

    public function setUserState() {
        $this->setState('USERNAME', $this->user->username);
        $this->setState('STATUS', $this->user->status);
    }

    public function getId() {
        return $this->user->id;
    }

    public function getErrorMessage($code) {
        switch ($code) {
            case UserIdentity::ERROR_NONE:
                return '';
            case UserIdentity::ERROR_USERNAME_INVALID:
                return TranslateUtil::t('Sorry but your username is not found');
            case UserIdentity::ERROR_STATUS_INACTIVE:
                return TranslateUtil::t('Your status is inactive. Please contact our administrator.');
            case UserIdentity::ERROR_STATUS_BANNED:
                return TranslateUtil::t('Your account is banned. Please contact our administrator.');
            case UserIdentity::ERROR_STATUS_REMOVED:
                return TranslateUtil::t('Your account is removed. Please contact our administrator.');
            case UserIdentity::ERROR_PASSWORD_INVALID:
            case UserIdentity::ERROR_UNKNOWN_IDENTITY:
                return TranslateUtil::t('Username or password is invalid!');
            default:
                return 'Sorry, you cannot login as some errors occur.';
        }
    }

}