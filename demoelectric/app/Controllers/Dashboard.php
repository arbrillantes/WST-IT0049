<?php

namespace App\Controllers;

use App\Models\CustomerAccount;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Dashboard extends BaseController
{
    private const STATUSES = ['active', 'inactive', 'suspended'];
    private const TYPES    = ['residential', 'commercial', 'industrial'];

    public function index(): string
    {
        $query = $this->request->getGet();
        $status = is_string($query['status'] ?? null) ? $query['status'] : '';
        $type   = is_string($query['type'] ?? null) ? $query['type'] : '';
        $filters = [
            'search' => is_string($query['search'] ?? null) ? mb_substr(trim($query['search']), 0, 150) : '',
            'status' => in_array($status, self::STATUSES, true) ? $status : '',
            'type'   => in_array($type, self::TYPES, true) ? $type : '',
        ];

        $model    = new CustomerAccount();
        $accounts = $model->filtered($filters);

        return view('dashboard', [
            'title'             => 'Admin Dashboard - Puihaha Electric',
            'page'              => 'dashboard',
            'accounts'          => $accounts,
            'pager'             => $model->pager,
            'filters'           => $filters,
            'totalAccounts'     => (new CustomerAccount())->countAllResults(),
            'activeAccounts'    => (new CustomerAccount())->countByStatus('active'),
            'inactiveAccounts'  => (new CustomerAccount())->countByStatus('inactive'),
            'suspendedAccounts' => (new CustomerAccount())->countByStatus('suspended'),
            'currentPage'       => $model->pager->getCurrentPage('accounts'),
        ]);
    }

    public function show(int $id): string
    {
        $account = $this->findAccount($id);

        return view('account', [
            'title'   => 'Account ' . $account['account_number'] . ' - Puihaha Electric',
            'page'    => 'dashboard',
            'account' => $account,
        ]);
    }

    public function new(): string
    {
        return view('account_form', [
            'title' => 'Add Customer - Puihaha Electric',
            'page' => 'dashboard',
            'account' => [],
        ]);
    }

    public function edit(int $id): string
    {
        return view('account_form', [
            'title' => 'Edit Customer - Puihaha Electric',
            'page' => 'dashboard',
            'account' => $this->findAccount($id),
        ]);
    }

    public function create(): RedirectResponse
    {
        return $this->persist();
    }

    public function update(int $id): RedirectResponse
    {
        $this->findAccount($id);

        return $this->persist($id);
    }

    public function confirmDelete(int $id): string
    {
        return view('account_delete', [
            'title' => 'Delete Customer - Puihaha Electric',
            'page' => 'dashboard',
            'account' => $this->findAccount($id),
        ]);
    }

    public function delete(int $id): RedirectResponse
    {
        $account = $this->findAccount($id);

        try {
            if (! (new CustomerAccount())->delete($id)) {
                throw new \RuntimeException('Customer deletion failed.');
            }
        } catch (\Throwable $exception) {
            log_message('error', 'Customer deletion failed: {message}', ['message' => $exception->getMessage()]);

            return redirect()->to('/account/' . $id . '/delete')->with('error', 'Could not delete the account. Please try again.');
        }

        return redirect()->to('/admin/dashboard')->with('success', 'Account ' . $account['account_number'] . ' deleted.');
    }

    private function persist(?int $id = null): RedirectResponse
    {
        $fields = ['account_number', 'customer_name', 'address', 'phone', 'email', 'meter_number', 'connection_type', 'status'];
        $data = [];
        foreach ($fields as $field) {
            $value = $this->request->getPost($field);
            $data[$field] = is_string($value) ? trim($value) : '';
        }

        $rules = [
            'account_number' => 'required|max_length[50]|is_unique[customer_accounts.account_number,id,' . ($id ?? 0) . ']',
            'customer_name' => 'required|min_length[2]|max_length[201]',
            'address' => 'required|min_length[5]|max_length[2000]',
            'phone' => 'permit_empty|max_length[20]',
            'email' => 'permit_empty|valid_email|max_length[100]|is_unique[customer_accounts.email,id,' . ($id ?? 0) . ']',
            'meter_number' => 'permit_empty|max_length[50]',
            'connection_type' => 'required|in_list[residential,commercial,industrial]',
            'status' => 'required|in_list[active,inactive,suspended]',
        ];
        $formPath = $id === null ? '/account/new' : '/account/' . $id . '/edit';

        if (! $this->validateData($data, $rules)) {
            return $this->formRedirect($formPath, $data, $this->validator->getErrors());
        }

        try {
            $data['email'] = $data['email'] === '' ? null : strtolower($data['email']);
            $model = new CustomerAccount();
            $saved = $id === null ? $model->insert($data) : $model->update($id, $data);
            if (! $saved) {
                throw new \RuntimeException('Customer could not be saved.');
            }
            $savedId = $id ?? (int) $saved;
        } catch (\Throwable $exception) {
            log_message('error', 'Customer save failed: {message}', ['message' => $exception->getMessage()]);

            return $this->formRedirect($formPath, $data, [], 'Could not save the account. Check the account number is unique and try again.');
        }

        return redirect()->to('/account/' . $savedId)->with('success', $id === null ? 'Customer account created.' : 'Customer account updated.');
    }

    private function findAccount(int $id): array
    {
        $account = (new CustomerAccount())->find($id);
        if ($account === null) {
            throw PageNotFoundException::forPageNotFound('Customer account not found.');
        }

        return $account;
    }
}
