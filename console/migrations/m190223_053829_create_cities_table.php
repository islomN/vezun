<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cities}}`.
 */
class m190223_053829_create_cities_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('{{%cities}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'region_id' => $this->integer(),
        ]);

        $this->createIndex('name_city_i', '{{%cities}}', 'name');

        $this->addForeignKey('region_fk',
            '{{%cities}}',
            'region_id',
            '{{%regions}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('region_fk', '{{%cities}}');
        $this->dropTable('{{%cities}}');
    }
}
