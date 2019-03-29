<?php

use yii\db\Migration;

/**
 * Class m190328_084808_content
 */
class m190328_084808_content extends Migration
{
    public function safeUp()
    {
        $this->createTable('content', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull(),
            'object' => $this->integer()->notNull(),
            'lang' => $this->string()->notNull(),
            'value' => $this->text(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('content');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190328_084808_content cannot be reverted.\n";

        return false;
    }
    */
}
