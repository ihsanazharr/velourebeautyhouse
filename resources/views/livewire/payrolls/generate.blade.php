<div class="p-6">

    <h1
        style="
            font-size:28px;
            font-weight:bold;
            margin-bottom:20px;
        "
    >
        Generate Payroll
    </h1>

    @if ($errors->any())

        <div
            style="
                background:#fee2e2;
                color:#991b1b;
                padding:12px;
                border-radius:8px;
                margin-bottom:16px;
            "
        >

            @foreach($errors->all() as $error)

                <div>{{ $error }}</div>

            @endforeach

        </div>

    @endif

    <form wire:submit="generate">

        <div
            style="
                background:white;
                padding:24px;
                border-radius:12px;
            "
        >

            <div style="margin-bottom:16px;">

                <label>Karyawan</label>

                <select
                    wire:model="employee_id"
                    style="
                        width:100%;
                        padding:10px;
                    "
                >
                    <option value="">
                        Pilih Karyawan
                    </option>

                    @foreach($employees as $employee)

                        <option
                            value="{{ $employee->id }}"
                        >
                            {{ $employee->full_name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div style="margin-bottom:16px;">

                <label>Bulan</label>

                <select
                    wire:model="month"
                    style="
                        width:100%;
                        padding:10px;
                    "
                >

                    @for($i = 1; $i <= 12; $i++)

                        <option value="{{ $i }}">
                            {{ $i }}
                        </option>

                    @endfor

                </select>

            </div>

            <div style="margin-bottom:20px;">

                <label>Tahun</label>

                <input
                    type="number"
                    wire:model="year"
                    style="
                        width:100%;
                        padding:10px;
                    "
                >

            </div>

            <button
                type="submit"
                style="
                    background:black;
                    color:white;
                    padding:12px 24px;
                    border-radius:8px;
                "
            >
                Generate Payroll
            </button>

        </div>

    </form>

</div>