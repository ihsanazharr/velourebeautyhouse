<div class="p-6">

    <h1 class="text-3xl font-bold mb-6">
        Tambah Produk
    </h1>

    <form wire:submit="save">

        <div style="background:white;padding:24px;border-radius:12px;">

            <div style="margin-bottom:16px;">

                <label>SKU</label>

                <input
                    type="text"
                    wire:model="sku"
                    style="width:100%;padding:10px;"
                >
            </div>

            <div style="margin-bottom:16px;">

                <label>Nama Produk</label>

                <input
                    type="text"
                    wire:model="name"
                    style="width:100%;padding:10px;"
                >
            </div>

            <div style="margin-bottom:16px;">

                <label>Kategori</label>

                <input
                    type="text"
                    wire:model="category"
                    placeholder="Hair, Nail, Eye Lash, dll"
                    style="width:100%;padding:10px;"
                >
            </div>

            <div style="margin-bottom:16px;">

                <label>Deskripsi</label>

                <textarea
                    wire:model="description"
                    style="width:100%;padding:10px;"
                ></textarea>

            </div>

            <div style="margin-bottom:16px;">

                <label>Satuan</label>

                <input
                    type="text"
                    wire:model="unit"
                    style="width:100%;padding:10px;"
                >
            </div>

            <div style="margin-bottom:16px;">

                <label>Stok Saat Ini</label>

                <input
                    type="number"
                    wire:model="current_stock"
                    style="width:100%;padding:10px;"
                >
            </div>

            <div style="margin-bottom:16px;">

                <label>Minimum Stok</label>

                <input
                    type="number"
                    wire:model="minimum_stock"
                    style="width:100%;padding:10px;"
                >
            </div>

            <div style="margin-bottom:16px;">

                <label>Alert Stok</label>

                <input
                    type="number"
                    wire:model="alert_stock"
                    style="width:100%;padding:10px;"
                >
            </div>

            <div style="margin-bottom:16px;">

                <label>Harga Modal</label>

                <input
                    type="number"
                    wire:model="cost_price"
                    style="width:100%;padding:10px;"
                >
            </div>

            <div style="margin-bottom:16px;">

                <label>Harga Jual</label>

                <input
                    type="number"
                    wire:model="selling_price"
                    style="width:100%;padding:10px;"
                >
            </div>

            <div style="margin-bottom:16px;">

                <label>Supplier</label>

                <input
                    type="text"
                    wire:model="supplier"
                    style="width:100%;padding:10px;"
                >
            </div>

            <div style="margin-bottom:24px;">

                <label>
                    <input
                        type="checkbox"
                        wire:model="is_active"
                    >

                    Produk Aktif
                </label>

            </div>

            <button
                type="submit"
                style="
                    background:#000;
                    color:#fff;
                    padding:12px 24px;
                    border-radius:8px;
                "
            >
                Simpan Produk
            </button>

        </div>

    </form>

</div>