<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Tenant Filter - Ensures tenant context is set for all requests
 * Multi-tenant SaaS isolation
 */
class TenantFilter implements FilterInterface
{
    /**
     * Check tenant context before request
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip for auth routes and API token auth
        $uri = $request->uri->getPath();
        if (strpos($uri, 'auth') === 0 || strpos($uri, 'api/') === 0) {
            return;
        }

        // Get tenant from session or subdomain
        $session = session();
        $tenantId = $session->get('tenant_id');

        if (!$tenantId) {
            // Try to get from subdomain
            $host = $request->getServer('HTTP_HOST');
            $parts = explode('.', $host);
            if (count($parts) >= 2 && $parts[0] !== 'www') {
                $subdomain = $parts[0];
                $tenantModel = new \App\Models\TenantModel();
                $tenant = $tenantModel->getBySlug($subdomain);
                
                if ($tenant && $tenant['is_active']) {
                    $session->set('tenant_id', $tenant['id']);
                    $session->set('tenant_slug', $tenant['slug']);
                    $session->set('tenant_name', $tenant['name']);
                    return;
                }
            }

            // No tenant found - redirect to tenant selection or login
            if (!$session->get('logged_in')) {
                return redirect()->to('/auth/login');
            }
            
            return redirect()->to('/auth/select-tenant');
        }

        // Verify tenant is still active
        $tenantModel = new \App\Models\TenantModel();
        $tenant = $tenantModel->find($tenantId);

        if (!$tenant || !$tenant['is_active']) {
            $session->destroy();
            return redirect()->to('/auth/login')->with('error', 'Tenant account is inactive');
        }

        // Set tenant context for the request
        $session->set('tenant_slug', $tenant['slug']);
        $session->set('tenant_name', $tenant['name']);
    }

    /**
     * Allows After filters to inspect and modify the response
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Add tenant info to response headers for debugging
        $session = session();
        if ($session->get('tenant_id')) {
            $response->setHeader('X-Tenant-ID', (string) $session->get('tenant_id'));
            $response->setHeader('X-Tenant-Slug', $session->get('tenant_slug') ?? '');
        }
    }
}
