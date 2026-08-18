<div class="p-6 lg:p-10" style="background-color: #FAF9F7; min-height: 100vh;">

    {{-- Page Header --}}
    <div class="flex justify-between items-center flex-wrap gap-4 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="h-px w-8" style="background-color: #B89B6B;"></span>
                <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Attendance</span>
            </div>
            <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">Edit Absensi</h1>
            <p class="font-body text-sm mt-1" style="color: #9C9690;">Perbarui jam masuk dan jam pulang karyawan.</p>
        </div>

        <a
            href="{{ route('owner.attendances.index') }}"
            wire:navigate
            class="font-body text-xs tracking-[0.15em] uppercase underline underline-offset-4"
            style="color: #9C9690; text-decoration-color: #EDEAE4;"
            onmouseover="this.style.textDecorationColor='#B89B6B'"
            onmouseout="this.style.textDecorationColor='#EDEAE4'"
        >
            Kembali
        </a>
    </div>

    <style>
        .veloure-form :where(input[type=date], input[type=time], select) {
            border-radius: 8px;
            border: 1px solid #EDEAE4;
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            padding: 12px 14px;
            color: #1C1A18;
            background: #FAF9F7;
            width: 100%;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .veloure-form :where(input[type=date], input[type=time], select):focus {
            outline: none;
            border-color: #B89B6B;
            box-shadow: 0 0 0 3px rgba(184,155,107,0.12);
            background: white;
        }
    </style>

    <form wire:submit="save" class="veloure-form w-full">

        <div class="bg-white" style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 32px;">

            <p class="font-body text-xs tracking-[0.2em] uppercase mb-6" style="color: #B89B6B;">Data Absensi</p>

            <div class="grid sm:grid-cols-2 gap-5">

                {{-- Karyawan (full width) --}}
                <div class="sm:col-span-2">
                    <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color: #3A3733;">Karyawan</label>
                    <select wire:model="employee_id">
                        <option value="">— Pilih Karyawan —</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                </div>

                {{-- Tanggal (full width) --}}
                <div class="sm:col-span-2">
                    <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color: #3A3733;">Tanggal</label>
                    <input type="date" wire:model="attendance_date">
                    <x-input-error :messages="$errors->get('attendance_date')" class="mt-2" />
                </div>

                {{-- Check In --}}
                <div>
                    <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color: #3A3733;">Check In</label>
                    <input type="time" wire:model="checkin_time">
                    <x-input-error :messages="$errors->get('checkin_time')" class="mt-2" />
                </div>

                {{-- Check Out --}}
                <div>
                    <label class="block font-body text-xs tracking-[0.15em] uppercase mb-2" style="color: #3A3733;">Check Out</label>
                    <input type="time" wire:model="checkout_time">
                    <x-input-error :messages="$errors->get('checkout_time')" class="mt-2" />
                </div>

            </div>

            <div class="mt-8 flex justify-end">
                <button
                    type="submit"
                    class="font-body text-xs tracking-[0.15em] uppercase px-8 py-4 transition-all duration-200"
                    style="background: #1C1A18; color: #FAF9F7; border: none; border-radius: 8px; cursor: pointer;"
                    onmouseover="this.style.background='#3A3733'"
                    onmouseout="this.style.background='#1C1A18'"
                >
                    Update Absensi
                </button>
            </div>

        </div>

    </form>

</div>
