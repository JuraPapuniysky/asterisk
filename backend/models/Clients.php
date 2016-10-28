<?php

namespace backend\models;

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
            [['conference', 'callerid'], 'integer'],
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
            'id' => '№',
            'name' => 'Имя',
            'channel' => 'Channel',
            'conference' => 'Conference',
            'mutte' => 'Mutte',
            'callerid' => 'Номер телефона',
            'video' => 'Video',
        ];
    }
}
