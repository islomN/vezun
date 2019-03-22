<?php

use yii\db\Migration;

/**
 * Class m190304_172844_add_column_to_transpotr_type_table
 */
class m190304_172844_add_column_to_transpotr_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%transport_types}}', 'name', 'name_ru');
        $this->addColumn('{{%transport_types}}', 'name_uz', $this->string());
        $this->addColumn('{{%transport_types}}', 'name_oz', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%transport_types}}', 'name_ru', 'name');
        $this->dropColumn('{{%transport_types}}', 'name_uz');
        $this->dropColumn('{{%transport_types}}', 'name_oz');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190304_172844_add_column_to_transpotr_type_table cannot be reverted.\n";

        return false;
    }
    */
}
