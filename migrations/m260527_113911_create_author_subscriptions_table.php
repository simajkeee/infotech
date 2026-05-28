<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author_subscriptions}}`.
 */
class m260527_113911_create_author_subscriptions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author_subscriptions}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-author_subscriptions-author_id',
            '{{%author_subscriptions}}',
            'author_id'
        );

        $this->createIndex(
            'idx-author_subscriptions-phone',
            '{{%author_subscriptions}}',
            'phone'
        );

        $this->createIndex(
            'idx-author_subscriptions-author_id-phone',
            '{{%author_subscriptions}}',
            ['author_id', 'phone'],
            true
        );

        $this->addForeignKey(
            'fk-author_subscriptions-author_id',
            '{{%author_subscriptions}}',
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
        $this->dropForeignKey(
            'fk-author_subscriptions-author_id',
            '{{%author_subscriptions}}'
        );

        $this->dropIndex(
            'idx-author_subscriptions-author_id-phone',
            '{{%author_subscriptions}}'
        );

        $this->dropIndex(
            'idx-author_subscriptions-phone',
            '{{%author_subscriptions}}'
        );

        $this->dropIndex(
            'idx-author_subscriptions-author_id',
            '{{%author_subscriptions}}'
        );

        $this->dropTable('{{%author_subscriptions}}');
    }
}
