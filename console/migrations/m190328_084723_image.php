<?php

use yii\db\Migration;

/**
 * Class m190328_084723_image
 */
class m190328_084723_image extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {

        $this->createTable('image', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull(),
            'object' => $this->integer()->notNull(),
            'filename' => $this->string(),
            'priority' => $this->integer()->notNull(),
            'format' => $this->string()->notNull(),
            'size' => $this->integer()->notNull(),
            'width' => $this->integer()->notNull(),
            'height' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('image');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190328_084723_image cannot be reverted.\n";

        return false;
    }
    */
}
