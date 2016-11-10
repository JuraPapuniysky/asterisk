<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "list".
 *
 * @property integer $id
 * @property string $name
 *
 * @property ListClient[] $listClients
 */
class ListModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListClients()
    {
        return $this->hasMany(Clients::className(), ['id' => 'client_id'])
            ->viaTable('list_client', ['list_id' => 'id']);
    }
}
