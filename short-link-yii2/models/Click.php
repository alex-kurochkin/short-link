<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Click model
 * 
 * @property int $id
 * @property int $link_id
 * @property string $ip_address
 * @property int $clicked_at
 */
class Click extends ActiveRecord
{
    public static function tableName()
    {
        return 'clicks';
    }

    public function rules()
    {
        return [
            [['link_id', 'ip_address', 'clicked_at'], 'required'],
            [['link_id', 'clicked_at'], 'integer'],
            [['ip_address'], 'string', 'max' => 45],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link_id' => 'Ссылка',
            'ip_address' => 'IP адрес',
            'clicked_at' => 'Время клика',
        ];
    }

    public function getLink()
    {
        return $this->hasOne(Link::class, ['id' => 'link_id']);
    }
}
