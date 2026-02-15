<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ElevatorModel;
use App\Models\MaintenanceRecordModel;
use App\Models\InstallationModel;

class Dashboard extends BaseController
{
    private $elevatorModel;
    private $maintenanceModel;
    private $installationModel;

    public function __construct()
    {
        $this->elevatorModel = new ElevatorModel();
        $this->maintenanceModel = new MaintenanceRecordModel();
        $this->installationModel = new InstallationModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Elevator Management Dashboard',
            'total_elevators' => $this->elevatorModel->getTotalElevators(),
            'pending_maintenance' => $this->maintenanceModel->getPendingMaintenance(),
            'scheduled_installations' => $this->installationModel->getScheduledInstallations(),
            'recent_activities' => $this->getRecentActivities()
        ];

        return view('admin/dashboard', $data);
    }

    private function getRecentActivities()
    {
        // Get recent maintenance activities
        $recentMaintenances = $this->maintenanceModel->getRecentActivities(3);
        $recentActivities = [];
        
        foreach ($recentMaintenances as $maintenance) {
            $recentActivities[] = [
                'id' => $maintenance['id'],
                'activity' => 'Maintenance: ' . $maintenance['maintenance_type'],
                'date' => $maintenance['service_date'],
                'type' => 'maintenance'
            ];
        }
        
        // Get recent installations
        $recentInstallations = $this->installationModel->getRecentInstallations(3);
        
        foreach ($recentInstallations as $installation) {
            $recentActivities[] = [
                'id' => $installation['id'],
                'activity' => 'Installation: ' . $installation['project_name'],
                'date' => $installation['created_at'],
                'type' => 'installation'
            ];
        }
        
        // Sort by date (most recent first) and limit to 5
        usort($recentActivities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return array_slice($recentActivities, 0, 5);
    }
}