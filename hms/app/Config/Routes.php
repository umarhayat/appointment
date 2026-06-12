<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes
$routes->get('/', 'Home::index');

// Auth routes
$routes->group('auth', ['namespace' => 'App\Modules\Auth\Controllers'], function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('select-tenant', 'AuthController::selectTenant');
    $routes->post('set-tenant/(:num)', 'AuthController::setTenant/$1');
    $routes->get('logout', 'AuthController::logout');
});

// Protected routes with tenant filter
$routes->group('', ['filter' => 'tenant'], function($routes) {
    
    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');
    
    // Patient module
    $routes->group('patients', ['namespace' => 'App\Modules\Patient\Controllers'], function($routes) {
        $routes->get('/', 'PatientController::index');
        $routes->get('new', 'PatientController::create');
        $routes->post('store', 'PatientController::store');
        $routes->get('edit/(:num)', 'PatientController::edit/$1');
        $routes->post('update/(:num)', 'PatientController::update/$1');
        $routes->get('view/(:num)', 'PatientController::show/$1');
    });
    
    // Doctor module
    $routes->group('doctors', ['namespace' => 'App\Modules\Doctor\Controllers'], function($routes) {
        $routes->get('/', 'DoctorController::index');
        $routes->get('new', 'DoctorController::create');
        $routes->post('store', 'DoctorController::store');
        $routes->get('edit/(:num)', 'DoctorController::edit/$1');
        $routes->post('update/(:num)', 'DoctorController::update/$1');
        $routes->get('schedule/(:num)', 'DoctorController::schedule/$1');
    });
    
    // OPD/Visits module
    $routes->group('opd', ['namespace' => 'App\Modules\OPD\Controllers'], function($routes) {
        $routes->get('/', 'OPDController::index');
        $routes->get('new', 'OPDController::create');
        $routes->post('store', 'OPDController::store');
        $routes->get('visit/(:num)', 'OPDController::show/$1');
        $routes->post('consultation/(:num)', 'OPDController::consultation/$1');
    });
    
    // Lab module
    $routes->group('lab', ['namespace' => 'App\Modules\Lab\Controllers'], function($routes) {
        $routes->get('/', 'LabController::index');
        $routes->get('orders', 'LabController::orders');
        $routes->post('order/create', 'LabController::createOrder');
        $routes->get('results/(:num)', 'LabController::results/$1');
        $routes->post('results/save', 'LabController::saveResults');
    });
    
    // Radiology module
    $routes->group('radiology', ['namespace' => 'App\Modules\Radiology\Controllers'], function($routes) {
        $routes->get('/', 'RadiologyController::index');
        $routes->get('orders', 'RadiologyController::orders');
        $routes->post('order/create', 'RadiologyController::createOrder');
        $routes->post('upload/(:num)', 'RadiologyController::upload/$1');
        $routes->post('report/(:num)', 'RadiologyController::saveReport/$1');
    });
    
    // ECG module
    $routes->group('ecg', ['namespace' => 'App\Modules\ECG\Controllers'], function($routes) {
        $routes->get('/', 'ECGController::index');
        $routes->get('new', 'ECGController::create');
        $routes->post('store', 'ECGController::store');
        $routes->get('view/(:num)', 'ECGController::show/$1');
    });
    
    // Pharmacy module
    $routes->group('pharmacy', ['namespace' => 'App\Modules\Pharmacy\Controllers'], function($routes) {
        $routes->get('/', 'PharmacyController::index');
        $routes->get('inventory', 'PharmacyController::inventory');
        $routes->get('medicines', 'PharmacyController::medicines');
        $routes->post('dispense', 'PharmacyController::dispense');
    });
    
    // Billing module
    $routes->group('billing', ['namespace' => 'App\Modules\Billing\Controllers'], function($routes) {
        $routes->get('/', 'BillingController::index');
        $routes->get('invoices', 'BillingController::invoices');
        $routes->get('invoice/new', 'BillingController::createInvoice');
        $routes->post('invoice/store', 'BillingController::storeInvoice');
        $routes->get('payments', 'BillingController::payments');
        $routes->post('payment/store', 'BillingController::storePayment');
    });
    
    // Devices module
    $routes->group('devices', ['namespace' => 'App\Modules\Devices\Controllers'], function($routes) {
        $routes->get('/', 'DeviceController::index');
        $routes->get('register', 'DeviceController::create');
        $routes->post('store', 'DeviceController::store');
        $routes->get('monitor', 'DeviceController::monitor');
    });
    
    // AI module (for manual job management)
    $routes->group('ai', ['namespace' => 'App\Modules\AI\Controllers'], function($routes) {
        $routes->get('jobs', 'AiController::jobs');
        $routes->post('analyze/ecg/(:num)', 'AiController::analyzeECG/$1');
        $routes->post('analyze/xray/(:num)', 'AiController::analyzeXRay/$1');
    });
    
    // Analytics module
    $routes->group('analytics', ['namespace' => 'App\Modules\Analytics\Controllers'], function($routes) {
        $routes->get('/', 'AnalyticsController::index');
        $routes->get('revenue', 'AnalyticsController::revenue');
        $routes->get('patients', 'AnalyticsController::patients');
        $routes->get('doctors', 'AnalyticsController::doctors');
    });
});

// API routes for mobile apps and external systems
$routes->group('api/v1', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->post('auth/login', 'AuthApiController::login');
    $routes->group('', ['filter' => 'apiAuth'], function($routes) {
        $routes->get('patients', 'PatientApiController::index');
        $routes->get('patients/(:num)', 'PatientApiController::show/$1');
        $routes->get('doctors', 'DoctorApiController::index');
        $routes->get('visits', 'VisitApiController::index');
        $routes->get('lab/orders', 'LabApiController::orders');
        $routes->get('lab/results/(:num)', 'LabApiController::results/$1');
    });
});

// HL7/FHIR endpoints
$routes->group('hl7', ['namespace' => 'App\Controllers\Integration'], function($routes) {
    $routes->post('receive', 'Hl7Controller::receive');
});

$routes->group('fhir', ['namespace' => 'App\Controllers\Integration'], function($routes) {
    $routes->get('Patient/(:num)', 'FhirController::getPatient/$1');
    $routes->post('Patient', 'FhirController::createPatient');
    $routes->get('Observation', 'FhirController::getObservations');
});
