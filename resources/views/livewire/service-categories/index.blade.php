<div class="p-6">

    <div
        class="flex justify-between items-center mb-6"
    >

        <h1
            class="text-3xl font-bold"
        >
            Service Categories
        </h1>

        <a
            href="{{ route(
                'owner.service-categories.create'
            ) }}"
            class="
                bg-black
                text-white
                px-4
                py-2
                rounded
            "
        >
            + Tambah Kategori
        </a>

    </div>

    <table
        class="
            w-full
            border
        "
    >

        <thead>

            <tr>

                <th class="p-3 border">
                    Nama
                </th>

                <th class="p-3 border">
                    Slug
                </th>

                <th class="p-3 border">
                    Status
                </th>

                <th class="p-3 border">
                    Aksi
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse(
                $categories
                as $category
            )

                <tr>

                    <td class="p-3 border">
                        {{ $category->name }}
                    </td>

                    <td class="p-3 border">
                        {{ $category->slug }}
                    </td>

                    <td class="p-3 border">

                        @if(
                            $category->is_active
                        )

                            Aktif

                        @else

                            Nonaktif

                        @endif

                    </td>

                    <td class="p-3 border">

                        <a
                            href="{{
                                route(
                                    'owner.service-categories.edit',
                                    $category
                                )
                            }}"
                        >
                            Edit
                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="4"
                        align="center"
                    >
                        Belum ada kategori
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>