<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username', 'email', 'password', 'full_name', 'phone', 'role', 'avatar', 'is_active'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'full_name' => 'required|min_length[3]|max_length[255]',
        'role'     => 'required|in_list[admin,user]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getActiveUsers()
    {
        return $this->where('is_active', true)->findAll();
    }

    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->where('is_active', true)->findAll();
    }

    /**
     * Get user statistics for admin dashboard
     */
    public function getUserStats()
    {
        $total = $this->countAllResults(false);
        $active = $this->where('is_active', 1)->countAllResults(false);
        $inactive = $this->where('is_active', 0)->countAllResults(false);
        
        // Get new users from last 30 days
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        $new = $this->where('created_at >=', $thirtyDaysAgo)->countAllResults(false);
        
        return [
            'total_users' => $total,
            'active_users' => $active,
            'inactive_users' => $inactive,
            'new_users' => $new
        ];
    }
    
    /**
     * Get user with their events
     */
    public function getUserWithEvents($userId)
    {
        return $this->select('users.*, COUNT(events.id) as event_count')
            ->join('events', 'events.user_id = users.id', 'left')
            ->where('users.id', $userId)
            ->groupBy('users.id')
            ->first();
    }
    
    /**
     * Get user with their registrations
     */
    public function getUserWithRegistrations($userId)
    {
        return $this->select('users.*, COUNT(event_registrations.id) as registration_count')
            ->join('event_registrations', 'event_registrations.user_id = users.id', 'left')
            ->where('users.id', $userId)
            ->groupBy('users.id')
            ->first();
    }
    
    /**
     * Search users with filters
     */
    public function searchUsers($filters = [])
    {
        $builder = $this->builder();
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('username', $filters['search'])
                ->orLike('email', $filters['search'])
                ->orLike('full_name', $filters['search'])
                ->groupEnd();
        }
        
        if (!empty($filters['role'])) {
            $builder->where('role', $filters['role']);
        }
        
        if (isset($filters['is_active'])) {
            $builder->where('is_active', $filters['is_active']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('created_at <=', $filters['date_to']);
        }
        
        return $builder->orderBy('created_at', 'DESC');
    }
    
    /**
     * Get users with pagination and search
     */
    public function getUsersPaginated($perPage = 10, $filters = [])
    {
        $builder = $this->searchUsers($filters);
        return $builder->paginate($perPage);
    }
    
    /**
     * Bulk update user status
     */
    public function bulkUpdateStatus($userIds, $status)
    {
        return $this->whereIn('id', $userIds)
            ->set(['is_active' => $status])
            ->update();
    }
    
    /**
     * Get user activity summary
     */
    public function getUserActivitySummary($userId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                u.id,
                u.username,
                u.email,
                u.full_name,
                u.created_at as joined_date,
                COUNT(DISTINCT e.id) as events_created,
                COUNT(DISTINCT er.id) as events_registered,
                MAX(er.created_at) as last_registration
            FROM users u
            LEFT JOIN events e ON e.user_id = u.id
            LEFT JOIN event_registrations er ON er.user_id = u.id
            WHERE u.id = ?
            GROUP BY u.id
        ", [$userId]);
        
        return $query->getRow();
    }
}