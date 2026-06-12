<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Create ECG, Devices, and Pharmacy Tables
 */
class CreateECGDevicesPharmacyTables extends Migration
{
    public function up()
    {
        // ECG Records
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'ecg_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true, 'null' => false],
            'patient_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'visit_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'device_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'ecg_date' => ['type' => 'DATETIME', 'null' => false],
            'heart_rate' => ['type' => 'INT', 'constraint' => 3, 'null' => true],
            'rhythm' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'pr_interval' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'qrs_duration' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'qt_interval' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'axis' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'raw_data' => ['type' => 'LONGTEXT', 'null' => true, 'comment' => 'ECG waveform data'],
            'pdf_path' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'ai_analyzed' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'ai_result' => ['type' => 'JSON', 'null' => true],
            'interpreted_by' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'interpretation' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('patient_id');
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ecg_records');

        // Medical Devices
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'device_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'device_type' => ['type' => 'ENUM', 'constraint' => ['ECG', 'Ventilator', 'Infusion Pump', 'Monitor', 'X-Ray', 'CT', 'MRI', 'Other'], 'null' => false],
            'manufacturer' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'model_number' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'serial_number' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'mac_address' => ['type' => 'VARCHAR', 'constraint' => 17, 'null' => true],
            'location' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['Active', 'Inactive', 'Maintenance', 'Offline'], 'default' => 'Active'],
            'last_communication' => ['type' => 'DATETIME', 'null' => true],
            'firmware_version' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'calibration_due_date' => ['type' => 'DATE', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('device_type');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('medical_devices');

        // Device Data Queue
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'device_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'patient_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'data_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'raw_data' => ['type' => 'LONGTEXT', 'null' => true],
            'processed' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'ai_processed' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'error_message' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'processed_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id', 'processed']);
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('device_data_queue');

        // Pharmacy - Medicines Master
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'medicine_code' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'generic_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'category' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'form' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'comment' => 'Tablet, Capsule, Syrup, Injection'],
            'strength' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'manufacturer' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'unit_price' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'requires_prescription' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id', 'medicine_code']);
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('medicines');

        // Pharmacy Inventory
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'medicine_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'batch_number' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'quantity' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'unit' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'PCS'],
            'expiry_date' => ['type' => 'DATE', 'null' => true],
            'purchase_price' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'selling_price' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'reorder_level' => ['type' => 'INT', 'constraint' => 11, 'default' => 10],
            'location' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id', 'medicine_id']);
        $this->forge->addKey('expiry_date');
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('medicine_id', 'medicines', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('medicine_inventory');
    }

    public function down()
    {
        $this->forge->dropTable('medicine_inventory');
        $this->forge->dropTable('medicines');
        $this->forge->dropTable('device_data_queue');
        $this->forge->dropTable('medical_devices');
        $this->forge->dropTable('ecg_records');
    }
}
