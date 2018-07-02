<?php

use yii\db\Migration;

/**
 * Class m180630_130527_add_date_to_comment
 */
class m180630_130527_add_date_to_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    // public function safeUp()
    // {
    //
    // }

    /**
     * {@inheritdoc}
     */
    // public function safeDown()
    // {
    //     echo "m180630_130527_add_date_to_comment cannot be reverted.\n";
    //
    //     return false;
    // }

    /*
    // Use up()/down() to run migration code without a transaction.*/
    public function up()
    {
      $this->addColumn('comment', 'data', $this->date());
    }

    public function down()
    {
        $this->dropColumn('comment');
    }

}
