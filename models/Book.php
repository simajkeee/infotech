<?php

declare(strict_types=1);

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title
 * @property int $release_year
 * @property string|null $description
 * @property string $isbn
 * @property string|null $cover_image
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Author[] $authors
 */
class Book extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%books}}';
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
            [['title', 'release_year', 'isbn'], 'required'],
            [['release_year', 'created_at', 'updated_at'], 'integer'],
            ['description', 'string'],
            [['title', 'cover_image'], 'string', 'max' => 255],
            ['isbn', 'string', 'max' => 20],
            ['isbn', 'unique'],
            [['title', 'isbn', 'cover_image'], 'trim'],

            [
                'release_year',
                'integer',
                'min' => 1000,
                'max' => (int) date('Y'),
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'release_year' => 'Release Year',
            'description' => 'Description',
            'isbn' => 'ISBN',
            'cover_image' => 'Cover Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
                    ->viaTable('{{%book_author}}', ['book_id' => 'id']);
    }
}
