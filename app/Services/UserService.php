<?php


namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Enums\UserRole;
use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $repo
    ) {}

    public function create(array $data)
    {
        $user = $this->repo->create([
            'region_id' => (int)$data['region_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] instanceof UserRole ? $data['role']->value : $data['role'],
        ]);

        return  $user;
    }

    public function all()
    {
        return $this->repo->all();
    }

    public function paginate($limit)
    {
        return $this->repo->paginate($limit);
    }

    public function destroy($id)
    {
        $user = $this->repo->find($id);
        if (! $user) {
            throw new \Exception("User tidak ditemukan");
        }
        if ($user->role === UserRole::Admin) {
            throw new \Exception("User dengan role admin tidak dapat dihapus");
        }
        return $this->repo->destroy($id);
    }

    public function update(array $data, $id)
    {
        if (isset($data['password']) && $data['password'] !== null && $data['password'] !== '') {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        if (isset($data['region_id'])) {
            $data['region_id'] = (int)$data['region_id'];
        }
        if (isset($data['role']) && $data['role'] instanceof UserRole) {
            $data['role'] = $data['role']->value;
        }
        return $this->repo->update($data, $id);
    }
}
