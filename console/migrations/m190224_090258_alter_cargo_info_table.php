<?php

use yii\db\Migration;

/**
 * Class m190224_090258_alter_cargo_info_table
 */
class m190224_090258_alter_cargo_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('c_map_fk',
            '{{%cargo_info}}');
        $this->renameColumn('{{%cargo_info}}', 'map_id', 'from_map_id');
        $this->addColumn('{{%cargo_info}}', 'to_map_id', $this->integer());

        $this->addForeignKey('c_from_map_fk',
            '{{%cargo_info}}',
            'from_map_id',
            '{{%map}}',
            'id');

        $this->addForeignKey('c_to_map_fk',
            '{{%cargo_info}}',
            'to_map_id',
            '{{%map}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('c_to_map_fk',
            '{{%cargo_info}}');

        $this->dropForeignKey('c_from_map_fk',
            '{{%cargo_info}}');

        $this->renameColumn('{{%cargo_info}}', 'from_map_id', 'map_id');
        $this->dropColumn('{{%cargo_info}}', 'to_map_id');

        $this->addForeignKey('c_map_fk',
            '{{%cargo_info}}',
            'map_id',
            '{{%map}}',
            'id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190224_090258_alter_cargo_info_table cannot be reverted.\n";

        return false;
    }
    */
}
