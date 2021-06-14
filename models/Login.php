<?php

namespace app\models;

use yii\base\Model;

class Login extends Model
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
            //Пишем собственную функцию валидации для правильного входа в систему
            ['password', 'validatePassword'] //сообственаня функция валидаци данных
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if(!$this->hasErrors()) // Если нет ошибок в валидации 
        {
            $user = $this->getUser(); // Получаем пользователя для дальнейшего сравнения 
            //Проверка на правильность ввода данных
            if(!$user || !$user->validatePassword($this->password)) 
            //если мы НЕ нашли в базе такого пользователя
            //или введенный пароль и пароль пользователя в базе НЕ равно ТО,
                {
                    $this->addError($attribute, 'Пароль или пользователь введены неверно');
                    //добавляем новую ошибку для атрибута password о том что пароль или имейл введены  не верно
                }

        }
    }

    //Нахождение юсера
    public function getUser()
    {
        return User::findOne(['username'=>$this->username]); //а получаем мы его по введенному имейлу

    }

}