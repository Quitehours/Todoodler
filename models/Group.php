<?php

namespace app\models;

use yii\base\Model;
use yii\db\ActiveRecord;
use Yii;
use Exception;

class Group extends ActiveRecord
{
    public static function tableName ()
    {
        return 'group';
    }

    public function rules() 
    {
        return[
            [['name', 'user_id'], 'required'],
            ['user_id', 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['name', 'string']
        ];
    }
    
    public static function createGroup ($req)
    {
        $group = new Group();

        $group->name = $req['name'];
        $group->user_id = Yii::$app->user->id;
        $is_saved = $group->save();
        if($is_saved){

            return [
                'id' => $group->id,
                'user_id' => $group->user_id,
                'name' => $group->name,
            ];
        } else return false;
    }

    public static function getAllGroup()
    {
        $groups = Group::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->all();
        $groupsJson = [];
        foreach ($groups as $group) {
            array_push($groupsJson, [
                'id' => $group['id'],
                'name' => $group['name'],
                'user_id' => $group['user_id']
            ]);
        }
        return $groupsJson;
    }

    public static function deleteGroup($group_id)
    {
        try {

            $group = Group::find()->where(['id' => intval($group_id)])->one();
            if ($group == NULL) return false;
            $group->delete();
            return true;
        } catch (Exception $e) {
            var_dump($e);
            die;
            return false;
        }
    }

    public static function findGroupName($group_id) 
    {
        $group = Group::find()->where(['id' => intval($group_id)])->one();
        if(isset($group)) {
            return $group->name;
        }
        else return 'Персональные';

    }
    
    public function getUser()
    {   
        return $this->hasOne(User::class, ['id' => 'user_id']);
        // return User::findOne(['username'=>$this->username]); //а получаем мы его по введенному имейлу
    }

    








}
?>