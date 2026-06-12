<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Create Tenants Table
 * Multi-tenant SaaS architecture - each tenant is a hospital
 */
class CreateTenantsTable extends Migration
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
                'constraint' => 255,
                'null'       => false,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
                'null'       => false,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'address' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'state' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'country' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'India',
            ],
            'postal_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'timezone' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'UTC',
            ],
            'subscription_plan_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'subscription_status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'suspended', 'cancelled', 'expired'],
                'default'    => 'active',
            ],
            'subscription_start_date' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'subscription_end_date' => [
                'type'       => 'DATETIME',
                'null'       => true,
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
            'features' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'Enabled features as JSON array',
            ],
            'settings' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'Tenant-specific settings',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=active, 0=inactive',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('slug');
        $this->forge->addKey('is_active');
        $this->forge->addKey('created_at');

        $this->forge->createTable('tenants');
    }

    public function down()
    {
        $this->forge->dropTable('tenants');
    }
}
