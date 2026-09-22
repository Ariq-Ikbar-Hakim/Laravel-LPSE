@if(isset($activityLog) && $activityLog->isNotEmpty())
    <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3">Log Aktivitas</h3>
        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @foreach($activityLog as $activity)
                <div class="py-3 flex justify-between gap-4 text-sm">
                    <div>
                        <p class="font-semibold text-slate-800 dark:text-slate-200">{{ str_replace('_', ' ', $activity->description) }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">{{ $activity->causer->nama ?? 'Sistem' }} · {{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $activity->subject_type ? class_basename($activity->subject_type) : 'Aktivitas' }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif
