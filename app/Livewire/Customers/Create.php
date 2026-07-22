<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public string $name        = '';
    public string $phone       = '';
    public ?string $email      = null;
    public ?string $birth_date = null;
    public ?string $notes      = null;
    public bool $is_active     = true;

    // Account creation
    public bool $create_account    = false;
    public string $account_email   = '';
    public string $account_password = '';

    public function updatedCreateAccount(bool $value): void
    {
        // Pre-fill email from customer email if available
        if ($value && $this->email) {
            $this->account_email = $this->email;
        }
    }

    public function updatedEmail(string $value): void
    {
        if ($this->create_account && empty($this->account_email)) {
            $this->account_email = $value;
        }
    }

    public function save()
    {
        $rules = [
            'name'  => ['required', 'max:255'],
            'phone' => ['required', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'notes' => ['nullable'],
            'is_active' => ['boolean'],
        ];

        if ($this->create_account) {
            $rules['account_email']    = ['required', 'email', 'max:255', Rule::unique('users', 'email')];
            $rules['account_password'] = ['required', 'min:8'];
        }

        $validated = $this->validate($rules);

        $userId = null;

        if ($this->create_account) {
            $user = User::create([
                'name'     => $this->name,
                'email'    => $this->account_email,
                'password' => Hash::make($this->account_password),
            ]);

            $user->assignRole('customer');

            $userId = $user->id;

            // Also sync email to customer profile if empty
            if (! $this->email) {
                $validated['email'] = $this->account_email;
            }
        }

        Customer::create([
            'user_id'      => $userId,
            'name'         => $validated['name'],
            'phone'        => $validated['phone'],
            'email'        => $validated['email'] ?? null,
            'birth_date'   => $validated['birth_date'] ?? null,
            'notes'        => $validated['notes'] ?? null,
            'is_active'    => $validated['is_active'],
            'last_visit_at' => null,
        ]);

        $prefix = auth()->user()->hasRole('kasir') ? 'kasir' : 'owner';

        session()->flash('success', 'Customer berhasil ditambahkan.' . ($this->create_account ? ' Akun login juga telah dibuat.' : ''));

        return redirect()->route("{$prefix}.customers.index");
    }

    public function render()
    {
        $prefix = auth()->user()->hasRole('kasir') ? 'kasir' : 'owner';

        return view('livewire.customers.create', compact('prefix'))
            ->layout('layouts.app');
    }
}