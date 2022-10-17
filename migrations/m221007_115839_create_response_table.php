<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%response}}`.
 */
class m221007_115839_create_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('response', [
            'id' => $this->primaryKey(),
            'executor_id' => $this->integer()->notNull(),
            'task_id' => $this->integer()->notNull(),
            'price' => $this->integer(),
            'text' => $this->text(),
            'creation_date' => $this->dateTime()
        ]);

        $this->createIndex(
            'idx-response-executor_id',
            'response',
            'executor_id'
        );
        $this->addForeignKey(
            'fk-response-executor_id',
            'response',
            'executor_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-response-task_id',
            'response',
            'task_id'
        );
        $this->addForeignKey(
            'fk-response-task_id',
            'response',
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
        $this->dropIndex('idx-response-executor_id', 'response');
        $this->dropForeignKey('fk-response-executor_id', 'response');

        $this->dropIndex('idx-response-task_id', 'response');
        $this->dropForeignKey('fk-response-task_id', 'response');


        $this->dropTable('response');
    }
}
