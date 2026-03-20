@extends('layouts.app')

@section('content')
    <div class="py-6 space-y-6">
        {{-- HEADER --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">
                    Dashboard
                </h1>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Overview of FileCollect system
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2">

                <a href="{{ route('document-requests.create') }}"
                    class="w-full lg:w-auto inline-flex items-center justify-center gap-2
        h-10 px-4 sm:px-5 text-sm font-medium border
        text-primary-600 border-primary-600/30 bg-primary-600/10
        hover:bg-primary-600 hover:text-white
        dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
        dark:hover:bg-primary-500 dark:hover:text-white
        transition cursor-pointer">

                    <x-lucide-plus class="w-4 h-4" />
                    Create Request

                </a>


                <a href="{{ route('clients.create') }}"
                    class="w-full lg:w-auto inline-flex items-center justify-center gap-2
        h-10 px-4 sm:px-5 text-sm font-medium border
        text-primary-600 border-primary-600/30 bg-primary-600/10
        hover:bg-primary-600 hover:text-white
        dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
        dark:hover:bg-primary-500 dark:hover:text-white
        transition cursor-pointer">

                    <x-lucide-user-plus class="w-4 h-4" />
                    Add Client

                </a>

            </div>

        </div>


        {{-- STATS --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">

            {{-- Requests --}}
            <div class="border border-blue-200 dark:border-blue-900 bg-blue-50/60 dark:bg-blue-950/20 p-5">

                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-blue-700 dark:text-blue-400">Requests</p>
                    <x-lucide-file-text class="w-4 h-4 text-blue-500" />
                </div>

                <p class="text-3xl font-semibold text-blue-600 dark:text-blue-400">
                    {{ $totalRequests }}
                </p>

                @if ($requestLimit > 0)
                    <p class="text-xs text-blue-500 mt-1">
                        of {{ $requestLimit }}
                    </p>

                    <div class="mt-2 h-1 bg-blue-100 dark:bg-blue-900/40">
                        <div class="h-1 bg-blue-500" style="width: {{ ($totalRequests / $requestLimit) * 100 }}%">
                        </div>
                    </div>
                @endif

            </div>


            {{-- Pending --}}
            <div class="border border-yellow-200 dark:border-yellow-900 bg-yellow-50/60 dark:bg-yellow-950/20 p-5">

                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-yellow-700 dark:text-yellow-400">Pending</p>
                    <x-lucide-clock class="w-4 h-4 text-yellow-500" />
                </div>

                <p class="text-3xl font-semibold text-yellow-600 dark:text-yellow-400">
                    {{ $pendingRequests }}
                </p>

                <div class="mt-2 h-1 bg-yellow-100 dark:bg-yellow-900/40">
                    <div class="h-1 bg-yellow-500" style="width: {{ $pendingPercent }}%">
                    </div>
                </div>

            </div>


            {{-- Completed --}}
            <div class="border border-green-200 dark:border-green-900 bg-green-50/60 dark:bg-green-950/20 p-5">

                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-green-700 dark:text-green-400">Completed</p>
                    <x-lucide-check-circle class="w-4 h-4 text-green-500" />
                </div>

                <p class="text-3xl font-semibold text-green-600 dark:text-green-400">
                    {{ $completedRequests }}
                </p>

                <div class="mt-2 h-1 bg-green-100 dark:bg-green-900/40">
                    <div class="h-1 bg-green-500" style="width: {{ $completedPercent }}%">
                    </div>
                </div>

            </div>


            {{-- Overdue --}}
            <div class="border border-red-200 dark:border-red-900 bg-red-50/60 dark:bg-red-950/20 p-5">

                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-red-700 dark:text-red-400">Overdue</p>
                    <x-lucide-alert-triangle class="w-4 h-4 text-red-500" />
                </div>

                <p class="text-3xl font-semibold text-red-600 dark:text-red-400">
                    {{ $overdueRequests }}
                </p>

                <div class="mt-2 h-1 bg-red-100 dark:bg-red-900/40">
                    <div class="h-1 bg-red-500" style="width: {{ $overduePercent }}%">
                    </div>
                </div>

            </div>


            {{-- Clients --}}
            <div class="border border-indigo-200 dark:border-indigo-900 bg-indigo-50/60 dark:bg-indigo-950/20 p-5">

                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-indigo-700 dark:text-indigo-400">Clients</p>
                    <x-lucide-users class="w-4 h-4 text-indigo-500" />
                </div>

                <p class="text-3xl font-semibold text-indigo-600 dark:text-indigo-400">
                    {{ $clients }}
                </p>

                @if ($clientLimit > 0)
                    <p class="text-xs text-indigo-500 mt-1">
                        of {{ $clientLimit }}
                    </p>

                    <div class="mt-2 h-1 bg-indigo-100 dark:bg-indigo-900/40">
                        <div class="h-1 bg-indigo-500" style="width: {{ ($clients / $clientLimit) * 100 }}%">
                        </div>
                    </div>
                @endif

            </div>


            {{-- Documents --}}
            <div class="border border-purple-200 dark:border-purple-900 bg-purple-50/60 dark:bg-purple-950/20 p-5">

                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-purple-700 dark:text-purple-400">Documents</p>
                    <x-lucide-folder class="w-4 h-4 text-purple-500" />
                </div>

                <p class="text-3xl font-semibold text-purple-600 dark:text-purple-400">
                    {{ $documents }}
                </p>

                @if ($documentLimit > 0)
                    <p class="text-xs text-purple-500 mt-1">
                        of {{ $documentLimit }}
                    </p>

                    <div class="mt-2 h-1 bg-purple-100 dark:bg-purple-900/40">
                        <div class="h-1 bg-purple-500" style="width: {{ ($documents / $documentLimit) * 100 }}%">
                        </div>
                    </div>
                @endif

            </div>

        </div>


        {{-- CHARTS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 p-6 shadow-sm">

                <h2 class="text-sm font-semibold mb-4 flex items-center gap-2">
                    <x-lucide-upload class="w-4 h-4 text-gray-400" />
                    Uploads (Last 7 Days)
                </h2>

                <div class="h-48 sm:h-64">
                    <canvas id="uploadsChart"></canvas>
                </div>

            </div>


            <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 p-6 shadow-sm">

                <h2 class="text-sm font-semibold mb-4 flex items-center gap-2">
                    <x-lucide-hard-drive class="w-4 h-4 text-gray-400" />
                    Storage Usage
                </h2>

                <div class="h-48 sm:h-64">
                    <canvas id="storageChart"></canvas>
                </div>

                <div class="mt-4 space-y-1 text-xs text-gray-500">

                    <p>
                        <strong class="text-gray-900 dark:text-gray-200">
                            {{ number_format($storageUsed / 1024 / 1024, 2) }} MB
                        </strong>
                        used
                    </p>

                    <p>
                        {{ number_format($storageRemaining / 1024 / 1024 / 1024, 2) }} GB remaining
                    </p>

                    <p>
                        {{ $storageUsagePercent }}% of plan used
                    </p>

                </div>

                @if ($storageUsagePercent >= 90)
                    <div class="mt-3 text-xs text-red-500 flex items-center gap-1">
                        <x-lucide-alert-triangle class="w-4 h-4" />
                        Storage almost full. Consider upgrading your plan.
                    </div>
                @endif

            </div>

        </div>

        {{-- RECENT REQUESTS --}}
        <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 shadow-sm">

            <div class="p-4 border-b border-gray-200 dark:border-neutral-800">
                <h2 class="text-sm font-semibold">
                    Recent Requests
                </h2>
            </div>

            <div class="overflow-x-auto">
                <div class="min-w-[600px]">
                    {{-- HEADER --}}
                    <div
                        class="grid grid-cols-[2fr_1fr_1fr_1fr] px-4 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-neutral-800">
                        <div>Request</div>
                        <div>Client</div>
                        <div>Status</div>
                        <div>Due</div>
                    </div>


                    {{-- ROWS --}}
                    <div class="divide-y divide-gray-200 dark:divide-neutral-800">

                        @forelse($recentRequests as $req)
                            <a href="{{ route('document-requests.show', $req->id) }}"
                                class="block grid grid-cols-[2fr_1fr_1fr_1fr] items-center px-4 py-3 text-sm hover:bg-gray-50 dark:hover:bg-neutral-800 transition cursor-pointer">

                                {{-- Request --}}
                                <div>

                                    {{-- Highlighted Number --}}
                                    <div class="font-mono text-sm font-semibold text-primary-600 dark:text-primary-400">
                                        {{ $req->request_number }}
                                    </div>

                                    <div class="text-[11px] text-gray-400">
                                        {{ $req->created_at->diffForHumans() }}
                                    </div>

                                </div>


                                {{-- Client --}}
                                <div class="text-gray-700 dark:text-gray-300 text-sm">
                                    {{ $req->client->first_name ?? 'Client' }}
                                </div>


                                {{-- Status --}}
                                <div>

                                    <span class="text-xs px-2 py-0.5">

                                        @if ($req->status === 'completed')
                                            <span class="text-green-600 font-medium">Completed</span>
                                        @elseif ($req->status === 'pending')
                                            <span class="text-yellow-600 font-medium">Pending</span>
                                        @elseif ($req->status === 'draft')
                                            <span class="text-gray-500 font-medium">Draft</span>
                                        @else
                                            <span class="text-blue-600 font-medium">
                                                {{ ucfirst($req->status) }}
                                            </span>
                                        @endif

                                    </span>

                                </div>


                                {{-- Due --}}
                                <div class="text-xs">

                                    @if ($req->expires_at)
                                        <span @class([
                                            'text-red-500 font-medium' => $req->expires_at->isPast(),
                                            'text-yellow-500 font-medium' =>
                                                $req->expires_at->diffInDays(now()) <= 2 && !$req->expires_at->isPast(),
                                            'text-gray-400' => $req->expires_at->diffInDays(now()) > 2,
                                        ])>

                                            {{ $req->expires_at->format('d M Y') }}

                                        </span>

                                        <div class="text-[11px] text-gray-400">
                                            {{ $req->expires_at->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif

                                </div>

                            </a>

                        @empty

                            <div class="px-4 py-4 text-sm text-gray-500">
                                No recent requests
                            </div>
                        @endforelse

                    </div>

                </div>

            </div>

        </div>
    </div>


    {{-- CHARTS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            new Chart(
                document.getElementById('uploadsChart'), {
                    type: 'line',
                    data: {
                        labels: @json($uploads->keys()),
                        datasets: [{
                            label: 'Uploads',
                            data: @json($uploads->values()),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                }
            );


            new Chart(
                document.getElementById('storageChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Used', 'Remaining'],
                        datasets: [{
                            data: [
                                {{ $storageUsed }},
                                {{ $storageRemaining }}
                            ],
                            backgroundColor: ['#6366f1', '#e5e7eb']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let value = context.raw;
                                        let mb = value / 1024 / 1024;
                                        return context.label + ': ' + mb.toFixed(2) + ' MB';
                                    }
                                }
                            }
                        }
                    }
                }
            );

        });
    </script>
@endsection
