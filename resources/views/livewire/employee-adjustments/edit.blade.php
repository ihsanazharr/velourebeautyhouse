<div class="p-6 lg:p-10" style="background-color:#FAF9F7; min-height:100vh;">
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color:#B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color:#B89B6B;">Payroll</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color:#1C1A18;">Edit Adjustment</h1>
            <p class="font-body text-sm mt-1" style="color:#9C9690;">Bonus, komisi, atau potongan untuk payroll.</p>
        </div>

        <a href="{{ route('owner.adjustments.index') }}" wire:navigate class="font-body text-xs tracking-[0.15em] uppercase underline underline-offset-4" style="color:#9C9690;">Kembali</a>
    </div>

    <style>
        .veloure-form :where(input, select, textarea) {
            border-radius: 0;
            border: 1px solid #EDEAE4;
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            padding: 12px 14px;
            color: #1C1A18;
            background: white;
            width: 100%;
        }
        .veloure-form :where(input, select, textarea):focus {
            outline: none;
            border-color: #B89B6B;
            box-shadow: 0 0 0 1px #B89B6B;
        }
    </style>

    <form wire:submit="save" class="veloure-form max-w-4xl">
        <div class="bg-white border p-6 sm:p-8" style="border-color:#EDEAE4;">
            <p class="font-body text-xs tracking-[0.2em] uppercase mb-6" style="color:#B89B6B;">Detail Adjustment</p>

            <div class="grid sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Karyawan</label>
                    <select wire:model="employee_id">
                        <option value="">Pilih karyawan</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                </div>

                <div>
                    <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Tanggal</label>
                    <input type="date" wire:model="adjustment_date">
                    <x-input-error :messages="$errors->get('adjustment_date')" class="mt-2" />
                </div>

                <div>
                    <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Tipe</label>
                    <select wire:model="type">
                        <option value="commission">Commission</option>
                        <option value="bonus">Bonus</option>
                        <option value="deduction">Deduction</option>
                    </select>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Nominal</label>
                    <input type="number" min="0" step="1000" wire:model="amount">
                    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color:#3A3733;">Deskripsi</label>
                    <textarea wire:model="description" rows="4"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="font-body text-xs tracking-[0.15em] uppercase px-8 py-4 transition-opacity duration-300 hover:opacity-90" style="background:#1C1A18; color:#FAF9F7;">
                    Update Adjustment
                </button>
            </div>
        </div>
    </form>
</div>
