@extends('layouts.app')

@section('content')
    <div class="w-full">

        <div class="max-w-7xl mx-auto space-y-6">

            {{-- ================= HEADER ================= --}}
            <div
                class="border border-neutral-200 dark:border-neutral-800 
                    bg-white dark:bg-neutral-900 p-5 md:p-6">

                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    {{-- LEFT --}}
                    <div class="flex items-center gap-4 min-w-0">

                        <div
                            class="w-14 h-14 rounded-full
                                bg-primary-600 text-white
                                flex items-center justify-center
                                text-lg font-semibold">
                            {{ strtoupper(substr($documentRequest->client->first_name, 0, 1) . substr($documentRequest->client->last_name, 0, 1)) }}
                        </div>

                        <div class="min-w-0">

                            <div class="flex items-center gap-3 flex-wrap">

                                <h1
                                    class="text-xl md:text-2xl font-semibold 
                                       text-neutral-900 dark:text-neutral-100 truncate">
                                    {{ $documentRequest->request_number }}
                                </h1>

                                @php
                                    $statusClasses = match ($documentRequest->status) {
                                        'completed'
                                            => 'text-emerald-600 border-emerald-600/30 bg-emerald-600/10 dark:text-emerald-400 dark:border-emerald-400/40 dark:bg-emerald-400/10',
                                        'sent'
                                            => 'text-primary-600 border-primary-600/30 bg-primary-600/10 dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10',
                                        'in_progress'
                                            => 'text-amber-600 border-amber-600/30 bg-amber-600/10 dark:text-amber-400 dark:border-amber-400/40 dark:bg-amber-400/10',
                                        'draft'
                                            => 'text-neutral-600 border-neutral-400/30 bg-neutral-400/10 dark:text-neutral-400 dark:border-neutral-600 dark:bg-neutral-700/30',
                                        default
                                            => 'text-neutral-600 border-neutral-400/30 bg-neutral-400/10 dark:text-neutral-400 dark:border-neutral-600 dark:bg-neutral-700/30',
                                    };
                                @endphp

                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-xs font-medium border rounded-2xl {{ $statusClasses }}">
                                    {{ ucfirst(str_replace('_', ' ', $documentRequest->status)) }}
                                </span>

                            </div>

                            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                                {{ $documentRequest->client->full_name }} •
                                Created {{ $documentRequest->created_at->diffForHumans() }}
                            </p>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                                {{-- DUE DATE --}}
                                @if ($documentRequest->expires_at)
                                    @if ($documentRequest->expires_at->isPast() && !$documentRequest->isCompleted())
                                        <div
                                            class="flex items-center gap-2 text-xs text-red-600 dark:text-red-400 font-semibold">
                                            <x-lucide-calendar class="w-3.5 h-3.5" />
                                            Due: {{ $documentRequest->expires_at->format('d M Y h:i A') }} (Overdue)
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 text-xs text-neutral-500 dark:text-neutral-400">
                                            <x-lucide-calendar class="w-3.5 h-3.5" />
                                            Due: {{ $documentRequest->expires_at->format('d M Y h:i A') }}
                                        </div>
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-row flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto">

                        {{-- BACK --}}
                        <a href="{{ route('document-requests.index') }}"
                            class="flex-1 sm:flex-none min-w-0 px-3 sm:px-4 h-10
                             inline-flex items-center justify-center gap-2
                             text-xs sm:text-sm font-medium border
                             text-neutral-600 border-neutral-300 bg-neutral-100
                             hover:bg-neutral-200
                             dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800
                             dark:hover:bg-neutral-700
                             transition cursor-pointer">
                            <x-lucide-arrow-left class="w-4 h-4 shrink-0" />
                            <span class="truncate">Back</span>
                        </a>

                        {{-- SEND / RESEND --}}
                        @if ($documentRequest->status === \App\Models\DocumentRequest::STATUS_DRAFT)
                            <form method="POST" action="{{ route('document-requests.send', $documentRequest) }}"
                                class="flex-1 sm:flex-none min-w-0">
                                @csrf
                                <button type="submit"
                                    class="w-full px-3 sm:px-4 h-10
                       inline-flex items-center justify-center gap-2
                       text-xs sm:text-sm font-medium border
                       text-primary-600 border-primary-600/30 bg-primary-600/10
                       hover:bg-primary-600 hover:text-white
                       dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                       dark:hover:bg-primary-500 dark:hover:text-white
                       transition cursor-pointer">
                                    <x-lucide-send class="w-4 h-4 shrink-0" />
                                    <span class="truncate">Send</span>
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('document-requests.resend', $documentRequest) }}"
                                class="flex-1 sm:flex-none min-w-0">
                                @csrf
                                <button type="submit"
                                    class="w-full px-3 sm:px-4 h-10
                       inline-flex items-center justify-center gap-2
                       text-xs sm:text-sm font-medium border
                       text-primary-600 border-primary-600/30 bg-primary-600/10
                       hover:bg-primary-600 hover:text-white
                       dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                       dark:hover:bg-primary-500 dark:hover:text-white
                       transition cursor-pointer">
                                    <x-lucide-refresh-cw class="w-4 h-4 shrink-0" />
                                    <span class="truncate">Resend</span>
                                </button>
                            </form>
                        @endif

                        {{-- COPY LINK --}}
                        <button id="copyLinkBtn" type="button"
                            onclick="generateLink('{{ route('document-requests.link', $documentRequest) }}')"
                            class="col-span-2 md:col-auto w-full md:w-auto
     px-4 h-10 inline-flex items-center justify-center gap-2
     text-sm font-medium border
     text-violet-600 border-violet-600/30 bg-violet-600/10
     hover:bg-violet-600 hover:text-white
     dark:text-violet-400 dark:border-violet-400/40 dark:bg-violet-400/10
     dark:hover:bg-violet-500 dark:hover:text-white
     transition cursor-pointer">

                            {{-- ICON --}}
                            <x-lucide-link id="linkIcon" class="w-4 h-4" />

                            {{-- LOADER --}}
                            <svg id="linkLoader" class="hidden animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4l3-3-3-3v4A10 10 0 002 12h2z">
                                </path>
                            </svg>

                            <span id="linkText">Copy Link</span>
                        </button>
                    </div>
                </div>
            </div>


            {{-- ================= DOCUMENTS ================= --}}
            <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 overflow-hidden">
                @php $request = $documentRequest; @endphp

                {{-- REQUEST HEADER --}}
                <div class="px-5 py-4 bg-neutral-50 dark:bg-neutral-800/40">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div>
                            <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                                Documemts
                            </div>

                            <div class="flex items-center gap-2 mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                <span>{{ $request->uploads->count() }} files</span>
                            </div>
                        </div>

                        @if ($request->uploads->isNotEmpty())
                            <a href="{{ route('requests.downloadAll', $request->id) }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium border
                    text-neutral-600 border-neutral-300 bg-neutral-100
                    hover:bg-neutral-200
                    dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800
                    dark:hover:bg-neutral-700
                    transition cursor-pointer">

                                <x-lucide-download class="w-4 h-4" />
                                Download All
                            </a>
                        @endif

                    </div>
                </div>

                {{-- FILES --}}
                @forelse($request->uploads->sortByDesc('created_at') as $doc)
                    <div class="px-5 py-4 hover:bg-neutral-100 dark:hover:bg-neutral-800/40 transition">

                        <div class="flex flex-col sm:flex-row gap-3">

                            <div
                                class="w-9 h-9 rounded-full
                            bg-neutral-200 dark:bg-neutral-700
                            flex items-center justify-center shrink-0">
                                <x-lucide-file class="w-4 h-4 text-neutral-600 dark:text-neutral-300" />
                            </div>

                            <div class="flex-1 min-w-0">

                                <div class="font-medium text-neutral-900 dark:text-neutral-100">
                                    {{ $doc->field_label }}
                                </div>

                                <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                    {{ $doc->original_name }}
                                </div>

                                <div class="text-xs text-neutral-400 mt-1">
                                    {{ $doc->file_size >= 1073741824
                                        ? number_format($doc->file_size / 1073741824, 2) . ' GB'
                                        : ($doc->file_size >= 1048576
                                            ? number_format($doc->file_size / 1048576, 2) . ' MB'
                                            : number_format($doc->file_size / 1024, 2) . ' KB') }}
                                    • {{ $doc->created_at->format('d M Y H:i') }}
                                </div>

                            </div>

                            <div class="flex items-start sm:items-center mt-3 sm:mt-0">
                                <a href="{{ route('requests.download', $doc) }}"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium border
        text-neutral-600 border-neutral-300 bg-neutral-100
        hover:bg-neutral-200
        dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800
        dark:hover:bg-neutral-700
        transition cursor-pointer">

                                    <x-lucide-download class="w-4 h-4" />
                                    Download
                                </a>
                            </div>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-10 text-center text-neutral-500 dark:text-neutral-400 italic">
                        No files uploaded.
                    </div>
                @endforelse

            </div>


            {{-- LINK MODAL --}}
            <div id="linkModal" class="fixed inset-0 hidden z-50">

                {{-- Overlay --}}
                <div class="absolute inset-0 bg-black/40" onclick="closeLinkModal()"></div>

                {{-- Modal Box --}}
                <div class="relative z-10 flex items-end sm:items-center justify-center min-h-screen p-4">
                    <div
                        class="w-full max-w-lg
                    border border-neutral-200 dark:border-neutral-800
                    bg-white dark:bg-neutral-900">

                        {{-- Header --}}
                        <div
                            class="px-5 py-4 border-b border-neutral-200 dark:border-neutral-800 flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                                Share Link
                            </h3>

                            <button onclick="closeLinkModal()"
                                class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 cursor-pointer">
                                <x-lucide-x class="w-5 h-5" />
                            </button>
                        </div>

                        {{-- Body --}}
                        <div class="p-5 space-y-4">

                            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                Copy and share this link with the client:
                            </p>

                            <div class="flex gap-2">
                                <input id="generatedLink" type="text" readonly
                                    class="flex-1 h-10 px-3 text-sm
                                  border border-neutral-300 dark:border-neutral-700
                                  bg-neutral-50 dark:bg-neutral-800
                                  text-neutral-900 dark:text-neutral-100">

                                <button onclick="copyGeneratedLink()"
                                    class="h-10 px-4 text-sm font-medium border
                                   text-primary-600 border-primary-600/30 bg-primary-600/10
                                   hover:bg-primary-600 hover:text-white
                                   dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                                   dark:hover:bg-primary-500 dark:hover:text-white
                                   transition cursor-pointer">
                                    Copy
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        function generateLink(url) {

            const btn = document.getElementById('copyLinkBtn');
            const loader = document.getElementById('linkLoader');
            const icon = document.getElementById('linkIcon');
            const text = document.getElementById('linkText');

            // SHOW LOADER
            loader.classList.remove('hidden');
            icon.classList.add('hidden');
            text.innerText = 'Generating...';
            btn.disabled = true;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {

                    // RESET BUTTON
                    loader.classList.add('hidden');
                    icon.classList.remove('hidden');
                    text.innerText = 'Copy Link';
                    btn.disabled = false;

                    if (!data.link) {
                        alert('Link could not be generated.');
                        return;
                    }

                    document.getElementById('generatedLink').value = data.link;

                    document.getElementById('linkModal').classList.remove('hidden');
                })
                .catch(error => {

                    loader.classList.add('hidden');
                    icon.classList.remove('hidden');
                    text.innerText = 'Copy Link';
                    btn.disabled = false;

                    console.error('Error:', error);
                    alert('Something went wrong.');
                });
        }

        function closeLinkModal() {
            document.getElementById('linkModal').classList.add('hidden');
        }

        function copyGeneratedLink() {
            const input = document.getElementById('generatedLink');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value);
        }
    </script>
@endsection
