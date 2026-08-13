<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $role = 'hairstylist';

    public ?string $phone = null;

    public ?string $birth_date = null;

    public ?string $address = null;

    public ?string $hire_date = null;

    public ?float $basic_salary = null;

    public ?float $meal_allowance = null;

    public ?float $overtime_rate = null;

    public string $employment_status = 'active';

    public function save()
    {
        $isKasir = strtolower(trim($this->role)) === 'kasir';

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'max:30'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable'],
            'hire_date' => ['required', 'date'],
            'basic_salary' => ['nullable', 'numeric', 'min:0'],
            'meal_allowance' => ['nullable', 'numeric', 'min:0'],
            'overtime_rate' => ['nullable', 'numeric', 'min:0'],
            'employment_status' => [
                'required',
                'in:active,inactive,resigned'
            ],
        ];

        if ($isKasir) {
            $rules['email'] = ['required', 'email', 'unique:users,email'];
            $rules['password'] = ['required', 'min:8'];
        }

        $validated = $this->validate($rules);

        DB::transaction(function () use ($validated, $isKasir) {
            $userId = null;

            if ($isKasir) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);

                $user->assignRole('kasir');
                $userId = $user->id;
            }

            $nextNumber = Employee::count() + 1;
            $employeeCode = 'EMP-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            Employee::create([
                'user_id' => $userId,
                'role' => $validated['role'],
                'employee_code' => $employeeCode,
                'full_name' => $validated['name'],
                'phone' => $validated['phone'],
                'birth_date' => $validated['birth_date'],
                'address' => $validated['address'],
                'hire_date' => $validated['hire_date'],
                'basic_salary' => $validated['basic_salary'],
                'commission_percentage' => 0,
                'meal_allowance' => $validated['meal_allowance'],
                'overtime_rate' => $validated['overtime_rate'],
                'employment_status' => $validated['employment_status'],
            ]);
        });

        session()->flash(
            'success',
            'Karyawan berhasil ditambahkan.'
        );

        return redirect()->route(
            'owner.employees.index'
        );
    }

    public function render()
    {
        return view(
            'livewire.employees.create'
        )->layout('layouts.app');
    }
}