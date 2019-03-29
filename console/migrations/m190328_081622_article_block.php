<?php

use yii\db\Migration;

/**
 * Class m190328_081622_article_block
 */
class m190328_081622_article_block extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('article_block', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'type_id' => $this->integer()->notNull(),
            'priority' => $this->integer()->notNull()
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article_block');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190328_081622_article_block cannot be reverted.\n";

        return false;
    }
    */
}
