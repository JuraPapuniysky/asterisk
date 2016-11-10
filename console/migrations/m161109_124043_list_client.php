<?php

use yii\db\Migration;

class m161109_124043_list_client extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%clients}}',[
            'id' => $this->primaryKey(),
            'name' => $this->char(255),
            'channel' => $this->char(255),
            'conference' => $this->char(15),
            'mutte' => $this->char(5),
            'callerid' => $this->char(15),
            'video' => $this->char(5),
        ], $tableOptions);

        $this->createTable('{{%list}}', [
            'id' => $this->primaryKey(),
            'name' => $this->char(10),
        ], $tableOptions);

        $this->createTable('{{%list_client}}',[
           'id' => $this->primaryKey(),
            'list_id' => $this->integer(10),
            'client_id' => $this->integer(10),
        ]);

        $this->createIndex('FK_list_list', '{{%list_client}}', 'list_id');
        $this->addForeignKey('FK_list_list', '{{%list_client}}', 'list_id', '{{%list}}', 'id');
        $this->createIndex('FK_list_client', '{{%list_client}}', 'client_id');
        $this->addForeignKey('FK_list_client', '{{%list_client}}', 'client_id', '{{%clients}}', 'id');

    }


    public function down()
    {
        echo "m161109_124043_list_client cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
