<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_file}}`.
 */
class m221007_115913_create_task_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task_file', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'file_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-task_file-task_id',
            'task_file',
            'task_id'
        );
        $this->addForeignKey(
            'fk-task_file-task_id',
            'task_file',
            'task_id',
            'task',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-task_file-file_id',
            'task_file',
            'file_id'
        );
        $this->addForeignKey(
            'fk-task_file-file_id',
            'task_file',
            'file_id',
            'file',
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
        $this->dropIndex('idx-task_file-task_id', 'task_file');
        $this->dropForeignKey('fk-task_file-task_id', 'task_file');

        $this->dropIndex('idx-task_file-file_id', 'task_file');
        $this->dropForeignKey('fk-task_file-file_id', 'task_file');

        $this->dropTable('task_file');
    }
}
