<div class="p-6 lg:p-10" style="background-color: #FAF9F7; min-height: 100vh;">

    {{-- Page Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-2">
            <span class="h-px w-8" style="background-color: #B89B6B;"></span>
            <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #B89B6B;">Manajemen</span>
        </div>
        <h1 class="font-display text-4xl tracking-[0.03em]" style="color: #1C1A18;">
            Attendance Kiosk
        </h1>
        <p class="font-body text-sm mt-1" style="color: #9C9690;">Rekam kehadiran karyawan dengan foto selfie.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div
            class="font-body text-sm mb-6 px-5 py-4 border-l-2 flex items-center gap-3"
            style="background: white; border-color: #5C7A5C; color: #3A3733; border-radius: 0 6px 6px 0;"
        >
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="8" fill="#5C7A5C"/><path d="M4.5 8.5L7 11L11.5 5.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div
            class="font-body text-sm mb-6 px-5 py-4 border-l-2 flex items-center gap-3"
            style="background: white; border-color: #B5402C; color: #3A3733; border-radius: 0 6px 6px 0;"
        >
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="8" fill="#B5402C"/><path d="M5.5 5.5L10.5 10.5M10.5 5.5L5.5 10.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Main Grid: Clock + Form --}}
    <div class="grid lg:grid-cols-2 gap-6 mb-12">

        {{-- Left Column: Clock & Employee Selector --}}
        <div class="flex flex-col gap-5">

            {{-- Live Clock Card --}}
            <div
                class="bg-white flex flex-col items-center justify-center text-center"
                style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 36px 24px;"
            >
                <div
                    x-data="{
                        time: '',
                        date: '',
                        updateClock() {
                            const now = new Date();
                            this.time = now.toLocaleTimeString('id-ID', { hour12: false, timeZone: 'Asia/Jakarta' });
                            this.date = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Jakarta' });
                        }
                    }"
                    x-init="updateClock(); setInterval(() => updateClock(), 1000);"
                >
                    <div
                        x-text="time"
                        class="font-display tabular-nums"
                        style="font-size: 56px; color: #1C1A18; letter-spacing: 0.05em; line-height: 1;"
                    ></div>
                    <div
                        x-text="date"
                        class="font-body text-xs tracking-[0.12em] uppercase mt-3"
                        style="color: #9C9690;"
                    ></div>
                </div>
            </div>

            {{-- Employee Selector Card --}}
            <div
                class="bg-white"
                style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 28px;"
            >
                <p class="font-body text-xs tracking-[0.2em] uppercase mb-4" style="color: #B89B6B;">Pilih Karyawan</p>

                <label class="font-body text-xs tracking-[0.15em] uppercase block mb-2" style="color: #3A3733;">Karyawan</label>

                <select
                    wire:model.live="employee_id"
                    class="font-body text-sm w-full focus:outline-none transition-all duration-200"
                    style="
                        border: 1px solid #EDEAE4;
                        border-radius: 8px;
                        padding: 12px 14px;
                        color: #1C1A18;
                        background: #FAF9F7;
                        appearance: none;
                        background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8' fill='none'%3E%3Cpath d='M1 1L6 7L11 1' stroke='%239C9690' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E\");
                        background-repeat: no-repeat;
                        background-position: right 14px center;
                        padding-right: 40px;
                    "
                    onfocus="this.style.borderColor='#B89B6B'; this.style.boxShadow='0 0 0 3px rgba(184,155,107,0.12)';"
                    onblur="this.style.borderColor='#EDEAE4'; this.style.boxShadow='none';"
                >
                    <option value="">— Pilih Karyawan —</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                    @endforeach
                </select>

                @if($todayAttendance)
                    <div
                        class="mt-5 grid grid-cols-2 gap-3"
                    >
                        <div
                            class="p-4 text-center"
                            style="background: #F0F5F0; border-radius: 8px;"
                        >
                            <p class="font-body text-[10px] tracking-[0.15em] uppercase mb-1" style="color: #5C7A5C;">Check In</p>
                            <p class="font-display text-xl" style="color: #3A4F3A;">
                                {{ $todayAttendance->checkin_time?->format('H:i') ?? '—' }}
                            </p>
                        </div>
                        <div
                            class="p-4 text-center"
                            style="background: {{ $todayAttendance->checkout_time ? '#FBF3E8' : '#FAFAFA' }}; border-radius: 8px;"
                        >
                            <p class="font-body text-[10px] tracking-[0.15em] uppercase mb-1" style="color: {{ $todayAttendance->checkout_time ? '#B89B6B' : '#9C9690' }};">Check Out</p>
                            <p class="font-display text-xl" style="color: {{ $todayAttendance->checkout_time ? '#7A6030' : '#BDBDB8' }};">
                                {{ $todayAttendance->checkout_time?->format('H:i') ?? '—' }}
                            </p>
                        </div>
                    </div>
                @endif

                <div class="flex gap-3 mt-5">
                    <button
                        wire:click="clockIn"
                        @disabled($todayAttendance)
                        class="flex-1 font-body text-xs tracking-[0.15em] uppercase transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed"
                        style="
                            background: #5C7A5C;
                            color: white;
                            padding: 13px 0;
                            border: none;
                            border-radius: 8px;
                            cursor: pointer;
                        "
                        onmouseover="if(!this.disabled) this.style.opacity='0.88'"
                        onmouseout="this.style.opacity='1'"
                    >
                        ↑ Check In
                    </button>
                    <button
                        wire:click="clockOut"
                        @disabled(!$todayAttendance || $todayAttendance?->checkout_time)
                        class="flex-1 font-body text-xs tracking-[0.15em] uppercase transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed"
                        style="
                            background: #B5402C;
                            color: white;
                            padding: 13px 0;
                            border: none;
                            border-radius: 8px;
                            cursor: pointer;
                        "
                        onmouseover="if(!this.disabled) this.style.opacity='0.88'"
                        onmouseout="this.style.opacity='1'"
                    >
                        ↓ Check Out
                    </button>
                </div>
            </div>

        </div>

        {{-- Right Column: Camera --}}
        <div
            x-data="{
                wire: $wire,
                preview: null,
                async startCamera() {
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } },
                            audio: false,
                        });
                        this.$refs.video.srcObject = stream;
                    } catch (e) {
                        console.error('Camera error:', e);
                    }
                },
                capture() {
                    const video  = this.$refs.video;
                    const canvas = this.$refs.canvas;
                    canvas.width  = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);
                    const image = canvas.toDataURL('image/jpeg', 0.9);
                    this.preview = image;
                    const stream = this.$refs.video.srcObject;
                    if (stream) stream.getTracks().forEach(t => t.stop());
                    this.wire.set('capturedImage', image);
                }
            }"
            x-init="startCamera()"
            class="bg-white flex flex-col"
            style="border: 1px solid #EDEAE4; border-radius: 12px; padding: 28px;"
        >
            <p class="font-body text-xs tracking-[0.2em] uppercase mb-4" style="color: #B89B6B;">Kamera Selfie</p>

            {{-- Camera viewport --}}
            <div
                class="relative flex items-center justify-center overflow-hidden flex-1"
                style="
                    background: #1C1A18;
                    border-radius: 10px;
                    min-height: 280px;
                    max-height: 380px;
                "
            >
                <video
                    x-ref="video"
                    autoplay
                    playsinline
                    muted
                    class="w-full h-full"
                    style="
                        object-fit: cover;
                        border-radius: 10px;
                        display: block;
                    "
                ></video>

                {{-- Corner frame decorations --}}
                <div style="position:absolute; top:12px; left:12px; width:22px; height:22px; border-top:2px solid #B89B6B; border-left:2px solid #B89B6B; border-radius:3px 0 0 0; pointer-events:none;"></div>
                <div style="position:absolute; top:12px; right:12px; width:22px; height:22px; border-top:2px solid #B89B6B; border-right:2px solid #B89B6B; border-radius:0 3px 0 0; pointer-events:none;"></div>
                <div style="position:absolute; bottom:12px; left:12px; width:22px; height:22px; border-bottom:2px solid #B89B6B; border-left:2px solid #B89B6B; border-radius:0 0 0 3px; pointer-events:none;"></div>
                <div style="position:absolute; bottom:12px; right:12px; width:22px; height:22px; border-bottom:2px solid #B89B6B; border-right:2px solid #B89B6B; border-radius:0 0 3px 0; pointer-events:none;"></div>

                <canvas x-ref="canvas" style="display:none;"></canvas>
            </div>

            {{-- Preview thumbnail --}}
            <template x-if="preview">
                <div class="mt-4 flex items-center gap-3">
                    <img
                        :src="preview"
                        style="width:72px; height:72px; object-fit:cover; border-radius:8px; border:2px solid #5C7A5C;"
                    >
                    <div>
                        <p class="font-body text-xs tracking-[0.1em] uppercase" style="color:#5C7A5C;">Foto Diambil</p>
                        <p class="font-body text-xs mt-0.5" style="color:#9C9690;">Siap untuk check in / out</p>
                    </div>
                </div>
            </template>

            {{-- Capture Button --}}
            <button
                type="button"
                @click="capture()"
                class="mt-5 w-full font-body text-xs tracking-[0.15em] uppercase transition-all duration-200"
                style="
                    background: #1C1A18;
                    color: #FAF9F7;
                    padding: 14px;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    letter-spacing: 0.15em;
                "
                onmouseover="this.style.background='#3A3733'"
                onmouseout="this.style.background='#1C1A18'"
            >
                📷 &nbsp; Ambil Foto
            </button>

            <p class="font-body text-xs text-center mt-3" style="color:#9C9690;">
                Pastikan wajah terlihat jelas sebelum check in / out
            </p>
        </div>

    </div>

    {{-- Riwayat Attendance --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <span class="h-px w-6" style="background-color: #B89B6B;"></span>
            <span class="font-body text-[11px] tracking-[0.3em] uppercase" style="color: #9C9690;">Data</span>
        </div>
        <h2 class="font-display text-2xl tracking-[0.02em]" style="color: #1C1A18;">Riwayat Attendance</h2>
    </div>

    <div class="bg-white overflow-hidden" style="border: 1px solid #EDEAE4; border-radius: 12px;">
        <div class="overflow-x-auto">
            <table width="100%" cellpadding="0" cellspacing="0" class="font-body text-sm" style="border-collapse: collapse;">

                <thead>
                    <tr style="background: #FAF9F7; border-bottom: 1px solid #EDEAE4;">
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Tanggal</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Karyawan</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Check In</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Check Out</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Foto Masuk</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Foto Pulang</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Terlambat</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Potongan</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Lembur</th>
                        <th class="text-left p-4 text-xs tracking-[0.1em] uppercase" style="color: #9C9690; font-weight: 500;">Uang Makan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($attendances as $attendance)
                        <tr style="border-bottom: 1px solid #EDEAE4; transition: background 0.15s;"
                            onmouseover="this.style.background='#FAFAF8'"
                            onmouseout="this.style.background='transparent'"
                        >
                            <td class="p-4" style="color: #3A3733;">
                                {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}
                            </td>

                            <td class="p-4" style="color: #1C1A18; font-weight: 500;">
                                {{ $attendance->employee?->full_name }}
                            </td>

                            <td class="p-4">
                                @if($attendance->checkin_time)
                                    <span
                                        class="font-body text-xs tracking-[0.05em] px-2 py-1 inline-block"
                                        style="background: #F0F5F0; color: #5C7A5C; border-radius: 4px;"
                                    >
                                        {{ $attendance->checkin_time->format('H:i') }}
                                    </span>
                                @else
                                    <span style="color:#BDBDB8;">—</span>
                                @endif
                            </td>

                            <td class="p-4">
                                @if($attendance->checkout_time)
                                    <span
                                        class="font-body text-xs tracking-[0.05em] px-2 py-1 inline-block"
                                        style="background: #FBF3E8; color: #B89B6B; border-radius: 4px;"
                                    >
                                        {{ $attendance->checkout_time->format('H:i') }}
                                    </span>
                                @else
                                    <span style="color:#BDBDB8;">—</span>
                                @endif
                            </td>

                            <td class="p-4">
                                @if($attendance->checkin_photo)
                                    <a href="{{ asset('storage/' . $attendance->checkin_photo) }}" target="_blank">
                                        <img
                                            src="{{ asset('storage/' . $attendance->checkin_photo) }}"
                                            style="width: 52px; height: 52px; object-fit: cover; border-radius: 6px; border: 1px solid #EDEAE4; transition: transform 0.2s;"
                                            onmouseover="this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.transform='scale(1)'"
                                        >
                                    </a>
                                @else
                                    <span style="color: #BDBDB8; font-size: 11px;">—</span>
                                @endif
                            </td>

                            <td class="p-4">
                                @if($attendance->checkout_photo)
                                    <a href="{{ asset('storage/' . $attendance->checkout_photo) }}" target="_blank">
                                        <img
                                            src="{{ asset('storage/' . $attendance->checkout_photo) }}"
                                            style="width: 52px; height: 52px; object-fit: cover; border-radius: 6px; border: 1px solid #EDEAE4; transition: transform 0.2s;"
                                            onmouseover="this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.transform='scale(1)'"
                                        >
                                    </a>
                                @else
                                    <span style="color: #BDBDB8; font-size: 11px;">—</span>
                                @endif
                            </td>

                            <td class="p-4">
                                @if($attendance->late_minutes > 0)
                                    <span
                                        class="font-body text-xs px-2 py-1 inline-block"
                                        style="background: #FBEAE7; color: #B5402C; border-radius: 4px;"
                                    >
                                        {{ $attendance->late_minutes }} mnt
                                    </span>
                                @else
                                    <span class="font-body text-xs" style="color: #5C7A5C;">Tepat Waktu</span>
                                @endif
                            </td>

                            <td class="p-4 font-body text-xs" style="color: #B5402C;">
                                @if($attendance->late_penalty > 0)
                                    Rp {{ number_format($attendance->late_penalty) }}
                                @else
                                    <span style="color:#BDBDB8;">—</span>
                                @endif
                            </td>

                            <td class="p-4 font-body text-xs" style="color: #5C7A5C;">
                                @if($attendance->overtime_amount > 0)
                                    Rp {{ number_format($attendance->overtime_amount) }}
                                @else
                                    <span style="color:#BDBDB8;">—</span>
                                @endif
                            </td>

                            <td class="p-4 font-body text-xs" style="color: #3A3733;">
                                @if($attendance->meal_allowance > 0)
                                    Rp {{ number_format($attendance->meal_allowance) }}
                                @else
                                    <span style="color:#BDBDB8;">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" align="center" class="font-body text-sm" style="padding: 56px 24px; color: #9C9690;">
                                <div style="opacity:0.5; font-size: 28px; margin-bottom: 8px;">🗓</div>
                                Belum ada data attendance
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    <div class="mt-6 font-body">
        {{ $attendances->links() }}
    </div>



</div>