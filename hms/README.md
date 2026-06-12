# Hospital Management System (HMS)

A production-grade, multi-tenant SaaS Hospital Management System built with CodeIgniter 4.

## Features

### Core Modules
- **Patient Management** - Registration, history, visits, medical records
- **Doctor Management** - Profiles, schedules, assignments, performance stats
- **OPD/IPD/Emergency** - Consultation, prescriptions, diagnosis
- **Laboratory** - Test orders, results entry, AI anomaly detection
- **Radiology** - X-ray, CT, MRI orders, image upload, AI interpretation
- **ECG** - Recording storage, AI analysis integration
- **Pharmacy** - Medicine inventory, stock management, dispensing
- **Billing** - Invoices, payments, insurance support
- **Devices** - Medical device registration, data streams, monitoring
- **AI Integration** - Job queue, Python FastAPI service connection
- **Analytics** - KPIs, revenue, patient flow, doctor performance

### SaaS Multi-Tenant Architecture
- Tenant-based isolation (tenant_id on all tables)
- Subscription plans and billing
- Role-based access control (RBAC)
- Subdomain or session-based tenant selection

### Integration Ready
- **AI Engine** - Connects to Python FastAPI service for ECG, X-Ray, CT, MRI, Lab analysis
- **Device Integration** - Queue-based processing for medical device data
- **HL7/FHIR** - Interoperability layer for healthcare data exchange
- **REST API** - API-ready backend for mobile apps

## Project Structure

```
hms/
├── app/
│   ├── Commands/           # CLI commands (device worker, etc.)
│   ├── Config/             # Configuration files
│   ├── Controllers/        # Base and API controllers
│   ├── Database/
│   │   └── Migrations/     # Database migrations (11 files)
│   ├── Filters/            # Request filters (TenantFilter, etc.)
│   ├── Models/             # Base models
│   ├── Modules/            # Feature-based modules
│   │   ├── Auth/           # Authentication module
│   │   ├── Patient/        # Patient management
│   │   ├── Doctor/         # Doctor management
│   │   ├── OPD/            # OPD/IPD module
│   │   ├── Lab/            # Laboratory module
│   │   ├── Radiology/      # Radiology module
│   │   ├── ECG/            # ECG module
│   │   ├── Pharmacy/       # Pharmacy module
│   │   ├── Billing/        # Billing module
│   │   ├── Devices/        # Device management
│   │   ├── AI/             # AI job management
│   │   └── Analytics/      # Analytics dashboard
│   ├── Services/           # Service layer (AiService, etc.)
│   └── Views/              # View templates
├── public/
│   ├── assets/             # CSS, JS, images
│   └── uploads/            # File uploads
└── writable/               # Writable directories
```

## Database Tables

| Table | Description |
|-------|-------------|
| tenants | Hospital/tenant information |
| subscription_plans | SaaS subscription plans |
| users | User accounts |
| tenant_users | User-tenant mappings with roles |
| roles | RBAC roles |
| permissions | RBAC permissions |
| role_permissions | Role-permission mappings |
| patients | Patient records |
| doctors | Doctor profiles |
| visits | Patient visits (OPD/IPD/Emergency) |
| lab_tests | Lab test master |
| lab_orders | Lab test orders |
| lab_results | Lab test results |
| radiology_tests | Radiology test master |
| radiology_orders | Radiology orders |
| ecg_records | ECG recordings |
| medical_devices | Registered medical devices |
| device_data_queue | Device data processing queue |
| medicines | Medicine master |
| medicine_inventory | Medicine stock |
| invoices | Billing invoices |
| payments | Payment records |
| audit_logs | System audit trail |
| ai_jobs | AI processing queue |
| hl7_fhir_messages | HL7/FHIR message store |

## Installation

### Prerequisites
- PHP 8.0+
- MySQL 8.0+
- Composer
- Node.js (optional, for asset compilation)

### Steps

1. Clone the repository:
```bash
git clone <repository-url> hms
cd hms
```

2. Install dependencies:
```bash
composer install
```

3. Configure environment:
```bash
cp env .env
# Edit .env with your database credentials
```

4. Run migrations:
```bash
php spark migrate
```

5. Create super admin user (seed):
```bash
php spark db:seed SuperAdminSeeder
```

6. Start development server:
```bash
php spark serve
```

## Usage

### Access the Application
- URL: http://localhost:8080
- Default login: admin@hms.com / admin123

### Running Device Worker
Process device data queue and send to AI:
```bash
php spark hms:device-worker --limit=50 --continuous --interval=10
```

### AI Service Configuration
Configure AI service endpoint in `.env`:
```
AI_BASE_URL=http://localhost:8000
AI_API_KEY=your-api-key
AI_TIMEOUT=30
```

## API Endpoints

### Authentication
- POST `/api/v1/auth/login` - User login

### Patients
- GET `/api/v1/patients` - List patients
- GET `/api/v1/patients/{id}` - Get patient details

### Doctors
- GET `/api/v1/doctors` - List doctors

### Visits
- GET `/api/v1/visits` - List visits

### Lab
- GET `/api/v1/lab/orders` - List lab orders
- GET `/api/v1/lab/results/{orderId}` - Get lab results

### HL7/FHIR Integration
- POST `/hl7/receive` - Receive HL7 messages
- GET `/fhir/Patient/{id}` - Get FHIR Patient resource
- POST `/fhir/Patient` - Create FHIR Patient
- GET `/fhir/Observation` - Get FHIR Observations

## Security Features

- Password hashing (bcrypt)
- API token authentication
- Session security
- Audit logging for all actions
- Tenant-based data isolation
- Role-based access control
- CSRF protection
- Input validation

## License

Proprietary - All rights reserved.
