<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Create Patient Visits Table (OPD/IPD/Emergency)
 */
class CreateVisitsTable extends Migration
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
            'tenant_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'visit_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
                'null'       => false,
            ],
            'patient_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'doctor_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'visit_type' => [
                'type'       => 'ENUM',
                'constraint' => ['OPD', 'IPD', 'Emergency', 'Follow-up'],
                'default'    => 'OPD',
            ],
            'visit_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'chief_complaints' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'diagnosis' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Primary diagnosis (ICD-10 codes)',
            ],
            'secondary_diagnosis' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'vitals' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'BP, Temperature, Pulse, Respiration, SpO2, Weight, Height',
            ],
            'notes' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'prescription' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'Prescribed medications',
            ],
            'lab_orders' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'Ordered lab tests',
            ],
            'radiology_orders' => [
                'type'       => 'JSON',
                'null'       => true,
                'comment'    => 'Ordered radiology scans',
            ],
            'admission_status' => [
                'type'       => 'ENUM',
                'constraint' => ['Outpatient', 'Admitted', 'Discharged', 'Referred', 'Left AMA'],
                'default'    => 'Outpatient',
            ],
            'referred_to_doctor_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'referral_notes' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'follow_up_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Registered', 'In-Progress', 'Completed', 'Cancelled'],
                'default'    => 'Registered',
            ],
            'created_by' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
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
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('visit_number');
        $this->forge->addKey('patient_id');
        $this->forge->addKey('doctor_id');
        $this->forge->addKey('visit_type');
        $this->forge->addKey('visit_date');
        $this->forge->addKey(['tenant_id', 'visit_date'], false, 'idx_tenant_visit_date');

        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('visits');
    }

    public function down()
    {
        $this->forge->dropTable('visits');
    }
}
