<?php

namespace app\models;

use yii\db\ActiveRecord;
//указываем что реализуем интерфейс, для аактивной сессии пользователя
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }
    public static function findIdentity($id)
    {//запрос в базу по полю айди 
        return self::findOne($id);
    }

    public function getId()
    {//позвращает айди 
        return $this->id;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    public function getAuthKey()
    {

    }

    public function validateAuthKey($authKey)
    {

    }

    
}
