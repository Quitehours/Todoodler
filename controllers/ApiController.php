<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Todo;
use app\models\User;
use app\models\Group;
use phpDocumentor\Reflection\Types\This;

class ApiController extends Controller
{

    public function actionIndex()
    {
    }

    public function actionGroup()
    {

        if (!Yii::$app->user->isGuest) { //Проверка залогиненный пользователь или нет

            $request = Yii::$app->request; //запрос
            $response = Yii::$app->getResponse();
            $response->format = \yii\web\Response::FORMAT_JSON; //ответ получаем в формате JSON

            if ($request->isGet) { //если запрос GET
                // {return Group::getAllGroup();}
                $params = Yii::$app->request->get();
                // var_dump($params);die;
                if (isset($params['delete'])) {
                    
                    $status = Group::deleteGroup($params['delete']);
                    
                    if ($status) {
                        return [
                            'status' => 'ok'
                        ];
                    } else {
                        return [
                            'status' => 'error'
                        ];
                    }
                }
                
                return Group::getAllGroup();
                
            } elseif ($request->isPost) { //если запрос POST

                $request = Yii::$app->request->post(); //получаем пост запрос
                $newGroup = Group::createGroup($request);
                if ($newGroup != false) {
                    return [
                        'id' => $newGroup['id'], //Возвращаю айди введенной тудушки
                        'user_id' =>$newGroup['user_id'],
                        'name' => $newGroup['name']
                    ];
                } else {

                    return ['message' => 'Запись не смогла добавиться']; //! тут нужно добавить ошибку 
                }
            }
        } else {

            return 'Вам нужно залогинься для начала'; //! тут нужно добавить ошибку 
        }
    }

    public function actionTodo()
    {
        if (!Yii::$app->user->isGuest) { //Проверка залогиненный пользователь или нет

            $request = Yii::$app->request; //запрос
            $response = Yii::$app->getResponse();
            $response->format = \yii\web\Response::FORMAT_JSON; //ответ получаем в формате JSON

            if ($request->isGet) { //если запрос GET

                $params = Yii::$app->request->get();

                if (isset($params['delete'])) {

                    $status = Todo::deleteTodo($params['delete']);
                    if ($status) {
                        return [
                            'status' => 'ok'
                        ];
                    } else {
                        return [
                            'status' => 'error'
                        ];
                    }
                }
                return Todo::getAllTodo();

            } elseif ($request->isPost) { //если запрос POST

                $request = Yii::$app->request->post(); //получаем пост запрос

                $newTodo = Todo::createTodo($request);
                
                if ($newTodo != false) {
                    return [
                        'id' => $newTodo['id'], //Возвращаю айди введенной тудушки
                        'text' => $newTodo['text'],
                        'group_id' => intval($newTodo['group_id']),
                        'group_name' => $newTodo['group_name'],
                        'completed' => $newTodo['completed']

                    ];
                } else {

                    return ['message' => 'Запись не смогла добавиться']; //! тут нужно добавить ошибку 
                }
            } elseif ($request->isPut) {

                $params = Yii::$app->request->post();
                if (isset($params)) {
                    $status = Todo::checkedTodo($params);
                    if ($status) {
                        return [
                            'completed' => $status,
                            'id' => $params['id']
                        ];
                    } else {
                        return [
                            'status' => 'error'
                        ];
                    }
                }
                return Todo::getAllTodo();
            }
        } else {

            return 'Вам нужно залогинься для начала'; //! тут нужно добавить ошибку 
        }
    }

}   