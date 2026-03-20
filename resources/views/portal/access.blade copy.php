<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $companyName ?? 'Secure Upload Portal' }}</title>

    @vite(['resources/css/app.css'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-100 text-slate-800 antialiased">

    <div class="min-h-screen flex flex-col">

        <!-- ================= FIXED HEADER ================= -->
        <header class="fixed top-0 left-0 right-0 bg-white border-b border-slate-200 shadow-sm z-50">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

                <!-- Company Branding -->
                <div class="flex items-center gap-4">
                    @if ($brandingEnabled && $companyLogo)
                    <img src="{{ Storage::disk($documentRequest->disk ?? config('filesystems.default'))->url($companyLogo) }}"
                        class="h-10 w-auto object-contain">
                    @endif

                    <div>
                        <h1 class="text-lg font-semibold">
                            {{ $brandingEnabled ? $companyName : 'Secure Document Portal' }}
                        </h1>
                        <p class="text-xs text-slate-400">
                            Secure Client Upload Portal
                        </p>
                    </div>
                </div>

                <!-- Portal Auth Options -->
                <div class="flex items-center gap-4 text-sm">

                    @if ($isCorrectClient)
                    <span class="text-slate-600">
                        Logged in as {{ $loggedClient->first_name }}
                    </span>

                    <form method="POST" action="{{ route('portal.logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-600 hover:text-red-600 font-medium">
                            Logout
                        </button>
                    </form>
                    @else
                    @if ($documentRequest->client && $documentRequest->client->password)
                    <a href="{{ route('portal.login', $token) }}"
                        class="text-slate-600 hover:text-slate-900 font-medium">
                        Login
                    </a>
                    @elseif ($documentRequest->client)
                    <a href="{{ route('portal.activate', $token) }}"
                        class="bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        Set Password
                    </a>
                    @endif

                    @endif

                </div>

            </div>
        </header>

        <!-- Spacer for fixed header -->
        <div class="h-20"></div>

        <!-- ================= MAIN ================= -->
        <main class="flex-1 py-10 px-6">
            <div class="max-w-5xl mx-auto space-y-8">

                <!-- REQUEST SUMMARY -->
                <div class="bg-white rounded-2xl shadow border border-slate-200 p-8">

                    <div class="grid md:grid-cols-2 gap-6">

                        <!-- Left Side -->
                        <div>
                            <h2 class="text-xl font-semibold">
                                Document Request #{{ $documentRequest->request_number }}
                            </h2>

                            <p class="text-sm text-slate-500 mt-2">
                                Created on {{ $documentRequest->created_at->format('d M Y') }}
                            </p>

                            @if ($documentRequest->expires_at)
                            <p class="text-sm mt-2">
                                @if ($documentRequest->isExpired())
                                <span class="text-red-600 font-medium">
                                    Expired on {{ $documentRequest->expires_at->format('d M Y') }}
                                </span>
                                @else
                                <span class="text-amber-600 font-medium">
                                    Expires on {{ $documentRequest->expires_at->format('d M Y') }}
                                </span>
                                @endif
                            </p>
                            @endif

                            <p class="text-sm mt-2">
                                Status:
                                <span class="font-medium">
                                    {{ ucfirst($documentRequest->status ?? 'Pending') }}
                                </span>
                            </p>
                        </div>

                        <!-- Right Side (Request User & Company Info) -->
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-sm">

                            <h3 class="font-semibold mb-3">Requested By</h3>

                            <p class="font-medium">
                                {{ $documentRequest->owner->name ?? 'N/A' }}
                            </p>

                            <p class="text-slate-500">
                                {{ $documentRequest->owner->email ?? '' }}
                            </p>

                            <hr class="my-4">

                            <h3 class="font-semibold mb-2">Company Details</h3>

                            <p>
                                {{ $companyName ?? config('app.name') }}
                            </p>

                            @if (isset($companyEmail))
                            <p class="text-slate-500">{{ $companyEmail }}</p>
                            @endif

                        </div>

                    </div>

                    <!-- Progress -->
                    <div class="mt-8">
                        <div class="flex justify-between text-sm mb-2">
                            <span>{{ $uploadedCount }} of {{ $total }} completed</span>
                            <span>{{ $progress }}%</span>
                        </div>

                        <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 transition-all duration-500"
                                style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    <!-- Storage Info -->
                    <div class="mt-6 text-xs text-slate-500 flex justify-between">
                        <span>
                            Storage Remaining:
                            <strong>
                                {{ $storageRemainingMB !== null ? $storageRemainingMB . ' MB' : 'Unlimited' }}
                            </strong>
                        </span>

                        @if ($maxUploadMB)
                        <span>
                            Max File Size:
                            <strong>{{ $maxUploadMB }} MB</strong>
                        </span>
                        @endif
                    </div>

                </div>

                <!-- ================= FIELDS ================= -->
                @foreach ($documentRequest->fields ?? [] as $field)
                @php($uploaded = $documentRequest->uploads->firstWhere('field_label', $field['label']))

                <div class="bg-white rounded-2xl shadow border border-slate-200 p-6">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg">
                            {{ $field['label'] }}
                        </h3>

                        @if ($uploaded)
                        <span class="px-3 py-1 text-xs bg-emerald-100 text-emerald-700 rounded-full">
                            Uploaded
                        </span>
                        @endif
                    </div>

                    @if ($uploaded)
                    <div class="bg-slate-50 border border-slate-100 rounded-lg p-4 text-sm">
                        <p class="font-medium">{{ $uploaded->original_name }}</p>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ round($uploaded->file_size / 1024 / 1024, 2) }} MB
                            • {{ optional($uploaded->uploaded_at)->format('d M Y, h:i A') }}
                        </p>
                    </div>
                    @elseif(!$documentRequest->isExpired())
                    <!-- Upload Component (UNCHANGED LOGIC) -->
                    <div x-data="uploadComponent(
                                '{{ route('portal.upload', $token) }}',
                                '{{ $field['label'] }}',
                                {{ $maxUploadBytes ?? 0 }},
                                {{ $storageRemaining ?? 0 }}
                            )">

                        <input type="file" x-ref="fileInput" @change="selectFile"
                            class="mb-4 block w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">

                        <button @click="upload" :disabled="uploading || !file"
                            class="px-6 py-2 rounded-lg bg-slate-900 text-white hover:bg-blue-600 transition disabled:opacity-50">
                            <span x-show="!uploading">Upload Document</span>
                            <span x-show="uploading">Uploading...</span>
                        </button>

                        <div x-show="uploading" class="mt-4">
                            <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                                <div class="bg-blue-500 h-full" :style="'width:' + percent + '%'"></div>
                            </div>
                            <p class="text-xs mt-1" x-text="percent + '%'"></p>
                        </div>

                        <div x-show="message" class="mt-4 text-sm"
                            :class="success ? 'text-emerald-600' : 'text-red-600'" x-text="message"></div>

                    </div>
                    @endif

                </div>
                @endforeach

            </div>
        </main>

        <!-- ================= FOOTER ================= -->
        <footer class="py-6 text-center text-xs text-slate-400 border-t">
            Powered by {{ $brandingEnabled ? $companyName : config('app.name') }}
        </footer>

    </div>

    <!-- ================= UPLOAD SCRIPT (UNCHANGED) ================= -->
    <script>
        function uploadComponent(url, fieldLabel, maxBytes, remainingBytes) {
            return {
                file: null,
                uploading: false,
                percent: 0,
                message: '',
                success: false,

                selectFile(e) {
                    this.file = e.target.files[0] || null;
                    this.message = '';
                },

                upload() {
                    if (!this.file) {
                        this.message = 'Please select a file.';
                        this.success = false;
                        return;
                    }

                    if (maxBytes && this.file.size > maxBytes) {
                        this.message = 'File exceeds maximum size.';
                        this.success = false;
                        return;
                    }

                    if (remainingBytes && this.file.size > remainingBytes) {
                        this.message = 'Storage limit reached.';
                        this.success = false;
                        return;
                    }

                    this.uploading = true;
                    this.percent = 0;
                    this.message = '';

                    const formData = new FormData();
                    formData.append('file', this.file);
                    formData.append('field_label', fieldLabel);
                    formData.append('_token', '{{ csrf_token() }}');

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', url);

                    xhr.upload.onprogress = (e) => {
                        if (e.lengthComputable) {
                            this.percent = Math.round((e.loaded / e.total) * 100);
                        }
                    };

                    xhr.onload = () => {
                        this.uploading = false;
                        let response = {};
                        try {
                            response = JSON.parse(xhr.responseText);
                        } catch {}

                        if (xhr.status === 200 && response.success) {
                            this.message = response.message ?? 'Upload successful!';
                            this.success = true;
                            setTimeout(() => location.reload(), 800);
                        } else {
                            this.message = response.message ?? 'Upload failed.';
                            this.success = false;
                        }
                    };

                    xhr.onerror = () => {
                        this.uploading = false;
                        this.message = 'Network error.';
                        this.success = false;
                    };

                    xhr.send(formData);
                }
            }
        }
    </script>

</body>

</html>