<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Create Radiology Module Tables (X-ray, CT, MRI)
 */
class CreateRadiologyTables extends Migration
{
    public function up()
    {
        // Radiology Tests Master
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'test_code' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'test_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'modality' => ['type' => 'ENUM', 'constraint' => ['X-Ray', 'CT', 'MRI', 'Ultrasound', 'PET', 'Mammography'], 'null' => false],
            'body_part' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'price' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id', 'test_code']);
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('radiology_tests');

        // Radiology Orders
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'order_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true, 'null' => false],
            'visit_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'patient_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'doctor_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'test_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'clinical_indication' => ['type' => 'TEXT', 'null' => true],
            'priority' => ['type' => 'ENUM', 'constraint' => ['Routine', 'Urgent', 'STAT'], 'default' => 'Routine'],
            'status' => ['type' => 'ENUM', 'constraint' => ['Pending', 'In Progress', 'Completed', 'Reported', 'Cancelled'], 'default' => 'Pending'],
            'images_path' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true, 'comment' => 'Path to DICOM/images'],
            'dicom_study_uid' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'ai_analyzed' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'ai_result' => ['type' => 'JSON', 'null' => true],
            'report' => ['type' => 'TEXT', 'null' => true],
            'reported_by' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'reported_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('order_number');
        $this->forge->addKey('patient_id');
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('visit_id', 'visits', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('radiology_orders');
    }

    public function down()
    {
        $this->forge->dropTable('radiology_orders');
        $this->forge->dropTable('radiology_tests');
    }
}
