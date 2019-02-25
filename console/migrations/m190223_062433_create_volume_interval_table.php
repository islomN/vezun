<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%volume_interval}}`.
 */
class m190223_062433_create_volume_interval_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%volume_interval}}', [
            'id' => $this->primaryKey(),
            'from' => $this->float(),
            'to' => $this->float(),
        ]);

        $this->createIndex('volume_interval_from_i', '{{%volume_interval}}', 'from');
        $this->createIndex('volume_interval_to_i', '{{%volume_interval}}', 'to');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        $this->dropIndex('volume_interval_from_i', '{{%weight_interval}}');
//        $this->dropIndex('volume_interval_to_i', '{{%weight_interval}}');

        $this->dropTable('{{%volume_interval}}');
    }
}
