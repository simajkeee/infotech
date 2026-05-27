<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m260527_112734_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'release_year' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(20)->notNull()->unique(),
            'cover_image' => $this->string(255),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-books-title',
            '{{%books}}',
            'title'
        );

        $this->createIndex(
            'idx-books-release_year',
            '{{%books}}',
            'release_year'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books}}');
    }
}
