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
    public float   $basic_salary      = 2000000;
    public float   $meal_allowance    = 15000;
    public float   $overtime_rate     = 15000;
    public string  $employment_status = 'active';

    public function mount(Employee $employee): void
    {
        $this->employee = $employee;

        $user = $employee->user;

        $this->name              = $employee->full_name;
        $this->email             = $user?->email ?? '';
        $this->role              = $user?->getRoleNames()->first() ?? 'hairstylist';
        $this->phone             = $employee->phone;
        $this->birth_date        = $employee->birth_date?->format('Y-m-d');
        $this->address           = $employee->address;
        $this->hire_date         = $employee->hire_date?->format('Y-m-d');
        $this->basic_salary      = (float) $employee->basic_salary;
        $this->meal_allowance    = (float) $employee->meal_allowance;
        $this->overtime_rate     = (float) $employee->overtime_rate;
        $this->employment_status = $employee->employment_status;
    }

    public function updatedRole(): void
    {
        match ($this->role) {
            'kasir'          => $this->basic_salary = 2500000,
            'hairstylist'    => $this->basic_salary = 2000000,
            'nailist_senior' => $this->basic_salary = 2000000,
            'nailist_junior' => $this->basic_salary = 2000000,
            default          => $this->basic_salary = 2000000,
        };
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'unique:users,email,' . $this->employee->user_id],
            'password'          => ['nullable', 'min:8'],
            'role'              => ['required', 'in:kasir,hairstylist,nailist_senior,nailist_junior'],
            'phone'             => ['nullable', 'max:30'],
            'birth_date'        => ['nullable', 'date'],
            'address'           => ['nullable'],
            'hire_date'         => ['required', 'date'],
            'basic_salary'      => ['required', 'numeric', 'min:0'],
            'meal_allowance'    => ['required', 'numeric', 'min:0'],
            'overtime_rate'     => ['required', 'numeric', 'min:0'],
            'employment_status' => ['required', 'in:active,inactive,resigned'],
        ]);

        $user = $this->employee->user;

        $userPayload = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $userPayload['password'] = Hash::make($validated['password']);
        }

        $user->update($userPayload);
        $user->syncRoles([$validated['role']]);

        $this->employee->update([
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