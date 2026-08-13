<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Edit extends Component
{
    public Employee $employee;

    public string  $name              = '';
    public string  $email             = '';
    public string  $password          = '';
    public string  $role              = 'hairstylist';
    public ?string $phone             = null;
    public ?string $birth_date        = null;
    public ?string $address           = null;
    public ?string $hire_date         = null;
    public ?float  $basic_salary      = 2000000;
    public ?float  $meal_allowance    = 15000;
    public ?float  $overtime_rate     = 15000;
    public string  $employment_status = 'active';

    public function mount(Employee $employee): void
    {
        $this->employee = $employee;

        $user = $employee->user;

        $this->name              = $employee->full_name;
        $this->email             = $user?->email ?? '';
        $this->role              = $employee->role ?? $user?->getRoleNames()->first() ?? 'hairstylist';
        $this->phone             = $employee->phone;
        $this->birth_date        = $employee->birth_date?->format('Y-m-d');
        $this->address           = $employee->address;
        $this->hire_date         = $employee->hire_date?->format('Y-m-d');
        $this->basic_salary      = $employee->basic_salary !== null ? (float) $employee->basic_salary : null;
        $this->meal_allowance    = $employee->meal_allowance !== null ? (float) $employee->meal_allowance : null;
        $this->overtime_rate     = $employee->overtime_rate !== null ? (float) $employee->overtime_rate : null;
        $this->employment_status = $employee->employment_status;
    }

    public function save(): void
    {
        $isKasir = strtolower(trim($this->role)) === 'kasir';

        $rules = [
            'name'              => ['required', 'string', 'max:255'],
            'role'              => ['required', 'string', 'max:255'],
            'phone'             => ['nullable', 'max:30'],
            'birth_date'        => ['nullable', 'date'],
            'address'           => ['nullable'],
            'hire_date'         => ['required', 'date'],
            'basic_salary'      => ['nullable', 'numeric', 'min:0'],
            'meal_allowance'    => ['nullable', 'numeric', 'min:0'],
            'overtime_rate'     => ['nullable', 'numeric', 'min:0'],
            'employment_status' => ['required', 'in:active,inactive,resigned'],
        ];

        if ($isKasir) {
            $rules['email'] = ['required', 'email', 'unique:users,email,' . $this->employee->user_id];
            $rules['password'] = ['nullable', 'min:8'];
        }

        $validated = $this->validate($rules);

        $userId = $this->employee->user_id;

        if ($isKasir) {
            if ($userId) {
                // Update existing user account
                $user = User::findOrFail($userId);
                $userPayload = [
                    'name'  => $validated['name'],
                    'email' => $validated['email'],
                ];
                if (!empty($validated['password'])) {
                    $userPayload['password'] = Hash::make($validated['password']);
                }
                $user->update($userPayload);
                $user->syncRoles(['kasir']);
            } else {
                // Create new user account for cashier
                $user = User::create([
                    'name'     => $validated['name'],
                    'email'    => $validated['email'],
                    'password' => Hash::make($validated['password'] ?? 'password'),
                ]);
                $user->assignRole('kasir');
                $userId = $user->id;
            }
        } else {
            // Stylist role: if they had a user account, delete it
            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    $user->delete();
                }
                $userId = null;
            }
        }

        $this->employee->update([
            'user_id'           => $userId,
            'role'              => $validated['role'],
            'full_name'         => $validated['name'],
            'phone'             => $validated['phone'],
            'birth_date'        => $validated['birth_date'],
            'address'           => $validated['address'],
            'hire_date'         => $validated['hire_date'],
            'basic_salary'      => $validated['basic_salary'],
            'meal_allowance'    => $validated['meal_allowance'],
            'overtime_rate'     => $validated['overtime_rate'],
            'employment_status' => $validated['employment_status'],
        ]);

        session()->flash('success', 'Data karyawan berhasil diperbarui.');

        $this->redirect(route('owner.employees.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.employees.edit')->layout('layouts.app');
    }
}