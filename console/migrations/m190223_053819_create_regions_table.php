<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%regions}}`.
 */
class m190223_053819_create_regions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%regions}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'country_id' => $this->integer(),
        ]);

        $this->createIndex('name_region_i', '{{%regions}}', 'name');

        $this->addForeignKey('country_fk',
                            '{{%regions}}',
                            'country_id',
                            '{{%countries}}',
                            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('country_fk', '{{%regions}}');
        $this->dropTable('{{%regions}}');
    }
}
