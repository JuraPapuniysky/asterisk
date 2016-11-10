<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "list_client".
 *
 * @property integer $id
 * @property integer $list_id
 * @property integer $client_id
 *
 * @property Clients $client
 * @property List $list
 */
class ListClient extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'list_client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['list_id', 'client_id'], 'integer'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clients::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ListModel::className(), 'targetAttribute' => ['list_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'list_id' => 'List ID',
            'client_id' => 'Client ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Clients::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getList()
    {
        return $this->hasOne(ListModel::className(), ['id' => 'list_id']);
    }
}
