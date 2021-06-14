<?php
//! Модель прослойка, которая показывает правильную валидацию при регистрации

namespace app\models;

use yii\base\Model;

class Signup extends Model 
{
    public $username;
    public $password;

    public function rules() 
    {
        return[
            // username and password обязательны к заполнению  
            [['username', 'password'], 'required'],
            // username должен быть string
            ['username', 'string'],
            //username должен быть уникален и поэтмоу мы его прописываем
            // [['username', 'unique', 'targetClass'=>'app\models\User']],
            //пароль должен состоять не меньше 2 не больше 10
            ['password', 'string', 'min'=>2, 'max'=>10]
        ];
    }

    public function signup() 
    {
        $user = new User();
        $namebase = User::find()->where(['username' => $this->username])->one();
        if($namebase) return false;
        $user->username = $this->username;
        $user->password = md5($this->password); //шифрование md5 
        return $user->save();
    }

    public function getUser()
    {
        return User::findOne(['username'=>$this->username]); //а получаем мы его по введенному имейлу
    }
    
}