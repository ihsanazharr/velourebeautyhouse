<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public Customer $customer;

    public string $name        = '';
    public string $phone       = '';
    public ?string $email      = null;
    public ?string $birth_date = null;
    public ?string $notes      = null;
    public bool $is_active     = true;

    // Account creation for customers without an account
    public bool $create_account     = false;
    public string $account_email    = '';
    public string $account_password = '';

    public function mount(Customer $customer): void
    {
        $this->customer   = $customer;
        $this->name       = $customer->name;
        $this->phone      = $customer->phone;
        $this->email      = $customer->email;
        $this->birth_date = $customer->birth_date?->toDateString();
        $this->notes      = $customer->notes;
        $this->is_active  = $customer->is_active;

        // Pre-fill account email from customer email
        $this->account_email = $customer->email ?? '';
    }

    public function save()
    {
        $rules = [
            'name'       => ['required', 'string', 'max:255'],
            'phone'      => ['required', 'string', 'max:50'],
            'email'      => ['nullable', 'email', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'notes'      => ['nullable', 'string'],
            'is_active'  => ['boolean'],
        ];

        // Only validate account fields if customer has no account and toggled on
        if ($this->create_account && ! $this->customer->user_id) {
            $rules['account_email']    = ['required', 'email', 'max:255', Rule::unique('users', 'email')];
            $rules['account_password'] = ['required', 'min:8'];
        }

        $validated = $this->validate($rules);

        $this->customer->update([
            'name'       => $validated['name'],
            'phone'      => $validated['phone'],
            'email'      => $validated['email'],
            'birth_date' => $validated['birth_date'],
            'notes'      => $validated['notes'],
            'is_active'  => $validated['is_active'],
        ]);

        // Sync name/email to existing linked User account
        if ($this->customer->user) {
            $this->customer->user->update([
                'name'  => $validated['name'],
                'email' => $validated['email'] ?: $this->customer->user->email,
            ]);
        }

        // Create new account if requested and customer has none
        if ($this->create_account && ! $this->customer->user_id) {
            $user = User::create([
                'name'     => $this->name,
                'email'    => $this->account_email,
                'password' => Hash::make($this->account_password),
            ]);
            $user->assignRole('customer');

            $this->customer->update(['user_id' => $user->id]);
        }

        $routePrefix = auth()->user()?->hasRole('kasir') ? 'kasir' : 'owner';

        session()->flash('success', 'Customer berhasil diperbarui.' . ($this->create_account && ! $this->customer->fresh()->user_id ? '' : ($this->create_account ? ' Akun login telah dibuat.' : '')));

        return redirect()->route("{$routePrefix}.customers.index");
    }

    public function render()
    {
        return view('livewire.customers.edit')
            ->layout('layouts.app');
    }
}
