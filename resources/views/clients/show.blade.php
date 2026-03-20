@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm p-4 md:p-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                {{-- AVATAR & INFO --}}
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center text-lg font-bold shrink-0">
                        {{ $client->initials }} </div>

                    <div class="min-w-0">
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100 truncate">
                            {{ $client->full_name }}
                        </h1>
                        <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                            {{ $client->company_name ?? 'No company' }}
                        </div>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="grid grid-cols-2 md:flex items-center gap-2">

                    <a href="{{ route('clients.index') }}"
                        class="px-3 h-10 md:h-9 inline-flex items-center justify-center gap-2
                bg-gray-100 hover:bg-gray-200
                dark:bg-gray-800 dark:hover:bg-gray-700
                text-gray-700 dark:text-gray-200
                text-sm font-medium transition">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Back
                    </a>

                    <a href="{{ route('clients.edit', $client) }}"
                        class="px-3 h-10 md:h-9 inline-flex items-center justify-center gap-2
                bg-emerald-50 hover:bg-emerald-100
                dark:bg-emerald-900/20 dark:hover:bg-emerald-900/40
                text-emerald-600 dark:text-emerald-400
                text-sm font-medium transition">
                        <x-lucide-square-pen class="w-4 h-4" />
                        Edit
                    </a>

                    {{-- NEW REQUEST --}}
                    <a href="{{ route('document-requests.create', ['client' => $client->uuid]) }}"
                        class="col-span-2 md:col-auto w-full md:w-auto h-10 md:h-9 px-4 inline-flex items-center justify-center gap-2
                bg-blue-600 hover:bg-blue-700
                dark:bg-blue-500 dark:hover:bg-blue-400
                text-white text-sm font-semibold transition">
                        <x-lucide-plus class="w-4 h-4" />
                        <span>New Request</span>
                    </a>
                </div>

            </div>

        </div>


        {{-- INFO CARDS --}}
        <div class="grid md:grid-cols-3 gap-4">

            @foreach ([
            'Email' => $client->email,
            'Phone' => $client->phone ?? '-',
            'Client Since' => $client->created_at->format('d M Y'),
        ] as $label => $value)
                <div
                    class="bg-white dark:bg-gray-900
                border border-gray-200 dark:border-gray-800
                shadow-sm p-4">

                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $label }}
                    </div>

                    <div class="font-semibold text-gray-800 dark:text-gray-100">
                        {{ $value }}
                    </div>

                </div>
            @endforeach

        </div>


        {{-- MAIN GRID --}}
        <div class="grid lg:grid-cols-3 gap-6">

            {{-- LEFT --}}
            <div class="lg:col-span-2">

                <div x-data="{ tab: 'requests' }">

                    {{-- TABS --}}
                    <div class="flex gap-6 border-b border-gray-100 dark:border-gray-800 mb-4">

                        @foreach (['requests' => 'Requests', 'documents' => 'Documents', 'overview' => 'Overview'] as $key => $label)
                            <button @click="tab='{{ $key }}'"
                                :class="tab === '{{ $key }}'
                                    ?
                                    'border-b-2 border-blue-600 text-blue-600 dark:text-blue-400' :
                                    'text-gray-500 dark:text-gray-400'"
                                class="pb-2 text-sm font-semibold cursor-pointer">

                                {{ $label }}

                            </button>
                        @endforeach

                    </div>

                    {{-- REQUESTS --}}
                    <div x-data="{ deleteModal: false, deleteUrl: '' }" x-show="tab==='requests'" x-cloak>

                        {{-- DESKTOP VIEW (Table) --}}
                        <div
                            class="hidden md:block bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm overflow-x-auto">
                            <table class="min-w-full text-sm">

                                <thead class="border-b border-gray-200 dark:border-gray-800">
                                    <tr class="text-gray-600 dark:text-gray-400">
                                        <th class="px-4 py-3 text-left font-semibold">#</th>
                                        <th class="px-4 py-3 text-left font-semibold">Request</th>
                                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                                        <th class="px-4 py-3 text-left font-semibold">Due</th>
                                        <th class="px-4 py-3 text-left font-semibold">Created</th>
                                        <th class="px-4 py-3 text-right font-semibold">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($client->documentRequests as $request)
                                        <tr
                                            class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 font-semibold">
                                                {{ $client->documentRequests->count() - $loop->index }}
                                            </td>

                                            <td class="px-4 py-3 font-semibold">
                                                {{ $request->request_number }}
                                            </td>

                                            <td class="px-4 py-3">
                                                @php
                                                    $statusClasses = match ($request->status) {
                                                        'completed'
                                                            => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                                        'sent'
                                                            => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                                        'in_progress'
                                                            => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                        'draft'
                                                            => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                                                        default
                                                            => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                                                    };
                                                @endphp

                                                <span class="px-2 py-1 text-xs font-semibold {{ $statusClasses }}">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                            </td>

                                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                                {{ optional($request->expires_at)->format('d M Y') ?? '-' }}
                                            </td>

                                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                                {{ $request->created_at->format('d M Y') }}
                                            </td>

                                            <td class="px-4 py-3 text-right space-x-2">

                                                {{-- SEND / RESEND --}}
                                                @if ($request->status === \App\Models\DocumentRequest::STATUS_DRAFT)
                                                    <form method="POST"
                                                        action="{{ route('document-requests.send', $request) }}"
                                                        class="inline" onclick="event.stopPropagation();">
                                                        @csrf
                                                        <button type="submit"
                                                            class="h-8 px-3 inline-flex items-center gap-2 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 text-blue-600 dark:text-blue-400 text-xs font-semibold transition cursor-pointer">
                                                            Send
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST"
                                                        action="{{ route('document-requests.resend', $request) }}"
                                                        class="inline" onclick="event.stopPropagation();">
                                                        @csrf
                                                        <button type="submit"
                                                            class="h-8 px-3 inline-flex items-center gap-2 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-semibold transition cursor-pointer">
                                                            Resend
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- LINK --}}
                                                <button type="button"
                                                    onclick="event.stopPropagation(); generateLink('{{ route('document-requests.link', $request) }}')"
                                                    class="h-8 px-3 inline-flex items-center gap-2 bg-violet-50 hover:bg-violet-100 dark:bg-violet-900/20 dark:hover:bg-violet-900/40 text-violet-600 dark:text-violet-400 text-xs font-semibold transition cursor-pointer">
                                                    Link
                                                </button>

                                                <button
                                                    onclick="event.stopPropagation(); window.dispatchEvent(
        new CustomEvent('open-delete-request', {
            detail: {
                url: '{{ route('document-requests.destroy', $request) }}',
                name: 'Request #{{ $request->request_number }}'
            }
        })
    )"
                                                    class="h-8 px-3 inline-flex items-center gap-2
           bg-red-50 hover:bg-red-100
           dark:bg-red-900/20 dark:hover:bg-red-900/40
           text-red-600 dark:text-red-400
           text-xs font-semibold transition cursor-pointer">

                                                    Delete
                                                </button>

                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-14 text-center">
                                                <div class="text-gray-400 mb-4">No requests yet.</div>

                                                <a href="{{ route('document-requests.create', ['client_id' => $client->id]) }}"
                                                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400 text-white text-sm font-semibold px-4 py-2 transition">
                                                    <x-lucide-plus class="w-4 h-4" /> Create First Request
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                        {{-- MOBILE VIEW --}}
                        <div class="block md:hidden space-y-3">

                            @forelse($client->documentRequests as $request)
                                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-4 shadow-sm"
                                    onclick="window.location='{{ route('document-requests.show', $request) }}'">

                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">
                                                #{{ $client->documentRequests->count() - $loop->index }}
                                            </div>

                                            <div class="font-bold text-gray-900 dark:text-white">
                                                {{ $request->request_number }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4 text-xs">
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Created</p>
                                            <p class="font-medium dark:text-gray-200">
                                                {{ $request->created_at->format('d M Y') }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Due Date</p>
                                            <p class="font-medium dark:text-gray-200">
                                                {{ optional($request->expires_at)->format('d M Y') ?? '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">

                                        {{-- SEND / RESEND --}}
                                        @if ($request->status === \App\Models\DocumentRequest::STATUS_DRAFT)
                                            <form method="POST" action="{{ route('document-requests.send', $request) }}"
                                                class="flex-1" onclick="event.stopPropagation();">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full h-9 flex items-center justify-center
                            bg-blue-50 text-blue-600
                            dark:bg-blue-900/20 dark:text-blue-400
                            text-xs font-semibold cursor-pointer">
                                                    Send
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('document-requests.resend', $request) }}"
                                                class="flex-1" onclick="event.stopPropagation();">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full h-9 flex items-center justify-center
                            bg-indigo-50 text-indigo-600
                            dark:bg-indigo-900/20 dark:text-indigo-400
                            text-xs font-semibold cursor-pointer">
                                                    Resend
                                                </button>
                                            </form>
                                        @endif

                                        {{-- LINK --}}
                                        <button type="button"
                                            onclick="event.stopPropagation(); generateLink('{{ route('document-requests.link', $request) }}')"
                                            class="flex-1 h-9 flex items-center justify-center bg-violet-50 text-violet-600 dark:bg-violet-900/20 dark:text-violet-400 text-xs font-semibold cursor-pointer">
                                            Link
                                        </button>

                                        <button
                                            onclick="event.stopPropagation(); window.dispatchEvent(
        new CustomEvent('open-delete-request', {
            detail: {
                url: '{{ route('document-requests.destroy', $request) }}',
                name: 'Request #{{ $request->request_number }}'
            }
        })
    )"
                                            class="h-8 px-3 inline-flex items-center gap-2
           bg-red-50 hover:bg-red-100
           dark:bg-red-900/20 dark:hover:bg-red-900/40
           text-red-600 dark:text-red-400
           text-xs font-semibold transition cursor-pointer">

                                            Delete
                                        </button>

                                    </div>
                                </div>

                            @empty
                                <div
                                    class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-8 text-center">
                                    <p class="text-gray-400 mb-4">No requests yet.</p>

                                    <a href="{{ route('document-requests.create', ['client_id' => $client->id]) }}"
                                        class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold px-4 py-2">
                                        <x-lucide-plus class="w-4 h-4" /> Create Request
                                    </a>
                                </div>
                            @endforelse

                        </div>


                        {{-- DELETE REQUEST MODAL --}}
                        <div x-data="{
                            show: false,
                            url: '',
                            name: ''
                        }"
                            x-on:open-delete-request.window="
        show = true;
        url = $event.detail.url;
        name = $event.detail.name;
    "
                            x-show="show" x-cloak
                            class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 p-2 sm:p-4">

                            <div @click.away="show=false" x-transition
                                class="w-full sm:max-w-md
               bg-white dark:bg-gray-900
               border border-gray-200 dark:border-gray-800
               p-5 sm:p-6">

                                {{-- Title --}}
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                    Delete Request
                                </h2>

                                {{-- Message --}}
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                                    Are you sure you want to delete
                                    <span class="font-semibold" x-text="name"></span>?
                                </p>

                                <p class="text-xs text-red-500 mt-2">
                                    This action cannot be undone.
                                </p>

                                {{-- Actions --}}
                                <div class="flex flex-col sm:flex-row gap-2 mt-6">

                                    {{-- Cancel --}}
                                    <button @click="show=false"
                                        class="w-full h-10
                       border border-gray-300 dark:border-gray-700
                       text-sm font-medium
                       cursor-pointer
                       hover:bg-gray-100 dark:hover:bg-gray-800">
                                        Cancel
                                    </button>

                                    {{-- Confirm --}}
                                    <form :action="url" method="POST" class="w-full">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="w-full h-10
                           bg-red-600 hover:bg-red-700
                           text-white text-sm font-medium
                           cursor-pointer">
                                            Delete
                                        </button>
                                    </form>

                                </div>

                            </div>
                        </div>


                    </div>


                    {{-- DOCUMENTS --}}
                    <div x-show="tab==='documents'" x-cloak>

                        @forelse($client->documentRequests->sortByDesc('created_at') as $request)
                            <div style="margin-bottom:20px;">

                                {{-- REQUEST HEADER --}}
                                <div
                                    style="
            padding:10px;
            background:#f3f4f6;
            border:1px solid #e5e7eb;
            display:flex;
            justify-content:space-between;
            align-items:center;
        ">

                                    <div>
                                        <strong>
                                            Request #{{ $request->request_number }}
                                        </strong>

                                        <span style="margin-left:10px; color:gray;">
                                            {{ $request->uploads->count() }} files
                                        </span>
                                    </div>


                                    {{-- DOWNLOAD ALL --}}
                                    @if ($request->uploads->isNotEmpty())
                                        <a href="{{ route('requests.downloadAll', $request->uuid) }}">
                                            Download All
                                        </a>
                                    @endif

                                </div>



                                {{-- TABLE --}}
                                <table width="100%" border="1" cellspacing="0" cellpadding="8">

                                    <thead style="background:#fafafa;">
                                        <tr>
                                            <th align="left">Document</th>
                                            <th align="left">File Name</th>
                                            <th>Size</th>
                                            <th>Uploaded</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @forelse($request->uploads->sortByDesc('created_at') as $doc)
                                            <tr>

                                                <td>{{ $doc->field_label }}</td>

                                                <td>{{ $doc->original_name }}</td>

                                                <td align="center">
                                                    {{ number_format($doc->file_size / 1024, 1) }} KB
                                                </td>

                                                <td align="center">
                                                    {{ $doc->created_at->format('d M Y') }}
                                                </td>

                                                <td align="center">

                                                    {{-- <a href="{{ route('requests.view', $doc->id) }}">
                                                        View
                                                    </a> --}}

                                                    |

                                                    <a href="{{ route('requests.download', $doc->id) }}">
                                                        Download
                                                    </a>

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>
                                                <td colspan="5" align="center">
                                                    No files uploaded.
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        @empty

                            <div style="padding:20px; text-align:center; color:gray;">
                                No document requests found for this client.
                            </div>
                        @endforelse

                    </div>


                    {{-- OVERVIEW --}}
                    <div x-show="tab==='overview'" x-cloak>

                        <div
                            class="bg-white dark:bg-gray-900
                        border border-gray-100 dark:border-gray-800
                        shadow-sm p-6 space-y-3">

                            <div><strong>Address:</strong> {{ $client->address_line_1 ?? '-' }}</div>
                            <div><strong>City:</strong> {{ $client->city ?? '-' }}</div>
                            <div><strong>Notes:</strong> {{ $client->notes ?? '-' }}</div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- ACTIVITY --}}
            <div>

                @php
                    $activities = $client->documentRequests
                        ->flatMap(function ($request) {
                            $events = collect([
                                (object) [
                                    'text' => "Request created — {$request->request_number}",
                                    'created_at' => $request->created_at,
                                ],
                            ]);

                            $uploads = $request->uploads->map(function ($upload) {
                                return (object) [
                                    'text' => "Document uploaded — {$upload->field_label}",
                                    'created_at' => $upload->created_at,
                                ];
                            });

                            return $events->concat($uploads);
                        })
                        ->sortByDesc('created_at')
                        ->take(7);
                @endphp


                <div
                    class="bg-white dark:bg-gray-900
                border border-gray-100 dark:border-gray-800
                shadow-sm p-5">

                    <h3 class="text-sm font-semibold mb-4">
                        Activity
                    </h3>

                    <div class="space-y-5">

                        @forelse ($activities as $activity)
                            <div class="flex items-start gap-3">

                                <div class="mt-2 w-2 h-2 rounded-full bg-blue-600"></div>

                                <div class="text-sm">

                                    <div class="text-gray-700 dark:text-gray-300">
                                        {{ $activity->text }}
                                    </div>

                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="text-sm text-gray-400 text-center py-6">
                                No recent activity.
                            </div>
                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div id="linkPopup"
        class="fixed bottom-6 right-6
     bg-gray-900 text-white text-sm
     px-4 py-2
     opacity-0 pointer-events-none
     transition">

        Link copied!
    </div>


    <script>
        function generateLink(url) {

            fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {

                    if (!data.link) return;

                    navigator.clipboard.writeText(data.link);

                    const popup = document.getElementById('linkPopup');

                    popup.classList.remove('opacity-0');
                    popup.classList.add('opacity-100');

                    setTimeout(() => {
                        popup.classList.add('opacity-0');
                    }, 1800);
                });
        }
    </script>
@endsection
