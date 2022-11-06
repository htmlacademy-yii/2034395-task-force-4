<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%task}}`.
 */
class m221106_182056_add_location_long_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task', 'location_long', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('task', 'location_long');
    }
}
