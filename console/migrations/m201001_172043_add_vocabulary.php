<?php

use yii\db\Migration;

/**
 * Class m201001_172043_add_vocabulary
 */
class m201001_172043_add_vocabulary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('vocabulary', [
            'id' => $this->primaryKey(),
            'word' => $this->string()->notNull(),
            'translate' => $this->string()->notNull(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('vocabulary');
    }
}
