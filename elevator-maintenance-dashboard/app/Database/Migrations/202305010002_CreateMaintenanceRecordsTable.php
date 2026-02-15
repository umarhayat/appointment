<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMaintenanceRecordsTable extends Migration
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
            'elevator_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'maintenance_type' => [
                'type' => 'ENUM',
                'constraint' => ['routine_check', 'repair', 'upgrade', 'emergency'],
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'performed_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'service_date' => [
                'type' => 'DATE',
            ],
            'next_service_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['completed', 'pending', 'in_progress', 'scheduled'],
                'default' => 'pending',
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
        $this->forge->addForeignKey('elevator_id', 'elevators', 'elevator_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('maintenance_records');
    }

    public function down()
    {
        $this->forge->dropTable('maintenance_records');
    }
}