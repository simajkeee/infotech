<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

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
    public array $authorIds = [];

    /**
     * @var UploadedFile|null
     */
    public $coverImageFile = null;

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
            [['title', 'release_year', 'isbn', 'authorIds'], 'required'],
            [['release_year', 'created_at', 'updated_at'], 'integer'],
            ['description', 'string'],
            [['title', 'cover_image'], 'string', 'max' => 255],
            ['isbn', 'string', 'max' => 20],
            ['isbn', 'unique'],
            [['title', 'isbn', 'cover_image'], 'trim'],
            ['authorIds', 'each', 'rule' => ['integer']],

            [
                'coverImageFile',
                'file',
                'skipOnEmpty' => true,
                'extensions' => ['png', 'jpg', 'jpeg', 'webp'],
                'maxSize' => 1024 * 1024 * 2,
            ],

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
            'authorIds' => 'Authors',
            'coverImageFile' => 'Cover Image',
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

    public function afterFind(): void
    {
        parent::afterFind();

        $this->authorIds = array_map(
            static fn (Author $author): int => $author->id,
            $this->authors
        );
    }

    public function saveAuthors(): void
    {
        $this->unlinkAll('authors', true);

        foreach ($this->authorIds as $authorId) {
            $author = Author::findOne((int) $authorId);

            if ($author !== null) {
                $this->link('authors', $author);
            }
        }
    }

    public function uploadCoverImage(): bool
    {
        if ($this->coverImageFile === null) {
            return true;
        }

        $uploadDir = Yii::getAlias('@webroot/uploads/books');
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $uploadDir));
            }
        }

        $fileName = uniqid('book_', true) . '.' . $this->coverImageFile->extension;
        $filePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

        if (!$this->coverImageFile->saveAs($filePath)) {
            return false;
        }

        $this->cover_image = '/uploads/books/' . $fileName;

        return true;
    }
}
