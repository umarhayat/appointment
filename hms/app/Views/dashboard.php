<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; background: #2c3e50; }
        .sidebar .nav-link { color: #ecf0f1; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #34495e; color: #3498db; }
        .sidebar .nav-link i { margin-right: 10px; }
        .main-content { background: #f8f9fa; }
        .stat-card { border-radius: 10px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stat-card .icon { font-size: 2.5rem; opacity: 0.3; }
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
        .bg-gradient-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
        .bg-gradient-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
        .bg-gradient-danger { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar p-0">
            <div class="p-3 text-white bg-dark">
                <h4><i class="bi bi-hospital"></i> HMS</h4>
                <small><?= session('tenant_name') ?? 'Hospital' ?></small>
            </div>
            <ul class="nav flex-column mt-3">
                <li class="nav-item"><a class="nav-link active" href="<?= site_url('dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('patients') ?>"><i class="bi bi-people"></i> Patients</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('doctors') ?>"><i class="bi bi-person-badge"></i> Doctors</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('opd') ?>"><i class="bi bi-calendar-check"></i> OPD</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('lab') ?>"><i class="bi bi-microscope"></i> Laboratory</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('radiology') ?>"><i class="bi bi-xray"></i> Radiology</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('ecg') ?>"><i class="bi bi-heart-pulse"></i> ECG</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('pharmacy') ?>"><i class="bi bi-capsule"></i> Pharmacy</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('billing') ?>"><i class="bi bi-receipt"></i> Billing</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('devices') ?>"><i class="bi bi-hdd-network"></i> Devices</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('analytics') ?>"><i class="bi bi-graph-up"></i> Analytics</a></li>
                <li class="nav-item mt-4"><a class="nav-link" href="<?= site_url('auth/logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto main-content px-4 py-4">
            <!-- Top Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
                <div>
                    <span class="me-3"><i class="bi bi-person-circle"></i> <?= session('name') ?? 'User' ?></span>
                    <span class="badge bg-primary"><?= date('Y-m-d') ?></span>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-2">
                    <div class="card stat-card bg-gradient-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Patients</h6>
                                    <h3 class="mb-0"><?= $stats['total_patients'] ?? 0 ?></h3>
                                </div>
                                <i class="bi bi-people icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card stat-card bg-gradient-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Revenue Today</h6>
                                    <h3 class="mb-0">$<?= number_format($stats['revenue_today'] ?? 0, 2) ?></h3>
                                </div>
                                <i class="bi bi-currency-dollar icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card stat-card bg-gradient-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Lab Orders</h6>
                                    <h3 class="mb-0"><?= $stats['lab_orders'] ?? 0 ?></h3>
                                </div>
                                <i class="bi bi-microscope icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card stat-card bg-gradient-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Radiology Scans</h6>
                                    <h3 class="mb-0"><?= $stats['radiology_scans'] ?? 0 ?></h3>
                                </div>
                                <i class="bi bi-xray icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card stat-card bg-gradient-danger">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">High Risk (AI)</h6>
                                    <h3 class="mb-0"><?= $stats['high_risk_patients'] ?? 0 ?></h3>
                                </div>
                                <i class="bi bi-exclamation-triangle icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card stat-card bg-dark text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">OPD Visits</h6>
                                    <h3 class="mb-0"><?= $stats['opd_visits'] ?? 0 ?></h3>
                                </div>
                                <i class="bi bi-calendar-check icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Patient Trends (Last 7 Days)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="patientChart" height="80"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Department Distribution</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="deptChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Activities</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (($recent_activities ?? []) as $activity): ?>
                            <tr>
                                <td><?= esc($activity['created_at']) ?></td>
                                <td><?= esc($activity['user_name'] ?? 'System') ?></td>
                                <td><?= esc($activity['action']) ?></td>
                                <td><span class="badge bg-secondary"><?= esc($activity['module']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Patient Trend Chart
const patientCtx = document.getElementById('patientChart').getContext('2d');
new Chart(patientCtx, {
    type: 'line',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Patients',
            data: [45, 52, 38, 65, 48, 72, 55],
            borderColor: '#667eea',
            tension: 0.4,
            fill: true,
            backgroundColor: 'rgba(102, 126, 234, 0.1)'
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

// Department Chart
const deptCtx = document.getElementById('deptChart').getContext('2d');
new Chart(deptCtx, {
    type: 'doughnut',
    data: {
        labels: ['OPD', 'IPD', 'Emergency', 'ICU'],
        datasets: [{
            data: [45, 25, 20, 10],
            backgroundColor: ['#667eea', '#11998e', '#f093fb', '#fa709a']
        }]
    },
    options: { responsive: true, cutout: '60%' }
});
</script>
</body>
</html>
