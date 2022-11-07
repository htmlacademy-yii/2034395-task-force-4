<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m221007_115837_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'customer_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer(),
            'city_id' => $this->integer()->defaultValue(null),
            'location' => $this->string(),
            'status' => $this->string(),
            'title' => $this->string(),
            'details' => $this->text(),
            'budget' => $this->integer(),
            'execution_date' => $this->dateTime(),
            'creation_date' => $this->dateTime(),
        ]);

        $this->createIndex(
            'idx-task-category_id',
            'task',
            'category_id'
        );
        $this->addForeignKey(
            'fk-task-category_id',
            'task',
            'category_id',
            'category',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-task-customer_id',
            'task',
            'customer_id'
        );
        $this->addForeignKey(
            'fk-task-customer_id',
            'task',
            'customer_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-task-executor_id',
            'task',
            'executor_id'
        );
        $this->addForeignKey(
            'fk-task-executor_id',
            'task',
            'executor_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-task-city_id',
            'task',
            'city_id'
        );
        $this->addForeignKey(
            'fk-task-city_id',
            'task',
            'city_id',
            'city',
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
        $this->dropIndex('idx-task-category_id', 'task');
        $this->dropForeignKey('fk-task-category_id', 'task');

        $this->dropIndex('idx-task-customer_id', 'task');
        $this->dropForeignKey('fk-task-customer_id', 'task');

        $this->dropIndex('idx-task-executor_id', 'task');
        $this->dropForeignKey('fk-task-executor_id', 'task');

        $this->dropIndex('idx-task-city_id', 'task');
        $this->dropForeignKey('fk-task-city_id', 'task');


        $this->dropTable('task');
    }
}
