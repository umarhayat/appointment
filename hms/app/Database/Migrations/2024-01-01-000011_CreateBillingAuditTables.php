<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Create Billing, Audit Logs, AI Jobs, and HL7/FHIR Tables
 */
class CreateBillingAuditTables extends Migration
{
    public function up()
    {
        // Invoices
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'invoice_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true, 'null' => false],
            'patient_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'visit_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'invoice_date' => ['type' => 'DATETIME', 'null' => false],
            'due_date' => ['type' => 'DATE', 'null' => true],
            'subtotal' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'discount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'tax' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'total_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'paid_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'balance' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'status' => ['type' => 'ENUM', 'constraint' => ['Draft', 'Sent', 'Paid', 'Partial', 'Overdue', 'Cancelled'], 'default' => 'Draft'],
            'insurance_provider' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'insurance_claim_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'items' => ['type' => 'JSON', 'null' => true, 'comment' => 'Invoice line items'],
            'created_by' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('invoice_number');
        $this->forge->addKey('patient_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('invoices');

        // Payments
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'invoice_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'payment_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true, 'null' => false],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false],
            'payment_method' => ['type' => 'ENUM', 'constraint' => ['Cash', 'Card', 'UPI', 'Net Banking', 'Cheque', 'Insurance'], 'default' => 'Cash'],
            'transaction_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'payment_date' => ['type' => 'DATETIME', 'null' => false],
            'received_by' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('invoice_id');
        $this->forge->addForeignKey('tenant_id', 'tenants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('invoice_id', 'invoices', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments');

        // Audit Logs
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'user_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'action' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'module' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'entity_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'entity_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'old_values' => ['type' => 'JSON', 'null' => true],
            'new_values' => ['type' => 'JSON', 'null' => true],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'user_agent' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('action');
        $this->forge->addKey('created_at');
        $this->forge->createTable('audit_logs');

        // AI Jobs Queue
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'job_type' => ['type' => 'ENUM', 'constraint' => ['ECG Analysis', 'X-Ray Analysis', 'CT Analysis', 'MRI Analysis', 'Lab Analysis'], 'null' => false],
            'entity_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'entity_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'input_data' => ['type' => 'JSON', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['Pending', 'Processing', 'Completed', 'Failed'], 'default' => 'Pending'],
            'result' => ['type' => 'JSON', 'null' => true],
            'error_message' => ['type' => 'TEXT', 'null' => true],
            'retry_count' => ['type' => 'INT', 'constraint' => 3, 'default' => 0],
            'priority' => ['type' => 'INT', 'constraint' => 3, 'default' => 5],
            'scheduled_at' => ['type' => 'DATETIME', 'null' => true],
            'started_at' => ['type' => 'DATETIME', 'null' => true],
            'completed_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['tenant_id', 'status']);
        $this->forge->addKey('job_type');
        $this->forge->addKey('priority');
        $this->forge->createTable('ai_jobs');

        // HL7/FHIR Messages
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'tenant_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'message_type' => ['type' => 'ENUM', 'constraint' => ['HL7v2', 'FHIR'], 'null' => false],
            'direction' => ['type' => 'ENUM', 'constraint' => ['Inbound', 'Outbound'], 'null' => false],
            'event_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'comment' => 'e.g., ADT, ORU, FHIR Resource Type'],
            'source_system' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'payload' => ['type' => 'LONGTEXT', 'null' => true],
            'processed' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'error_message' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'processed_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey(['message_type', 'direction']);
        $this->forge->addKey('processed');
        $this->forge->createTable('hl7_fhir_messages');
    }

    public function down()
    {
        $this->forge->dropTable('hl7_fhir_messages');
        $this->forge->dropTable('ai_jobs');
        $this->forge->dropTable('audit_logs');
        $this->forge->dropTable('payments');
        $this->forge->dropTable('invoices');
    }
}
