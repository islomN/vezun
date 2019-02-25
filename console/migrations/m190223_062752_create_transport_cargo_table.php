<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transport_cargo}}`.
 */
class m190223_062752_create_transport_cargo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transport_cargo}}', [
            'transport_info_id' => $this->integer(),
            'cargo_info_id' => $this->integer(),
        ]);

        $this->addForeignKey('transport_info_fk',
                            '{{%transport_cargo}}',
                            'transport_info_id',
                            '{{%transport_info}}',
                            'id');

        $this->addForeignKey('cargo_info_fk',
            '{{%transport_cargo}}',
            'cargo_info_id',
            '{{%cargo_info}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('transport_info_fk',
            '{{%transport_cargo}}');

        $this->dropForeignKey('cargo_info_fk',
            '{{%transport_cargo}}');

        $this->dropTable('{{%transport_cargo}}');
    }
}
