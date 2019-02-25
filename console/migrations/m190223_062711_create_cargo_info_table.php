<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cargo_info}}`.
 */
class m190223_062711_create_cargo_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cargo_info}}', [
            'id' => $this->primaryKey(),
            'map_id' => $this->integer(),
            'date_id' => $this->integer(),
            'transport_type_id' => $this->integer(),
            'cargo_id' => $this->integer(),
            'user_info_id' => $this->integer(),
            'created_at' => $this->timestamp(),
            'status' => $this->tinyInteger(),
        ]);

        $this->addForeignKey('c_map_fk',
            '{{%cargo_info}}',
            'map_id',
            '{{%map}}',
            'id');

        $this->addForeignKey('c_dates_fk',
            '{{%cargo_info}}',
            'date_id',
            '{{%dates}}',
            'id');

        $this->addForeignKey('c_transport_type_fk',
            '{{%cargo_info}}',
            'transport_type_id',
            '{{%transport_types}}',
            'id');

        $this->addForeignKey('c_info_fk',
            '{{%cargo_info}}',
            'cargo_id',
            '{{%cargo}}',
            'id');

        $this->addForeignKey('c_user_info_fk',
            '{{%cargo_info}}',
            'user_info_id',
            '{{%user_info}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('c_map_fk',
            '{{%cargo_info}}');

        $this->dropForeignKey('c_dates_fk',
            '{{%cargo_info}}');

        $this->dropForeignKey('c_transport_type_fk',
            '{{%cargo_info}}');

        $this->dropForeignKey('c_info_fk',
            '{{%cargo_info}}');

        $this->dropForeignKey('c_user_info_fk',
            '{{%cargo_info}}');

        $this->dropTable('{{%cargo_info}}');
    }
}
