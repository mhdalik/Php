<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CustomerModel;
use App\Models\ActivityModel;

class Customers extends BaseController
{
    use ResponseTrait;

    protected $customerModel;
    protected $activityModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->activityModel = new ActivityModel();
    }

    public function index()
    {
        $model = $this->customerModel;

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $city = $this->request->getGet('city');

        if ($search) {
            $model->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->orLike('company', $search)
                ->groupEnd();
        }

        if ($status) $model->where('status', $status);
        if ($city) $model->where('city', $city);

        // sorting
        $sort = $this->request->getGet('sort') ?? 'id';
        $order = $this->request->getGet('order') ?? 'desc';

        // validate sort column to prevent sql injection
        $allowedSorts = ['id', 'name', 'email', 'phone', 'company', 'city', 'status'];
        if (in_array($sort, $allowedSorts)) {
            $model->orderBy($sort, strtolower($order) === 'asc' ? 'asc' : 'desc');
        } else {
            $model->orderBy('id', 'desc');
        }

        // pagination parameters
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('per_page') ?? 20);

        if ($page < 1)  $page = 1;
        if ($perPage < 1) $perPage = 20;

        $customers = $model->paginate($perPage, 'default', $page);

        $response = [
            'data' => $customers,
            'pagination' => [
                'total' => $model->pager->getTotal(),
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => $model->pager->getPageCount(),
            ]
        ];

        return $this->respond($response, 200);
    }

    public function show($id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) return $this->failNotFound('Customer not found');

        return $this->respond($customer, 200);
    }

    public function create()
    {
        $json = $this->request->getJSON(true);

        if (empty($json)) return $this->failValidationErrors(['error' => 'No JSON data provided']);

        $rules = [
            'name'   => 'required|min_length[3]|max_length[255]',
            'email'  => 'required|valid_email|is_unique[customers.email]',
            'phone'  => 'permit_empty|max_length[50]',
            'company' => 'permit_empty|max_length[255]',
            'city'   => 'permit_empty|max_length[100]',
            'status' => 'required|in_list[active,inactive,pending]'
        ];

        if (!$this->validateData($json, $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'name' => $json['name'],
            'email' => $json['email'],
            'phone' => $json['phone'] ?? null,
            'company' => $json['company'] ?? null,
            'city' => $json['city'] ?? null,
            'status' => $json['status'] ?? 'active',
            'notes' => $json['notes'] ?? null
        ];

        if ($this->customerModel->insert($data)) {
            $insertedId = $this->customerModel->getInsertID();

            // log activity
            $this->activityModel->insert([
                'customer_id' => $insertedId,
                'action' => 'created',
                'description' => 'Customer created via API',
                'user_id' => null
            ]);

            $createdCustomer = $this->customerModel->find($insertedId);
            return $this->respondCreated($createdCustomer);
        }

        return $this->fail('Failed to create customer', 500);
    }

    public function update($id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) return $this->failNotFound('Customer not found');

        $json = $this->request->getJSON(true);

        if (empty($json)) {
            return $this->failValidationErrors(['error' => 'No JSON data provided']);
        }

        $rules = [
            'name'   => 'required|min_length[3]|max_length[255]',
            'email'  => "required|valid_email|is_unique[customers.email,id,{$id}]",
            'phone'  => 'permit_empty|max_length[50]',
            'company' => 'permit_empty|max_length[255]',
            'city'   => 'permit_empty|max_length[100]',
            'status' => 'required|in_list[active,inactive,pending]'
        ];

        if (!$this->validateData($json, $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'name' => $json['name'],
            'email' => $json['email'],
            'phone' => $json['phone'] ?? null,
            'company' => $json['company'] ?? null,
            'city' => $json['city'] ?? null,
            'status' => $json['status'],
            'notes' => $json['notes'] ?? null
        ];

        if ($this->customerModel->update($id, $data)) { // log activity
            $this->activityModel->insert([
                'customer_id' => $id,
                'action' => 'updated',
                'description' => 'Customer updated via API',
                'user_id' => null
            ]);

            $updatedCustomer = $this->customerModel->find($id);
            return $this->respond($updatedCustomer, 200);
        }

        return $this->fail('Failed to update customer', 500);
    }

    public function delete($id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) return $this->failNotFound('Customer not found');

        $this->customerModel->delete($id);

        return $this->respond(['status' => 'success', 'message' => 'Customer deleted successfully'], 200);
    }
}
