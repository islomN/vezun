<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cargo}}`.
 */
class m190223_062206_create_cargo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cargo}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'weight' => $this->float(),
            'volume' => $this->float()
        ]);

        $this->createIndex('cargo_title_i', '{{%cargo}}', 'title');
        $this->createIndex('cargo_weight_i', '{{%cargo}}', 'weight');
        $this->createIndex('cargo_volume_i', '{{%cargo}}', 'volume');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        $this->dropIndex('cargo_title_i', '{{%cargo}}');
//        $this->dropIndex('cargo_weight_i', '{{%cargo}}');
//        $this->dropIndex('cargo_volume_i', '{{%cargo}}');

        $this->dropTable('{{%cargo}}');
    }
}
