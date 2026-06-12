<?php

namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController;
use App\Models\TenantModel;

class AuthController extends BaseController
{
    protected $session;
    protected $userModel;
    protected $tenantModel;

    public function __construct()
    {
        $this->session = session();
        $this->userModel = new \App\Models\UserModel();
        $this->tenantModel = new TenantModel();
    }

    /**
     * Show login page
     */
    public function login()
    {
        if ($this->session->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('App\Modules\Auth\Views\login');
    }

    /**
     * Process login
     */
    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Find user by email
        $user = $this->userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid credentials');
        }

        if (!$user['is_active']) {
            return redirect()->back()->with('error', 'Account is deactivated');
        }

        // Update last login
        $this->userModel->update($user['id'], [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $this->request->getIPAddress(),
        ]);

        // Set session data
        $this->session->set([
            'logged_in'  => true,
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'email'      => $user['email'],
            'name'       => $user['first_name'] . ' ' . $user['last_name'],
            'is_super_admin' => $user['is_super_admin'],
        ]);

        // If super admin, redirect to tenant selection
        if ($user['is_super_admin']) {
            return redirect()->to('/auth/select-tenant');
        }

        // Get user's tenants
        $tenantUserModel = new \App\Models\TenantUserModel();
        $tenants = $tenantUserModel->getUserTenants($user['id']);

        if (empty($tenants)) {
            return redirect()->to('/auth/select-tenant')->with('error', 'No tenants assigned');
        }

        // If only one tenant, auto-select
        if (count($tenants) === 1) {
            $this->setTenantContext($tenants[0]);
            return redirect()->to('/dashboard');
        }

        // Store tenants in session for selection
        $this->session->set('available_tenants', $tenants);
        return redirect()->to('/auth/select-tenant');
    }

    /**
     * Tenant selection page
     */
    public function selectTenant()
    {
        $tenants = $this->session->get('available_tenants') ?? [];
        return view('App\Modules\Auth\Views\select_tenant', ['tenants' => $tenants]);
    }

    /**
     * Set selected tenant context
     */
    public function setTenant($tenantId)
    {
        $tenant = $this->tenantModel->find($tenantId);
        
        if (!$tenant || !$tenant['is_active']) {
            return redirect()->back()->with('error', 'Invalid or inactive tenant');
        }

        // Verify user has access to this tenant
        $userId = $this->session->get('user_id');
        $tenantUserModel = new \App\Models\TenantUserModel();
        $tenantUser = $tenantUserModel->where('tenant_id', $tenantId)
                                      ->where('user_id', $userId)
                                      ->first();

        if (!$tenantUser || !$tenantUser['is_active']) {
            return redirect()->back()->with('error', 'Access denied to this tenant');
        }

        $this->setTenantContext($tenant);
        
        return redirect()->to('/dashboard');
    }

    /**
     * Helper to set tenant context in session
     */
    private function setTenantContext(array $tenant)
    {
        $this->session->set([
            'tenant_id'   => $tenant['id'],
            'tenant_slug' => $tenant['slug'],
            'tenant_name' => $tenant['name'],
        ]);
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/auth/login');
    }
}
