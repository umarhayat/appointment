<?php

namespace App\Models;

use CodeIgniter\Model;

class ElevatorModel extends Model
{
    protected $table = 'elevators';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'elevator_id', 'building_name', 'location', 'floor_count', 
        'installation_date', 'manufacturer', 'model', 'capacity_kg', 'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get total number of elevators
     */
    public function getTotalElevators()
    {
        return $this->countAll();
    }

    /**
     * Get elevators by status
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }

    /**
     * Get active elevators
     */
    public function getActiveElevators()
    {
        return $this->where('status', 'active')->countAllResults();
    }

    /**
     * Get elevators under maintenance
     */
    public function getUnderMaintenance()
    {
        return $this->where('status', 'under_maintenance')->countAllResults();
    }
}