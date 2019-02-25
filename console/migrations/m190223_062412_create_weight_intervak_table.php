<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%weight_interval}}`.
 */
class m190223_062412_create_weight_intervak_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%weight_interval}}', [
            'id' => $this->primaryKey(),
            'from' => $this->float(),
            'to' => $this->float(),
        ]);

        $this->createIndex('weight_interval_from_i', '{{%weight_interval}}', 'from');
        $this->createIndex('weight_interval_to_i', '{{%weight_interval}}', 'to');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        $this->dropIndex('weight_interval_from_i', '{{%weight_interval}}');
//        $this->dropIndex('weight_interval_to_i', '{{%weight_interval}}');

        $this->dropTable('{{%weight_interval}}');
    }
}
