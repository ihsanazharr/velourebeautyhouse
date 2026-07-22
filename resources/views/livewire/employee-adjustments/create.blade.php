<div class="p-6">

    <h1
        style="
            font-size:28px;
            font-weight:bold;
            margin-bottom:20px;
        "
    >
        Tambah Adjustment
    </h1>

    <form wire:submit="save">

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

                <label>Tanggal</label>

                <input
                    type="date"
                    wire:model="adjustment_date"
                    style="
                        width:100%;
                        padding:10px;
                    "
                >

            </div>

            <div style="margin-bottom:16px;">

                <label>Jenis</label>

                <select
                    wire:model="type"
                    style="
                        width:100%;
                        padding:10px;
                    "
                >

                    <option value="bonus">
                        Bonus
                    </option>

                    <option value="commission">
                        Commission
                    </option>

                    <option value="deduction">
                        Deduction
                    </option>

                </select>

            </div>

            <div style="margin-bottom:16px;">

                <label>Nominal</label>

                <input
                    type="number"
                    wire:model="amount"
                    style="
                        width:100%;
                        padding:10px;
                    "
                >

            </div>

            <div style="margin-bottom:20px;">

                <label>Keterangan</label>

                <textarea
                    wire:model="description"
                    style="
                        width:100%;
                        padding:10px;
                    "
                ></textarea>

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
                Simpan Adjustment
            </button>

        </div>

    </form>

</div>