<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Link model
 * 
 * @property int $id
 * @property int $user_id
 * @property string $code
 * @property string $original_url
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Link extends ActiveRecord
{
    public static function tableName()
    {
        return 'links';
    }

    public function rules()
    {
        return [
            [['user_id', 'code', 'original_url'], 'required'],
            [['user_id'], 'integer'],
            [['code'], 'string', 'max' => 255],
            [['original_url'], 'url'],
            [['original_url'], 'string', 'max' => 2048],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'code' => 'Код',
            'original_url' => 'Исходная ссылка',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getClicks()
    {
        return $this->hasMany(Click::class, ['link_id' => 'id']);
    }

    public function getShortUrl()
    {
        return \Yii::$app->urlManager->createAbsoluteUrl([$this->code]);
    }

    public function getClicksCount()
    {
        return $this->getClicks()->count();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            $this->updated_at = time();
            return true;
        }
        return false;
    }
}
