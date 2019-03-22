<?php

use yii\db\Migration;

/**
 * Class m190315_171817_add_column_regions_table
 */
class m190315_171817_add_column_regions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%regions}}", 'position', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%regions}}", 'position');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190315_171817_add_column_regions_table cannot be reverted.\n";

        return false;
    }
    */
}
