<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_author}}`.
 */
class m260527_113415_create_book_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book_author}}', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey(
            'pk-book_author',
            '{{%book_author}}',
            ['book_id', 'author_id']
        );

        $this->createIndex(
            'idx-book_author-book_id',
            '{{%book_author}}',
            'book_id'
        );

        $this->createIndex(
            'idx-book_author-author_id',
            '{{%book_author}}',
            'author_id'
        );

        $this->addForeignKey(
            'fk-book_author-book_id',
            '{{%book_author}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-book_author-author_id',
            '{{%book_author}}',
            'author_id',
            '{{%authors}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-book_author-author_id', '{{%book_author}}');
        $this->dropForeignKey('fk-book_author-book_id', '{{%book_author}}');

        $this->dropIndex('idx-book_author-author_id', '{{%book_author}}');
        $this->dropIndex('idx-book_author-book_id', '{{%book_author}}');

        $this->dropPrimaryKey('pk-book_author', '{{%book_author}}');

        $this->dropTable('{{%book_author}}');
    }
}
