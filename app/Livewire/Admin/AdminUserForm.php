<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AdminUserForm extends Component
{
    public $adminId = null;
    public $name = '';
    public $email = '';
    public $role = '';
    public $password = '';
    public $password_confirmation = '';
    public $isActive = true;

    public function mount($adminId = null)
    {
        $this->adminId = $adminId;
        if ($this->adminId) {
            $admin = Admin::findOrFail($this->adminId);
            $this->name = $admin->name;
            $this->email = $admin->email;
            $this->role = $admin->role;
            $this->isActive = $admin->active;
            // Password remains blank unless changed
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($this->adminId),
            ],
            'role' => 'required|string|max:255',
            'password' => [
                Rule::requiredIf(!$this->adminId), // Required if creating
                'nullable', // Allows it to be empty if editing
                'min:8',
                'confirmed',
            ],
            'isActive' => 'boolean',
        ];
    }

    public function saveAdmin()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'active' => $this->isActive,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->adminId) {
            Admin::find($this->adminId)->update($data);
        } else {
            Admin::create($data);
        }

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Admin user saved.']);
        return redirect()->route('admin.admin_users.index');
    }

    public function render()
    {
        return view('livewire.admin.admin-user-form');
    }
}
