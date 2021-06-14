<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Signup;
use app\models\Login;
use Codeception\Lib\Console\Message;
use PHPUnit\Util\Log\JSON;

class SiteController extends Controller
{
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) return $this->render('main');
        return $this->render('index');
    }

    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->redirect(['login']);
        }
    }

    public function actionSignup()
    {
        if(!Yii::$app->user->isGuest) { return $this->redirect('main'); }
        $model = new Signup();
            if (Yii::$app->request->post('Signup')) {
                $model->attributes = Yii::$app->request->post('Signup');

                if ($model->validate() && $model->signup()) {
                    Yii::$app->user->login($model->getUser());
                    return $this->redirect('main');
                } else {
                    return $this->render('signup', ['model' => $model, 'validate' => true] );
                }
            }
        return $this->render('signup', ['model' => $model, 'validate' => false] );
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $login_model = new Login();
        //Проверка работы формы
        if (Yii::$app->request->post('Login')) {
            $login_model->attributes = Yii::$app->request->post('Login');
            // var_dump($login_model->attributes);die;
            if ($login_model->validate()) {
                Yii::$app->user->login($login_model->getUser());
                return $this->goHome();
            } 
        }

        return $this->render('login', ['login_model' => $login_model]);
    }

    public function actionMain()
    {
        if(Yii::$app->user->isGuest) { return $this->redirect('index'); }
       
        return $this->render('main');
    }
}
