<?php

use yii\db\Migration;

/**
 * Class m190328_081355_article
 */
class m190328_081355_article extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'slug' => $this->string()->unique(),
            'status' => $this->integer()->notNull(),
            'priority' => $this->integer()->notNull(),
            'view_on_main' => $this->integer()->notNull(),
            'lead' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'published_at' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        echo "m190328_081355_article cannot be reverted.\n";
        $this->dropTable('article');

//        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190328_081355_article cannot be reverted.\n";

        return false;
    }
    */
}
