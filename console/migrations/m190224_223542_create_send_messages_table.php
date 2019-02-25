<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%send_messages}}`.
 */
class m190224_223542_create_send_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%messages}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'message' => $this->text(),
            'sended_at' => $this->timestamp(),

        ]);

        $this->createTable('{{%send_messages}}', [
            'id' => $this->primaryKey(),
            'message_id' => $this->integer(),
            'bot_user_id' => $this->integer(),

        ]);

        $this->addForeignKey('message_fk', '{{%send_messages}}', 'message_id', '{{%messages}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('message_fk', '{{%send_messages}}');
        $this->dropTable('{{%messages}}');
        $this->dropTable('{{%send_messages}}');
    }
}
