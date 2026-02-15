<?php

namespace App\Models;

use CodeIgniter\Model;

class InstallationModel extends Model
{
    protected $table = 'installations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'project_name', 'building_name', 'location', 'elevator_count',
        'contractor', 'installation_manager', 'start_date', 'estimated_completion',
        'actual_completion', 'status', 'budget', 'actual_cost', 'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get scheduled installations
     */
    public function getScheduledInstallations()
    {
        return $this->whereIn('status', ['planning', 'in_progress'])->countAllResults();
    }

    /**
     * Get installations by status
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }

    /**
     * Get recent installations
     */
    public function getRecentInstallations($limit = 5)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get overdue installations
     */
    public function getOverdueInstallations()
    {
        return $this->where('estimated_completion <', date('Y-m-d'))
                    ->where('status !=', 'completed')
                    ->findAll();
    }
}