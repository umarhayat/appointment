<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            font-size: 2em;
            margin-bottom: 10px;
            color: #667eea;
        }

        .stat-card p {
            color: #666;
            font-size: 1.1em;
        }

        .section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .section h2 {
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background-color: #5a6fd8;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }

        .status-completed {
            color: #28a745;
            font-weight: bold;
        }

        .status-scheduled {
            color: #17a2b8;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <h1><i class="fas fa-building"></i> Elevator Management Dashboard</h1>
            <p>Welcome to the elevator installation and maintenance management system</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3><i class="fas fa-elevator"></i> <?= $total_elevators ?></h3>
                <p>Total Elevators</p>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-tools"></i> <?= $pending_maintenance ?></h3>
                <p>Pending Maintenance</p>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-calendar-check"></i> <?= $scheduled_installations ?></h3>
                <p>Scheduled Installations</p>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-check-circle"></i> 138</h3>
                <p>Maintenance Completed</p>
            </div>
        </div>

        <div class="section">
            <h2><i class="fas fa-list"></i> Recent Activities</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Activity</th>
                        <th>Date</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_activities as $activity): ?>
                    <tr>
                        <td><?= $activity['id'] ?></td>
                        <td><?= esc($activity['activity']) ?></td>
                        <td><?= substr($activity['date'], 0, 10) ?></td>
                        <td>
                            <?php if($activity['type'] === 'maintenance'): ?>
                                <span class="status-pending">Maintenance</span>
                            <?php elseif($activity['type'] === 'installation'): ?>
                                <span class="status-scheduled">Installation</span>
                            <?php else: ?>
                                <span class="status-completed">Other</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2><i class="fas fa-tasks"></i> Quick Actions</h2>
            <div class="actions">
                <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Elevator</a>
                <a href="#" class="btn btn-success"><i class="fas fa-calendar-plus"></i> Schedule Maintenance</a>
                <a href="#" class="btn btn-warning"><i class="fas fa-file-invoice"></i> Create Work Order</a>
            </div>
        </div>

        <div class="section">
            <h2><i class="fas fa-chart-bar"></i> Maintenance Overview</h2>
            <table>
                <thead>
                    <tr>
                        <th>Elevator ID</th>
                        <th>Location</th>
                        <th>Last Service</th>
                        <th>Next Service</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ELV-001</td>
                        <td>Building A, Floor 5</td>
                        <td>2023-05-01</td>
                        <td>2023-06-01</td>
                        <td><span class="status-scheduled">Scheduled</span></td>
                        <td>
                            <button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>ELV-002</td>
                        <td>Building B, Floor 3</td>
                        <td>2023-04-15</td>
                        <td>2023-05-15</td>
                        <td><span class="status-pending">Pending</span></td>
                        <td>
                            <button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>ELV-003</td>
                        <td>Building C, Floor 7</td>
                        <td>2023-05-10</td>
                        <td>2023-06-10</td>
                        <td><span class="status-completed">Completed</span></td>
                        <td>
                            <button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add interactivity to the dashboard
            $('.stat-card').on('click', function() {
                alert('View details for this metric');
            });
        });
    </script>
</body>
</html>