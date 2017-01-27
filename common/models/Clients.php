<?php

namespace common\models;

use frontend\controllers\SiteController;
use Yii;


/**
 * This is the model class for table "clients".
 *
 * @property integer $id
 * @property string $name
 * @property string $channel
 * @property integer $conference
 * @property string $mutte
 * @property integer $callerid
 * @property string $video
 */
class Clients extends \yii\db\ActiveRecord
{

    public $position;
    public $isCall;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'callerid'], 'required'],
            [['conference', 'callerid'], 'string', 'max' => 15],
            [['name', 'channel'], 'string', 'max' => 255],
            [['mutte', 'video'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя пользователя',
            'channel' => 'Channel',
            'conference' => 'Conference',
            'mutte' => 'Mutte',
            'callerid' => 'Номер пользователя',
            'video' => 'Video',
        ];
    }



    public static function initClient($user, $conference, $channel)
    {
        $user->channel = $channel;
        $user->conference = $conference;
        return $user;
    }

    public static function getUserConfId($conf)
    {
        $ids = [];
        foreach ($conf as $column){
            foreach ($column as $user){
                array_push($ids, $user->callerId);
            }
        }
        return $ids;
    }

    public static function getHeadpiece()
    {
        return self::findOne(['callerid' => '894490']);
    }

}

