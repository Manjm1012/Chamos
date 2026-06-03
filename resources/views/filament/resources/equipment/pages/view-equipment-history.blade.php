<x-filament-panels::page>
    @php
        $total = max((int) ($summary['total'] ?? 0), 1);
        $completedPct = (int) round(((int) ($summary['completed'] ?? 0) / $total) * 100);
        $inProgressPct = (int) round(((int) ($summary['in_progress'] ?? 0) / $total) * 100);
        $pendingPct = (int) round(((int) ($summary['pending'] ?? 0) / $total) * 100);
        $cancelledPct = (int) round(((int) ($summary['cancelled'] ?? 0) / $total) * 100);
    @endphp

    <div class="space-y-6" x-data="{ statusFilter: 'all' }">
        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-gray-500">{{ __('ui.history.dashboard_title') }}</p>
                    <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        {{ __('ui.history.title', ['unit' => $record->unit_number]) }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        {{ __('ui.history.subtitle') }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-[11px] uppercase tracking-wide text-gray-500">{{ __('ui.history.type') }}</p>
                        <p class="font-semibold capitalize text-gray-900 dark:text-white">{{ $record->type }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-[11px] uppercase tracking-wide text-gray-500">{{ __('ui.history.status') }}</p>
                        <p class="font-semibold capitalize text-gray-900 dark:text-white">{{ str_replace('_', ' ', $record->status) }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-[11px] uppercase tracking-wide text-gray-500">{{ __('ui.history.brand') }}</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $record->brand ?: __('ui.history.undefined') }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-[11px] uppercase tracking-wide text-gray-500">{{ __('ui.history.model') }}</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $record->model ?: __('ui.history.undefined') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 lg:grid-cols-12">
            <div class="space-y-4 lg:col-span-8">
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                        <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('ui.history.total') }}</p>
                        <p class="mt-1 text-3xl font-black text-gray-900 dark:text-white">{{ $summary['total'] }}</p>
                    </article>
                    <article class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm dark:border-emerald-800/30 dark:bg-emerald-900/20">
                        <p class="text-xs uppercase tracking-wide text-emerald-700 dark:text-emerald-300">{{ __('ui.history.completed') }}</p>
                        <p class="mt-1 text-3xl font-black text-emerald-700 dark:text-emerald-300">{{ $summary['completed'] }}</p>
                    </article>
                    <article class="rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm dark:border-amber-800/30 dark:bg-amber-900/20">
                        <p class="text-xs uppercase tracking-wide text-amber-700 dark:text-amber-300">{{ __('ui.history.in_progress') }}</p>
                        <p class="mt-1 text-3xl font-black text-amber-700 dark:text-amber-300">{{ $summary['in_progress'] }}</p>
                    </article>
                    <article class="rounded-xl border border-rose-200 bg-rose-50 p-4 shadow-sm dark:border-rose-800/30 dark:bg-rose-900/20">
                        <p class="text-xs uppercase tracking-wide text-rose-700 dark:text-rose-300">{{ __('ui.history.pending') }}</p>
                        <p class="mt-1 text-3xl font-black text-rose-700 dark:text-rose-300">{{ $summary['pending'] }}</p>
                    </article>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            class="rounded-full border border-gray-300 px-4 py-1.5 text-xs font-semibold transition"
                            :class="statusFilter === 'all' ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900' : 'bg-white text-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700'"
                            x-on:click="statusFilter = 'all'"
                        >{{ __('ui.history.filters_all') }}</button>
                        <button
                            type="button"
                            class="rounded-full border border-emerald-300 px-4 py-1.5 text-xs font-semibold transition"
                            :class="statusFilter === 'completed' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300'"
                            x-on:click="statusFilter = 'completed'"
                        >{{ __('ui.history.completed') }}</button>
                        <button
                            type="button"
                            class="rounded-full border border-amber-300 px-4 py-1.5 text-xs font-semibold transition"
                            :class="statusFilter === 'in_progress' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300'"
                            x-on:click="statusFilter = 'in_progress'"
                        >{{ __('ui.history.in_progress') }}</button>
                        <button
                            type="button"
                            class="rounded-full border border-rose-300 px-4 py-1.5 text-xs font-semibold transition"
                            :class="statusFilter === 'pending' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700 dark:bg-rose-900/20 dark:text-rose-300'"
                            x-on:click="statusFilter = 'pending'"
                        >{{ __('ui.history.pending') }}</button>
                        <button
                            type="button"
                            class="rounded-full border border-slate-300 px-4 py-1.5 text-xs font-semibold transition"
                            :class="statusFilter === 'cancelled' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700'"
                            x-on:click="statusFilter = 'cancelled'"
                        >{{ __('ui.history.cancelled') }}</button>
                    </div>
                </div>
            </div>

            <aside class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900 lg:col-span-4">
                <h2 class="text-sm font-bold uppercase tracking-[0.12em] text-gray-500">{{ __('ui.history.distribution') }}</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <div class="mb-1 flex items-center justify-between text-xs">
                            <span class="font-semibold text-emerald-700 dark:text-emerald-300">{{ __('ui.history.completed') }}</span>
                            <span class="text-gray-500">{{ $completedPct }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-800"><div class="h-2 rounded-full bg-emerald-500" style="width: {{ $completedPct }}%"></div></div>
                    </div>
                    <div>
                        <div class="mb-1 flex items-center justify-between text-xs">
                            <span class="font-semibold text-amber-700 dark:text-amber-300">{{ __('ui.history.in_progress') }}</span>
                            <span class="text-gray-500">{{ $inProgressPct }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-800"><div class="h-2 rounded-full bg-amber-500" style="width: {{ $inProgressPct }}%"></div></div>
                    </div>
                    <div>
                        <div class="mb-1 flex items-center justify-between text-xs">
                            <span class="font-semibold text-rose-700 dark:text-rose-300">{{ __('ui.history.pending') }}</span>
                            <span class="text-gray-500">{{ $pendingPct }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-800"><div class="h-2 rounded-full bg-rose-500" style="width: {{ $pendingPct }}%"></div></div>
                    </div>
                    <div>
                        <div class="mb-1 flex items-center justify-between text-xs">
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ __('ui.history.cancelled') }}</span>
                            <span class="text-gray-500">{{ $cancelledPct }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-800"><div class="h-2 rounded-full bg-slate-500" style="width: {{ $cancelledPct }}%"></div></div>
                    </div>
                </div>
            </aside>
        </section>

        @forelse ($maintenancesByMonth as $monthLabel => $maintenances)
            <section class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-extrabold uppercase tracking-[0.12em] text-gray-500">{{ $monthLabel }}</h3>
                    <span class="rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-semibold text-gray-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ __('ui.history.records', ['count' => $maintenances->count()]) }}</span>
                </div>

                <div class="space-y-3">
                    @foreach ($maintenances as $maintenance)
                        @php
                            $statusTheme = match ($maintenance->workflow_status) {
                                'completed' => [
                                    'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300',
                                    'label' => __('ui.history.finished'),
                                ],
                                'in_progress' => [
                                    'badge' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300',
                                    'label' => __('ui.history.processing'),
                                ],
                                'cancelled' => [
                                    'badge' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                    'label' => __('ui.history.cancelled_status'),
                                ],
                                default => [
                                    'badge' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/20 dark:text-rose-300',
                                    'label' => __('ui.history.pending'),
                                ],
                            };
                        @endphp

                        <article
                            x-show="statusFilter === 'all' || statusFilter === '{{ $maintenance->workflow_status }}'"
                            x-transition.opacity
                            class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900"
                        >
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusTheme['badge'] }}">{{ $statusTheme['label'] }}</span>
                                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300">
                                            {{ $maintenance->type === 'preventive' ? __('ui.history.scheduled_type') : __('ui.history.reactive_type') }}
                                        </span>
                                    </div>
                                    <h4 class="mt-2 text-base font-bold text-gray-900 dark:text-white">{{ __('ui.history.maintenance_id', ['id' => $maintenance->id]) }}</h4>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $maintenance->date->format('d M Y') }} · {{ optional($maintenance->created_at)->format('H:i') ?: 'N/A' }}
                                    </p>
                                </div>

                                <div class="rounded-lg bg-gray-100 px-4 py-2 text-right dark:bg-gray-800">
                                    <p class="text-[11px] uppercase tracking-wide text-gray-500">{{ __('ui.history.cost') }}</p>
                                    <p class="text-lg font-black text-gray-900 dark:text-white">${{ number_format((float) $maintenance->cost, 2) }}</p>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800">
                                    <p class="text-[11px] uppercase tracking-wide text-gray-500">{{ __('ui.history.odometer') }}</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($maintenance->odometer_hours) }}</p>
                                </div>
                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800">
                                    <p class="text-[11px] uppercase tracking-wide text-gray-500">{{ __('ui.history.responsible') }}</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $maintenance->performed_by ?: __('ui.history.unassigned') }}</p>
                                </div>
                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800">
                                    <p class="text-[11px] uppercase tracking-wide text-gray-500">{{ __('ui.history.next_date') }}</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ optional($maintenance->next_maintenance_date)->format('d M Y') ?: __('ui.history.no_plan') }}</p>
                                </div>
                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800">
                                    <p class="text-[11px] uppercase tracking-wide text-gray-500">{{ __('ui.history.next_odometer') }}</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $maintenance->next_maintenance_odometer ? number_format($maintenance->next_maintenance_odometer) : __('ui.history.no_plan') }}</p>
                                </div>
                            </div>

                            <div class="mt-4 rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-900">
                                <p class="text-[11px] uppercase tracking-wide text-gray-500">{{ __('ui.history.work_done') }}</p>
                                <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $maintenance->description }}</p>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <a
                                    href="{{ \App\Filament\Resources\Maintenances\MaintenanceResource::getUrl('edit', ['record' => $maintenance]) }}"
                                    class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                                >
                                    {{ __('ui.history.edit') }}
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @empty
            <section class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ __('ui.history.empty_title') }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ __('ui.history.empty_subtitle') }}</p>
            </section>
        @endforelse
    </div>
</x-filament-panels::page>
