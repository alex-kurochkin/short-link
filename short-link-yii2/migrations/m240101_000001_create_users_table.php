<?php

use yii\db\Migration;

/**
 * Migration for creating users table
 */
class m240101_000001_create_users_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'email_verified_at' => $this->timestamp()->null(),
            'password' => $this->string()->notNull(),
            'remember_token' => $this->string(100)->null(),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);

        $this->createIndex('idx-users-email', 'users', 'email');
    }

    public function safeDown()
    {
        $this->dropTable('users');
    }
}
