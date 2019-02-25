<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%map}}`.
 */
class m190223_053839_create_map_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%map}}', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer(),
            'region_id' => $this->integer(),
            'city_id' => $this->integer()
        ]);

        $this->addForeignKey('m_country_fk',
            '{{%map}}',
            'country_id',
            '{{%countries}}',
            'id');

        $this->addForeignKey('m_region_fk',
            '{{%map}}',
            'region_id',
            '{{%regions}}',
            'id');

        $this->addForeignKey('m_city_fk',
            '{{%map}}',
            'city_id',
            '{{%cities}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('m_country_fk', '{{%map}}');
        $this->dropForeignKey('m_region_fk', '{{%map}}');
        $this->dropForeignKey('m_city_fk', '{{%map}}');
        $this->dropTable('{{%map}}');
    }
}
