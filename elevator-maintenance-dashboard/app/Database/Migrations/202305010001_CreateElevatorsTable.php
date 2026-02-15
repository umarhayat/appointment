<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateElevatorsTable extends Migration
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
                'unique' => true,
            ],
            'building_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'floor_count' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'installation_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'manufacturer' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'model' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'capacity_kg' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'under_maintenance'],
                'default' => 'active',
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
        $this->forge->createTable('elevators');
    }

    public function down()
    {
        $this->forge->dropTable('elevators');
    }
}