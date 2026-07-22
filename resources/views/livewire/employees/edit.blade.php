<div class="p-6 lg:p-10" style="background-color: #FAF9F7; min-height: 100vh;">

    {{-- ── Header ──────────────────────────────────────────── --}}
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Owner</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">
                Edit Karyawan
            </h1>
            <p class="font-body text-sm mt-1" style="color: #9C9690;">
                {{ $employee->employee_code }} — {{ $employee->full_name }}
            </p>
        </div>
        <a
            href="{{ route('owner.employees.index') }}"
            class="font-body text-xs tracking-[0.15em] uppercase transition-opacity duration-300 hover:opacity-70"
            style="color: #9C9690; text-decoration: underline; text-underline-offset: 4px;"
        >
            ← Kembali
        </a>
    </div>

    @if(session()->has('success'))
        <div
            class="font-body text-sm mb-6 px-5 py-4 border-l-2"
            style="background: white; border-color: #5C7A5C; color: #3A3733;"
        >
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ── Kolom Kiri ── --}}
            <div class="flex flex-col gap-6">

                {{-- Akun --}}
                <div class="bg-white border p-6" style="border-color: #EDEAE4;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-5" style="color: #B89B6B;">Akun Login</p>

                    <div class="flex flex-col gap-5">

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Nama Lengkap <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="name"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                                placeholder="Nama lengkap karyawan"
                            >
                            @error('name')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Email <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="email"
                                wire:model="email"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                                placeholder="email@veloure.com"
                            >
                            @error('email')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Password Baru
                                <span class="normal-case tracking-normal ml-1" style="color: #9C9690;">(kosongkan jika tidak diubah)</span>
                            </label>
                            <input
                                type="password"
                                wire:model="password"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                                placeholder="Min. 8 karakter"
                            >
                            @error('password')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Role <span style="color: #B5402C;">*</span>
                            </label>
                            <select
                                wire:model.live="role"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                                <option value="kasir">Kasir</option>
                                <option value="hairstylist">Hairstylist</option>
                                <option value="nailist_senior">Nailist Senior</option>
                                <option value="nailist_junior">Nailist Junior</option>
                            </select>
                            @error('role')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Status Karyawan <span style="color: #B5402C;">*</span>
                            </label>
                            <select
                                wire:model="employment_status"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="resigned">Resigned</option>
                            </select>
                            @error('employment_status')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Data Pribadi --}}
                <div class="bg-white border p-6" style="border-color: #EDEAE4;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-5" style="color: #B89B6B;">Data Pribadi</p>

                    <div class="flex flex-col gap-5">

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">Telepon</label>
                            <input
                                type="text"
                                wire:model="phone"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                                placeholder="08xxxxxxxxxx"
                            >
                            @error('phone')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">Tanggal Lahir</label>
                            <input
                                type="date"
                                wire:model="birth_date"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                            @error('birth_date')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">Tanggal Bergabung <span style="color: #B5402C;">*</span></label>
                            <input
                                type="date"
                                wire:model="hire_date"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                            @error('hire_date')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">Alamat</label>
                            <textarea
                                wire:model="address"
                                rows="3"
                                class="font-body text-sm w-full outline-none border bg-transparent p-2 resize-none"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                                placeholder="Alamat lengkap"
                            ></textarea>
                            @error('address')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            {{-- ── Kolom Kanan ── --}}
            <div class="flex flex-col gap-6">

                {{-- Kompensasi --}}
                <div class="bg-white border p-6" style="border-color: #EDEAE4;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-5" style="color: #B89B6B;">Kompensasi</p>

                    <div class="flex flex-col gap-5">

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Gaji Pokok (Rp) <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="number"
                                wire:model="basic_salary"
                                min="0"
                                step="50000"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                            @error('basic_salary')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Uang Makan / Hari (Rp) <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="number"
                                wire:model="meal_allowance"
                                min="0"
                                step="1000"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                            @error('meal_allowance')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-body text-xs tracking-[0.1em] uppercase" style="color: #9C9690;">
                                Rate Lembur / Jam (Rp) <span style="color: #B5402C;">*</span>
                            </label>
                            <input
                                type="number"
                                wire:model="overtime_rate"
                                min="0"
                                step="1000"
                                class="font-body text-sm w-full outline-none border-b bg-transparent py-2"
                                style="border-color: #EDEAE4; color: #1C1A18;"
                            >
                            @error('overtime_rate')
                                <span class="font-body text-xs" style="color: #B5402C;">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Info readonly --}}
                <div class="bg-white border p-6" style="border-color: #EDEAE4;">
                    <p class="font-body text-xs tracking-[0.2em] uppercase mb-5" style="color: #B89B6B;">Informasi Sistem</p>

                    <div class="flex flex-col gap-4">
                        <div class="flex justify-between items-center py-2" style="border-bottom: 1px solid #EDEAE4;">
                            <span class="font-body text-xs tracking-[0.08em] uppercase" style="color: #9C9690;">Kode Karyawan</span>
                            <span class="font-body text-sm" style="color: #1C1A18;">{{ $employee->employee_code }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2" style="border-bottom: 1px solid #EDEAE4;">
                            <span class="font-body text-xs tracking-[0.08em] uppercase" style="color: #9C9690;">Dibuat</span>
                            <span class="font-body text-sm" style="color: #3A3733;">{{ $employee->created_at?->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="font-body text-xs tracking-[0.08em] uppercase" style="color: #9C9690;">Terakhir Diupdate</span>
                            <span class="font-body text-sm" style="color: #3A3733;">{{ $employee->updated_at?->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="flex justify-end gap-4 pt-2">
                    <a
                        href="{{ route('owner.employees.index') }}"
                        class="font-body text-xs tracking-[0.15em] uppercase py-3.5 px-6 transition-opacity duration-300 hover:opacity-70"
                        style="color: #9C9690; border: 1px solid #EDEAE4; background: white;"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="font-body text-xs tracking-[0.15em] uppercase py-3.5 px-8 transition-opacity duration-300 hover:opacity-80"
                        style="background: #1C1A18; color: #FAF9F7;"
                    >
                        Simpan Perubahan
                    </button>
                </div>

            </div>

        </div>
    </form>

</div>