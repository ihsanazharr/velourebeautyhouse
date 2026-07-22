<div class="p-6">

    <div class="mb-8">

        <h1 class="text-3xl font-bold">
            Tambah Karyawan
        </h1>

        <p class="text-gray-500">
            VELOURE BEAUTY HOUSE
        </p>

    </div>

    <form wire:submit="save">

        <div class="bg-white rounded-xl border p-6">

            <h2 class="font-semibold text-lg mb-4">
                Akun Login
            </h2>

            <div class="grid md:grid-cols-2 gap-4">

                <div>

                    <label class="block mb-1">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        wire:model="name"
                        class="w-full rounded border"
                    >

                    @error('name')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>

                    <label class="block mb-1">
                        Email
                    </label>

                    <input
                        type="email"
                        wire:model="email"
                        class="w-full rounded border"
                    >

                    @error('email')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>

                    <label class="block mb-1">
                        Password
                    </label>

                    <input
                        type="password"
                        wire:model="password"
                        class="w-full rounded border"
                    >

                    @error('password')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>

                    <label class="block mb-1">
                        Role
                    </label>

                    <select
                        wire:model.live="role"
                        class="w-full rounded border"
                    >
                        <option value="kasir">
                            Kasir
                        </option>

                        <option value="hairstylist">
                            Hairstylist
                        </option>

                        <option value="nailist_senior">
                            Nailist Senior
                        </option>

                        <option value="nailist_junior">
                            Nailist Junior
                        </option>
                    </select>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-xl border p-6 mt-6">

            <h2 class="font-semibold text-lg mb-4">
                Data Karyawan
            </h2>

            <div class="grid md:grid-cols-2 gap-4">

                <div>
                    <label>No HP</label>

                    <input
                        type="text"
                        wire:model="phone"
                        class="w-full rounded border"
                    >
                </div>

                <div>
                    <label>Tanggal Lahir</label>

                    <input
                        type="date"
                        wire:model="birth_date"
                        class="w-full rounded border"
                    >
                </div>

                <div class="md:col-span-2">

                    <label>Alamat</label>

                    <textarea
                        wire:model="address"
                        class="w-full rounded border"
                    ></textarea>

                </div>

                <div>

                    <label>Tanggal Masuk</label>

                    <input
                        type="date"
                        wire:model="hire_date"
                        class="w-full rounded border"
                    >

                </div>

            </div>

        </div>

        <div class="bg-white rounded-xl border p-6 mt-6">

            <h2 class="font-semibold text-lg mb-4">
                Payroll Default
            </h2>

            <div class="grid md:grid-cols-2 gap-4">

                <div>

                    <label>Gaji Pokok</label>

                    <input
                        type="number"
                        wire:model="basic_salary"
                        class="w-full rounded border"
                    >

                </div>

                <div>

                    <label>Uang Makan</label>

                    <input
                        type="number"
                        wire:model="meal_allowance"
                        class="w-full rounded border"
                    >

                </div>

                <div>

                    <label>Tarif Lembur</label>

                    <input
                        type="number"
                        wire:model="overtime_rate"
                        class="w-full rounded border"
                    >

                </div>

                <div>

                    <label>Status</label>

                    <select
                        wire:model="employment_status"
                        class="w-full rounded border"
                    >
                        <option value="active">
                            Active
                        </option>

                        <option value="inactive">
                            Inactive
                        </option>

                        <option value="resigned">
                            Resigned
                        </option>

                    </select>

                </div>

            </div>

        </div>

        <div class="mt-6">

            <button
                type="submit"
                style="
                    background:#000;
                    color:#fff;
                    padding:12px 24px;
                    border-radius:8px;
                    font-weight:600;
                "
            >
                Simpan Karyawan
            </button>

        </div>

    </form>

</div>