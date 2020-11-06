<?php

use yii\db\Migration;

/**
 * Class m201014_170154_add_tests
 */
class m201014_170154_add_tests extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tests', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable('test_items', [
            'id' => $this->primaryKey(),
            'test_id' => $this->integer()->notNull(),
            'phrase_id' => $this->integer()->notNull(),
            'answer' => $this->string(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'type' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey('fk_test_test_item_test_id', 'test_items', 'test_id', 'tests', 'id');
        $this->addForeignKey('fk_vocabulary_test_item_phrase_id', 'test_items', 'phrase_id', 'vocabulary', 'id');
        $this->createIndex('idx_test_items_test_id', 'test_items', 'test_id');
        $this->createIndex('idx_test_items_phrase_id', 'test_items', 'phrase_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_vocabulary_test_item_phrase_id', 'test_items');
        $this->dropForeignKey('fk_test_test_item_test_id', 'test_items');
        $this->dropTable('test_items');
        $this->dropTable('tests');

    }
}
