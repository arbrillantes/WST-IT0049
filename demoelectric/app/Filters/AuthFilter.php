<?php

namespace App\Filters;

use App\Models\User;
use App\Models\CustomerAccount;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $requiredRole = $arguments[0] ?? null;
        $login = $requiredRole === 'admin' ? '/admin/login' : '/login';
        if (session()->get('isLoggedIn') !== true || ! session()->get('userId')) {
            return redirect()->to($login)->with('error', 'Please log in to continue.');
        }

        $role = session()->get('userType');
        $store = $role === 'admin' ? 'users' : 'customer_accounts';
        // Expire pre-migration sessions: IDs in the two tables can overlap.
        if (! in_array($role, ['admin', 'customer'], true) || session()->get('identityStore') !== $store) {
            session()->destroy();
            return redirect()->to($login);
        }
        $user = $role === 'admin'
            ? (new User())->where('user_type', 'admin')->find((int) session()->get('userId'))
            : (new CustomerAccount())->find((int) session()->get('userId'));
        if ($user === null || ! $user['is_active'] || empty($user['password'])) {
            session()->destroy();
            return redirect()->to($login);
        }
        session()->set([
            'userName' => $role === 'admin' ? trim($user['first_name'] . ' ' . $user['last_name']) : $user['customer_name'],
        ]);

        if ($requiredRole !== null && $role !== $requiredRole) {
            return service('response')->setStatusCode(403)
                ->setHeader('Cache-Control', 'no-store, private')
                ->setBody(view('access_denied', ['title' => 'Access denied - Puihaha Electric', 'page' => '']));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('Cache-Control', 'no-store, private');
    }
}
