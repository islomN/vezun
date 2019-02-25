<?php

use yii\db\Migration;

/**
 * Class m190224_090250_alter_transport_info_table
 */
class m190224_090250_alter_transport_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('t_map_fk',
            '{{%transport_info}}');
        $this->renameColumn('{{%transport_info}}', 'map_id', 'from_map_id');
        $this->addColumn('{{%transport_info}}', 'to_map_id', $this->integer());

        $this->addForeignKey('t_from_map_fk',
            '{{%transport_info}}',
            'from_map_id',
            '{{%map}}',
            'id');

        $this->addForeignKey('t_to_map_fk',
            '{{%transport_info}}',
            'to_map_id',
            '{{%map}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('t_to_map_fk',
            '{{%transport_info}}');

        $this->dropForeignKey('t_from_map_fk',
            '{{%transport_info}}');

        $this->renameColumn('{{%transport_info}}', 'from_map_id', 'map_id');
        $this->dropColumn('{{%transport_info}}', 'to_map_id');

        $this->addForeignKey('t_map_fk',
            '{{%transport_info}}',
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
        echo "m190224_090250_alter_transport_info_table cannot be reverted.\n";

        return false;
    }
    */
}
