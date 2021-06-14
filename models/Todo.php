<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;


class Todo extends ActiveRecord
{

    public static function tableName()
    {
        return 'todo';
    }

    public function rules()
    {
        return [
            [['text', 'user_id'], 'required'],
            ['user_id', 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['text', 'string']
        ];
    }

    public static function createTodo($req) //Todo: Post request
    {
        $todo = new Todo();
        
        $todo->text = $req['text']; //текст полученный с фронта записываем в базу
        $todo->user_id = Yii::$app->user->id; //получаем юсер айди пользователя
        $group_name = 'Персональные';
        $group_id = 0;
        if (isset($req['group'])) {
            $todo->group_id = $req['group'];
            $group_id = $todo->group_id;
            $group_name = Group::findGroupName($todo->group_id);
        }

        $is_saved = $todo->save(); //возвращаем результат операции
        

        if ($is_saved) {
            return [
                'id' => $todo->id,
                'text' => $todo->text,
                'group_id' => $group_id,
                'group_name' => $group_name,
                'completed' => $todo->completed

            ];
        } else return false;
    }

    public static function getAllTodo()
    {
        $todos = Todo::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->all();
        $todosJson = [];
        
        foreach ($todos as $todo) {
            $group_id = isset($todo['group_id']) ? $todo['group_id'] : 0;
            array_push($todosJson, [
                'id' => $todo['id'],
                'text' => $todo['text'],
                'group_id' => $group_id,
                'group_name' => Group::findGroupName($todo['group_id']),
                'completed' => $todo['completed']
            ]);
        }
        return $todosJson;
    }

    public static function deleteTodo($todo_id)
    {
        try {
            $todo = Todo::find()->where(['id' => intval($todo_id)])->one();
            if ($todo == NULL) return false;
            $todo->delete();
            return true;
        } catch (Exception $e) {
            var_dump($e);
            die;
            return false;
        }
    }

    public static function checkedTodo($params)
    {
        $todo = Todo::find()
        ->where(['id' => intval($params['id'])])
        ->one();
        if($params['state'] == 'false') {
         $todo->completed = false;
        }
        elseif($params['state'] == 'true') {
        $todo->completed = true;
        }
        $todo->save();
        return [
            'completed' => $todo->completed,
            'id' => $params['id']
        ];
            
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
