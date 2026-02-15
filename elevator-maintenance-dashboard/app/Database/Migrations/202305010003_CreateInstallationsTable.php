<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInstallationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'project_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'building_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'elevator_count' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'contractor' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'installation_manager' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'estimated_completion' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'actual_completion' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['planning', 'in_progress', 'completed', 'delayed', 'cancelled'],
                'default' => 'planning',
            ],
            'budget' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
            ],
            'actual_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->createTable('installations');
    }

    public function down()
    {
        $this->forge->dropTable('installations');
    }
}