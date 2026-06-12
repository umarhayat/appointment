<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Create Lab Module Tables
 */
class CreateLabTables extends Migration
{
    public function up()
    {
        // Lab Tests Master
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'test_code' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'test_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'category' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'comment' => 'e.g., Hematology, Biochemistry'],
            'description' => ['type' => 'TEXT', 'null' => true],
            'sample_type' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'comment' => 'Blood, Urine, etc.'],
            'turnaround_time_hours' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'price' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id', 'test_code'], false, 'idx_tenant_test');
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lab_tests');

        // Lab Orders
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'order_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true, 'null' => false],
            'visit_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'patient_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'doctor_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'tests' => ['type' => 'JSON', 'null' => false, 'comment' => 'Array of test IDs with details'],
            'priority' => ['type' => 'ENUM', 'constraint' => ['Routine', 'Urgent', 'STAT'], 'default' => 'Routine'],
            'status' => ['type' => 'ENUM', 'constraint' => ['Pending', 'Sample Collected', 'In Progress', 'Completed', 'Cancelled'], 'default' => 'Pending'],
            'collection_date' => ['type' => 'DATETIME', 'null' => true],
            'completed_date' => ['type' => 'DATETIME', 'null' => true],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('order_number');
        $this->forge->addKey('patient_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('visit_id', 'visits', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lab_orders');

        // Lab Results
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'order_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'test_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'result_value' => ['type' => 'TEXT', 'null' => true],
            'result_unit' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'reference_range_min' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'reference_range_max' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'is_abnormal' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'ai_analyzed' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'ai_result' => ['type' => 'JSON', 'null' => true, 'comment' => 'AI analysis result'],
            'verified_by' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'verified_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('order_id');
        $this->forge->addKey('test_id');
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('order_id', 'lab_orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lab_results');
    }

    public function down()
    {
        $this->forge->dropTable('lab_results');
        $this->forge->dropTable('lab_orders');
        $this->forge->dropTable('lab_tests');
    }
}
