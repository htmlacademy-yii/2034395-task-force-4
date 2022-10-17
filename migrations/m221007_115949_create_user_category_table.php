<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_category}}`.
 */
class m221007_115949_create_user_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_category', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-user_category-user_id',
            'user_category',
            'user_id'
        );
        $this->addForeignKey(
            'fk-user_category-user_id',
            'user_category',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-user_category-category_id',
            'user_category',
            'category_id'
        );
        $this->addForeignKey(
            'fk-user_category-category_id',
            'user_category',
            'category_id',
            'category',
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
        $this->dropIndex('idx-user_category-user_id', 'user_category');
        $this->dropForeignKey('fk-user_category-user_id', 'user_category');

        $this->dropIndex('idx-user_category-category_id', 'user_category');
        $this->dropForeignKey('fk-user_category-category_id', 'user_category');

        $this->dropTable('user_category');
    }
}
