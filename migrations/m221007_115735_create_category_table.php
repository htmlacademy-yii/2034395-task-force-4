<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m221007_115735_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'icon' => $this->string()
        ]);

        $this->batchInsert('category', ['name', 'icon'], [
            ['Курьерские услуги', 'courier'],
            ['Уборка', 'clean'],
            ['Переезды', 'cargo'],
            ['Компьютерная помощь', 'neo'],
            ['Ремонт квартирный', 'flat'],
            ['Ремонт техники', 'repair'],
            ['Красота', 'beauty'],
            ['Фото', 'photo'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');
    }
}
