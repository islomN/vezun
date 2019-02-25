<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transport_types}}`.
 */
class m190223_062249_create_transport_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transport_types}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'position' => $this->integer(),
        ]);

        $this->createIndex('transport_types_i', '{{%transport_types}}', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        $this->dropIndex('transport_types_i', '{{%transport_types}}');

        $this->dropTable('{{%transport_types}}');
    }
}
