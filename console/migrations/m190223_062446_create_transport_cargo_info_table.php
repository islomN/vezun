<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transport_cargo_info}}`.
 */
class m190223_062446_create_transport_cargo_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transport_cargo_info}}', [
            'id' => $this->primaryKey(),
            'weight_interval_id' => $this->integer(),
            'volume_interval_id' => $this->integer(),
        ]);

        $this->addForeignKey('weight_interval_fk',
                            '{{%transport_cargo_info%}}',
                            'weight_interval_id',
                            '{{%weight_interval%}}',
                            'id');

        $this->addForeignKey('volume_interval_fk',
            '{{%transport_cargo_info%}}',
            'volume_interval_id',
            '{{%volume_interval%}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('weight_interval_fk', '{{%transport_cargo_info%}}');
        $this->dropForeignKey('volume_interval_fk', '{{%transport_cargo_info%}}');

        $this->dropTable('{{%transport_cargo_info}}');
    }
}
