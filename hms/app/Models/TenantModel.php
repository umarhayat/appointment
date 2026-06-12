<?php

namespace App\Models;

use CodeIgniter\Model;

class TenantModel extends Model
{
    protected $table            = 'tenants';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'slug', 'email', 'phone', 'address', 'city', 'state', 'country',
        'postal_code', 'timezone', 'subscription_plan_id', 'subscription_status',
        'subscription_start_date', 'subscription_end_date', 'max_users', 'max_beds',
        'features', 'settings', 'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'  => 'required|min_length[3]|max_length[255]',
        'slug'  => 'required|alpha_dash|max_length[100]|is_unique[tenants.slug,id,{id}]',
        'email' => 'required|valid_email|max_length[255]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Tenant name is required',
        ],
        'slug' => [
            'required' => 'Tenant slug is required',
            'is_unique' => 'This slug is already taken',
        ],
    ];

    /**
     * Get tenant by slug
     */
    public function getBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Check if tenant subscription is active
     */
    public function isActiveSubscription(int $tenantId): bool
    {
        $tenant = $this->find($tenantId);
        if (!$tenant) {
            return false;
        }

        $activeStatuses = ['active', 'trial'];
        return in_array($tenant['subscription_status'], $activeStatuses);
    }

    /**
     * Get tenant features as array
     */
    public function getFeatures(int $tenantId): array
    {
        $tenant = $this->find($tenantId);
        if (!$tenant || empty($tenant['features'])) {
            return [];
        }
        return json_decode($tenant['features'], true) ?? [];
    }

    /**
     * Check if tenant has specific feature enabled
     */
    public function hasFeature(int $tenantId, string $feature): bool
    {
        $features = $this->getFeatures($tenantId);
        return in_array($feature, $features);
    }
}
