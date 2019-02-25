<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transport_info}}`.
 */
class m190223_062719_create_transport_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transport_info}}', [
            'id' => $this->primaryKey(),
            'map_id' => $this->integer(),
            'date_id' => $this->integer(),
            'transport_type_id' => $this->integer(),
            'transport_cargo_info_id' => $this->integer(),
            'user_info_id' => $this->integer(),
            'created_at' => $this->timestamp(),
            'status' => $this->tinyInteger(),
        ]);

        $this->addForeignKey('t_map_fk',
            '{{%transport_info}}',
            'map_id',
            '{{%map}}',
            'id');

        $this->addForeignKey('t_dates_fk',
            '{{%transport_info}}',
            'date_id',
            '{{%dates}}',
            'id');

        $this->addForeignKey('t_transport_type_fk',
            '{{%transport_info}}',
            'transport_type_id',
            '{{%transport_types}}',
            'id');

        $this->addForeignKey('t_cargo_info_fk',
            '{{%transport_info}}',
            'transport_cargo_info_id',
            '{{%transport_cargo_info}}',
            'id');

        $this->addForeignKey('t_user_info_fk',
            '{{%transport_info}}',
            'user_info_id',
            '{{%user_info}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('t_map_fk',
            '{{%transport_info}}');

        $this->dropForeignKey('t_dates_fk',
            '{{%transport_info}}');

        $this->dropForeignKey('t_transport_type_fk',
            '{{%transport_info}}');

        $this->dropForeignKey('t_cargo_info_fk',
            '{{%transport_info}}');

        $this->dropForeignKey('t_user_info_fk',
            '{{%transport_info}}');

        $this->dropTable('{{%transport_info}}');
    }
}
