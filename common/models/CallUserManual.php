<?php


namespace common\models;


use yii\base\Model;

class CallUserManual extends Model
{
    public $conference;
    
    public $userNumber;

    public function rules()
    {
        return [
            [['conference', 'userNumber'], 'required'],
            ['conference', 'match', 'pattern' => '/^\d+$/'],
            ['userNumber', 'match', 'pattern' => '/^\d+$/'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'conference' => 'Номер конференции',
            'userNumber' => 'Номер пользователя',
        ];
    }

}