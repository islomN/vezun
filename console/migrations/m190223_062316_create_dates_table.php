<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dates}}`.
 */
class m190223_062316_create_dates_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dates}}', [
            'id' => $this->primaryKey(),
            'from' => $this->date(),
            'to' => $this->date()
        ]);

        $this->createIndex('date_from_i', '{{%dates}}', 'from');
        $this->createIndex('date_to_i', '{{%dates}}', 'to');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        $this->dropIndex('date_from_i', '{{%dates}}');
//        $this->dropIndex('date_to_i', '{{%dates}}');
        $this->dropTable('{{%dates}}');
    }
}
