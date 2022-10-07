<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m221007_115835_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string(),
            'password' => $this->string(),
            'username' => $this->string(),
            'status' => $this->string(),
            'details' => $this->text(),
            'avatar_url' => $this->string(),
            'is_executor' => $this->boolean(),
            'city_id' => $this->integer(),
            'birthday' => $this->dateTime(),
            'phone_number' => $this->string(),
            'telegram' => $this->string(),
            'registration_date' => $this->dateTime(),
        ]);

        $this->createIndex(
            'idx-user-city_id',
            'user',
            'city_id'
        );
        $this->addForeignKey(
            'fk-user-city_id',
            'user',
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
        $this->dropIndex('idx-user-city_id', 'user');
        $this->dropForeignKey('fk-user-city_id', 'user');

        $this->dropTable('user');
    }
}
