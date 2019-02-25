<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_info}}`.
 */
class m190223_062223_create_user_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bot_users}}', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->string()->unique(),
            'name' => $this->string(),
            'phone' => $this->string(),
            'nickname' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'status' => $this->tinyInteger(),
        ]);

        $this->createIndex('bot_user_nickname_i', '{{%bot_users}}', 'nickname');
        $this->createIndex('bot_user_phone_i', '{{%bot_users}}', 'phone');

        $this->createTable('{{%user_info}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'phone' => $this->string(),
            'bot_user_id' => $this->integer(),
        ]);

        $this->addForeignKey('bot_user_fk',
                            '{{%user_info}}',
                            'bot_user_id',
                            '{{%bot_users}}',
                            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('bot_user_fk', '{{%user_info}}');

        $this->dropTable('{{%user_info}}');

//        $this->dropIndex('bot_user_nickname', '{{%bot_users}}');
//        $this->dropIndex('bot_user_phone_i', '{{%bot_users}}');

        $this->dropTable('{{%bot_users}}');
    }
}
