<?php

use yii\db\Migration;

/**
 * Class m190223_122947_alter_user_info_table
 */
class m190223_122947_alter_user_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('bot_user_fk',
            '{{%user_info}}');
        $this->dropColumn('{{%user_info}}', 'bot_user_id');

        $this->createTable('{{%bot_user_info}}', [
            'user_info_id' => $this->integer(),
            'bot_user_id' => $this->integer(),
        ]);

        $this->addForeignKey('b_user_info_fk',
            '{{%bot_user_info}}',
            'bot_user_id',
            '{{%bot_users}}',
            'id');
        $this->addForeignKey('b_bot_user_fk',
            '{{%bot_user_info}}',
            'user_info_id',
            '{{%user_info}}',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%user_info}}', 'bot_user_id', $this->integer());

        $this->addForeignKey('bot_user_fk',
            '{{%user_info}}',
            'bot_user_id',
            '{{%bot_users}}',
            'id');

        $this->dropForeignKey('b_user_info_fk',
            '{{%bot_user_info}}');
        $this->dropForeignKey('b_bot_user_fk',
            '{{%bot_user_info}}');

        $this->dropTable('{{%bot_user_info}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190223_122947_alter_user_info_table cannot be reverted.\n";

        return false;
    }
    */
}
