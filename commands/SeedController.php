<?php

declare(strict_types=1);

namespace app\commands;

use app\models\Author;
use app\models\AuthorSubscription;
use app\models\Book;
use app\models\User;
use RuntimeException;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class SeedController extends Controller
{
    public function actionIndex(): int
    {
        $this->createAdmin();

        $authors = $this->createAuthors();
        $this->createBooks($authors);
        $this->createSubscriptions($authors);

        $this->stdout("Seed data created.\n");

        return ExitCode::OK;
    }

    public function actionReset(): int
    {
        $this->resetData();

        return $this->actionIndex();
    }

    private function createAdmin(): void
    {
        if (User::find()->where(['username' => 'admin'])->exists()) {
            return;
        }

        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@example.com';
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword('admin');
        $user->generateAuthKey();
        $user->save(false);
    }

    /**
     * @return array<string, Author>
     */
    private function createAuthors(): array
    {
        $authorNames = [
            'tolstoy' => 'Lev Tolstoy',
            'dostoevsky' => 'Fedor Dostoyevski',
            'orwell' => 'George Orwell',
            'remark' => 'Erich Maria Remarque',
        ];

        $authors = [];

        foreach ($authorNames as $key => $fullName) {
            $author = Author::findOne(['full_name' => $fullName]);

            if ($author === null) {
                $author = new Author();
                $author->full_name = $fullName;
                $author->save(false);
            }

            $authors[$key] = $author;
        }

        return $authors;
    }

    /**
     * @param array<string, Author> $authors
     */
    private function createBooks(array $authors): void
    {
        $books = [
            [
                'title' => 'War and Peace',
                'release_year' => 1869,
                'description' => 'A historical novel about Russian society during the Napoleonic era.',
                'isbn' => '9780000000001',
                'cover_image' => $this->copySeedImage('war-and-peace.png'),
                'authors' => [$authors['tolstoy']],
            ],
            [
                'title' => 'Anna Karenina',
                'release_year' => 1877,
                'description' => 'A novel about love, family, society, and moral conflict.',
                'isbn' => '9780000000002',
                'cover_image' => $this->copySeedImage('anna-karenina.png'),
                'authors' => [$authors['tolstoy']],
            ],
            [
                'title' => 'Crime and Punishment',
                'release_year' => 1866,
                'description' => 'A psychological novel about guilt, morality, and redemption.',
                'isbn' => '9780000000003',
                'cover_image' => $this->copySeedImage('crime-and-punishment.png'),
                'authors' => [$authors['dostoevsky']],
            ],
            [
                'title' => 'Wonderful Book',
                'release_year' => 1993,
                'description' => 'Demo book with several authors.',
                'isbn' => '9780000000004',
                'cover_image' => $this->copySeedImage('wonderful-book.png'),
                'authors' => [
                    $authors['tolstoy'],
                    $authors['dostoevsky'],
                    $authors['orwell'],
                ],
            ],
        ];

        foreach ($books as $bookData) {
            $book = Book::findOne(['isbn' => $bookData['isbn']]);
            if ($book !== null) {
                continue;
            }

            $book = new Book();
            $book->title = $bookData['title'];
            $book->release_year = $bookData['release_year'];
            $book->description = $bookData['description'];
            $book->isbn = $bookData['isbn'];
            $book->cover_image = $bookData['cover_image'];
            $book->save(false);

            $book->unlinkAll('authors', true);

            foreach ($bookData['authors'] as $author) {
                $book->link('authors', $author);
            }
        }
    }

    /**
     * @param array<string, Author> $authors
     */
    private function createSubscriptions(array $authors): void
    {
        $subscriptions = [
            [
                'author_id' => $authors['tolstoy']->id,
                'phone' => '+79990000000',
            ],
            [
                'author_id' => $authors['dostoevsky']->id,
                'phone' => '+79991112233',
            ],
        ];

        foreach ($subscriptions as $data) {
            if (AuthorSubscription::find()->where($data)->exists()) {
                continue;
            }

            $subscription = new AuthorSubscription();
            $subscription->author_id = $data['author_id'];
            $subscription->phone = $data['phone'];
            $subscription->save(false);
        }
    }

    private function copySeedImage(string $fileName): string
    {
        $webRoot = Yii::getAlias('@app/web');

        $sourcePath = $webRoot . DIRECTORY_SEPARATOR . 'seed-images' . DIRECTORY_SEPARATOR . $fileName;
        $targetDir = $webRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'books';
        $targetPath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        if (!file_exists($sourcePath)) {
            throw new RuntimeException(sprintf('Seed image "%s" does not exist.', $sourcePath));
        }

        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created.', $targetDir));
        }

        if (!copy($sourcePath, $targetPath)) {
            throw new RuntimeException(sprintf('Failed to copy image "%s".', $fileName));
        }

        return '/uploads/books/' . $fileName;
    }

    private function resetData(): void
    {
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();

        Yii::$app->db->createCommand()->truncateTable('{{%author_subscriptions}}')->execute();
        Yii::$app->db->createCommand()->truncateTable('{{%book_author}}')->execute();
        Yii::$app->db->createCommand()->truncateTable('{{%books}}')->execute();
        Yii::$app->db->createCommand()->truncateTable('{{%authors}}')->execute();

        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();

        $this->stdout("Seed tables cleared.\n");
    }
}
