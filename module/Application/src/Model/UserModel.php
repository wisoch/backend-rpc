<?php

namespace Localhost\Model;

use Zend\EventManager\EventManagerAwareTrait;
use Kernel\Mvc\Model\AbstractModel;

class UserModel extends AbstractModel
{
    use EventManagerAwareTrait ;

    const EVENT_USER_LOGIN       = 'user.login';
    const EVENT_USER_LOGIN_ERROR = 'user.login.error';

    public function login()
    {
        $this->getEventManager()->trigger(static::EVENT_USER_LOGIN, $this);
    }
}
