<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerAccount extends Model
{
    protected $table            = 'customer_accounts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'account_number', 'customer_name', 'address', 'phone', 'email',
        'meter_number', 'connection_type', 'status',
        'first_name', 'last_name', 'city', 'state', 'zip_code',
        'password', 'is_active', 'email_verified',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    public static function generateAccountNumber(): string
    {
        return 'EC-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(6)));
    }

    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function filtered(array $filters, int $perPage = 10): array
    {
        $keyword = trim((string) ($filters['search'] ?? ''));

        if ($keyword !== '') {
            $this->groupStart()
                ->like('account_number', $keyword)
                ->orLike('customer_name', $keyword)
                ->orLike('email', $keyword)
                ->orLike('phone', $keyword)
                ->groupEnd();
        }

        if (($filters['status'] ?? '') !== '') {
            $this->where('status', $filters['status']);
        }

        if (($filters['type'] ?? '') !== '') {
            $this->where('connection_type', $filters['type']);
        }

        // The SQL sample shares one timestamp; the ID keeps pages deterministic.
        return $this->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->paginate($perPage, 'accounts');
    }

    public function countByStatus(string $status): int
    {
        return $this->where('status', $status)->countAllResults();
    }
}
