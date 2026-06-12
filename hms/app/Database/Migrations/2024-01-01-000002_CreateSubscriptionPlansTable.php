<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Create Subscription Plans Table
 */
class CreateSubscriptionPlansTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
                'null'       => false,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'price_monthly' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'price_yearly' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'currency' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => 'USD',
            ],
            'max_users' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 10,
            ],
            'max_beds' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 100,
            ],
            'max_storage_gb' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 10,
            ],
            'features' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'List of included features',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'trial_days' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 14,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('slug');
        $this->forge->addKey('is_active');

        $this->forge->createTable('subscription_plans');
    }

    public function down()
    {
        $this->forge->dropTable('subscription_plans');
    }
}
