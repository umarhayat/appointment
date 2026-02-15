<?php

namespace App\Models;

use CodeIgniter\Model;

class MaintenanceRecordModel extends Model
{
    protected $table = 'maintenance_records';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'elevator_id', 'maintenance_type', 'description', 'performed_by', 
        'cost', 'service_date', 'next_service_date', 'status', 'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get pending maintenance records
     */
    public function getPendingMaintenance()
    {
        return $this->where('status', 'pending')->orWhere('status', 'scheduled')->countAllResults();
    }

    /**
     * Get maintenance records by elevator ID
     */
    public function getByElevatorId($elevatorId)
    {
        return $this->where('elevator_id', $elevatorId)->findAll();
    }

    /**
     * Get maintenance records by status
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }

    /**
     * Get maintenance records due for service
     */
    public function getDueForService()
    {
        return $this->where('next_service_date <=', date('Y-m-d'))
                    ->where('status !=', 'completed')
                    ->findAll();
    }

    /**
     * Get recent maintenance activities
     */
    public function getRecentActivities($limit = 5)
    {
        return $this->orderBy('service_date', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}