<?php

use yii\db\Migration;

/**
 * Class m190315_171810_add_column_countries_table
 */
class m190315_171810_add_column_countries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%countries}}", 'position', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%countries}}", 'position');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190315_171810_add_column_countries_table cannot be reverted.\n";

        return false;
    }
    */
}
