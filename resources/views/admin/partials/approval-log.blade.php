@props(['activityLog', 'title', 'routeName'])

<div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
        <form method="GET" action="{{ route($routeName) }}" class="flex flex-wrap gap-2">
            <input name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, atau keterangan" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-sm">
            <select name="status" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-sm">
                <option value="">Semua status</option>
                <option value="disetujui" @selected(request('status') === 'disetujui')>Disetujui</option>
                <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
            </select>
            <select name="year" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-sm">
                <option value="">Semua tahun</option>
                @for($year = now()->year; $year >= now()->year - 4; $year--)
                    <option value="{{ $year }}" @selected((string) request('year') === (string) $year)>{{ $year }}</option>
                @endfor
            </select>
            <button class="px-3 py-2 rounded-lg bg-indigo-600 text-white text-sm">Filter</button>
        </form>
    </div>
    @if($activityLog->isEmpty())
        <p class="text-sm text-slate-500">Belum ada catatan persetujuan.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead><tr class="text-left text-slate-500 border-b dark:border-slate-800"><th class="py-2 pr-3">No.</th><th class="py-2 pr-3">Status</th><th class="py-2 pr-3">Pengguna</th><th class="py-2">Waktu</th></tr></thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($activityLog as $activity)
                        @php
                            $properties = is_array($activity->properties) ? $activity->properties : (method_exists($activity->properties, 'toArray') ? $activity->properties->toArray() : []);
                            $nama = $properties['nama'] ?? $properties['dari'] ?? 'Pengguna';
                        @endphp
                        <tr><td class="py-2 pr-3">{{ $activityLog->firstItem() + $loop->index }}</td><td class="py-2 pr-3"><span class="font-semibold {{ str_contains($activity->description, 'DITOLAK') ? 'text-red-500' : 'text-emerald-500' }}">{{ str_contains($activity->description, 'DITOLAK') ? 'Ditolak' : 'Disetujui' }}</span></td><td class="py-2 pr-3 text-slate-700 dark:text-slate-200">{{ $nama }}</td><td class="py-2 text-slate-500">{{ $activity->created_at->format('d/m/Y H:i') }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $activityLog->links() }}</div>
    @endif
</div>
