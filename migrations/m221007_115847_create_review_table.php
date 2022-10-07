<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%review}}`.
 */
class m221007_115847_create_review_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('review', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer()->notNull(),
            'task_id' => $this->integer()->notNull(),
            'text' => $this->text(),
            'grade' => $this->integer(),
            'creation_date' => $this->dateTime(),
        ]);

        $this->createIndex(
            'idx-review-customer_id',
            'review',
            'customer_id'
        );
        $this->addForeignKey(
            'fk-review-customer_id',
            'review',
            'customer_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-review-executor_id',
            'review',
            'executor_id'
        );
        $this->addForeignKey(
            'fk-review-executor_id',
            'review',
            'executor_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-review-task_id',
            'review',
            'task_id'
        );
        $this->addForeignKey(
            'fk-review-task_id',
            'review',
            'task_id',
            'task',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-review-customer_id', 'review');
        $this->dropForeignKey('fk-review-customer_id', 'review');

        $this->dropIndex('idx-review-executor_id', 'review');
        $this->dropForeignKey('fk-review-executor_id', 'review');

        $this->dropIndex('idx-review-task_id', 'review');
        $this->dropForeignKey('fk-review-task_id', 'review');


        $this->dropTable('review');
    }
}
