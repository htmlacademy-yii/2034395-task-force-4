<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city}}`.
 */
class m221007_115821_create_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('city', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'lat' => $this->float(),
            'long' => $this->float(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('city');
    }
}
