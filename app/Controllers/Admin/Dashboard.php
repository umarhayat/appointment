<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Elevator Management Dashboard',
            'total_elevators' => $this->getTotalElevators(),
            'pending_maintenance' => $this->getPendingMaintenance(),
            'scheduled_installations' => $this->getScheduledInstallations(),
            'recent_activities' => $this->getRecentActivities()
        ];
        
        return view('admin/dashboard', $data);
    }
    
    private function getTotalElevators()
    {
        // Placeholder - implement with actual model logic
        return 150;
    }
    
    private function getPendingMaintenance()
    {
        // Placeholder - implement with actual model logic
        return 12;
    }
    
    private function getScheduledInstallations()
    {
        // Placeholder - implement with actual model logic
        return 5;
    }
    
    private function getRecentActivities()
    {
        // Placeholder - implement with actual model logic
        return [
            ['id' => 1, 'activity' => 'Installation completed', 'date' => '2023-05-15'],
            ['id' => 2, 'activity' => 'Maintenance scheduled', 'date' => '2023-05-14'],
            ['id' => 3, 'activity' => 'New service request', 'date' => '2023-05-13']
        ];
    }
}