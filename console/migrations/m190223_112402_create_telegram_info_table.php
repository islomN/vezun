<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram_info}}`.
 */
class m190223_112402_create_telegram_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%telegram_info}}', [
            'token' => $this->string(),
            'name' => $this->string(),
        ]);

        $this->addPrimaryKey('telegram_pk', '{{%telegram_info}}', 'token');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('telegram_pk', '{{%telegram_info}}');
        $this->dropTable('{{%telegram_info}}');
    }
}
