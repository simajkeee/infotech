<?php

declare(strict_types=1);

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Author extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%authors}}';
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
            ['full_name', 'required'],
            ['full_name', 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'integer'],
            ['full_name', 'trim'],
        ];
    }

    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
                    ->viaTable('{{%book_author}}', ['author_id' => 'id']);
    }
}
