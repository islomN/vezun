<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%letter}}`.
 */
class m190303_100603_create_letter_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%letter}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string()->unique(),
            'word_ru' => $this->text(),
            'word_oz' => $this->text(),
            'word_uz' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%letter}}');
    }
}
