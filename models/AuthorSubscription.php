<?php

declare(strict_types=1);

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $author_id
 * @property string $phone
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Author $author
 */
class AuthorSubscription extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%author_subscriptions}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules(): array
    {
        return [
            [['author_id', 'phone'], 'required'],
            [['author_id', 'created_at', 'updated_at'], 'integer'],
            ['phone', 'string', 'max' => 32],
            ['phone', 'trim'],

            [
                ['author_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Author::class,
                'targetAttribute' => ['author_id' => 'id'],
            ],

            [
                ['author_id', 'phone'],
                'unique',
                'targetAttribute' => ['author_id', 'phone'],
                'message' => 'This phone is already subscribed to this author.',
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author',
            'phone' => 'Phone',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
