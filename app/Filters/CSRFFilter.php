<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CSRFFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip CSRF check for CLI requests
        if (is_cli()) {
            return;
        }

        // Skip CSRF check for GET requests
        if ($request->getMethod() === 'get') {
            return;
        }

        // Use CodeIgniter's built-in CSRF validation
        $security = \Config\Services::security();
        
        try {
            if (!$security->verify($request)) {
                if ($request->isAJAX()) {
                    return service('response')
                        ->setStatusCode(403)
                        ->setJSON([
                            'error' => 'CSRF token mismatch',
                            'message' => 'Invalid CSRF token. Please refresh the page and try again.'
                        ]);
                }
                
                throw new \CodeIgniter\Security\Exceptions\SecurityException('CSRF token mismatch');
            }
        } catch (\Exception $e) {
            log_message('error', 'CSRF validation failed: ' . $e->getMessage());
            
            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(403)
                    ->setJSON([
                        'error' => 'Security validation failed',
                        'message' => 'Please refresh the page and try again.'
                    ]);
            }
            
            return redirect()->back()->with('error', 'Security validation failed. Please try again.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after request
    }
}