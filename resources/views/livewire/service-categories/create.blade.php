<div class="p-6">

    <h1
        style="
            font-size:28px;
            font-weight:bold;
            margin-bottom:20px;
        "
    >
        Tambah Kategori Layanan
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

                <label>Nama Kategori</label>

                <input
                    type="text"
                    wire:model="name"
                    style="
                        width:100%;
                        padding:10px;
                    "
                >

            </div>

            <div style="margin-bottom:20px;">

                <label>

                    <input
                        type="checkbox"
                        wire:model="is_active"
                    >

                    Aktif

                </label>

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
                Simpan
            </button>

        </div>

    </form>

</div>