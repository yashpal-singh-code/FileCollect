@extends('layouts.portal')

@section('title', 'Activate Client Portal')

@section('portal')

    <div class="grid lg:grid-cols-3 gap-8">

        <!-- ================= LEFT SIDE (UPLOADS) ================= -->
        <div class="lg:col-span-2 space-y-4">

            @foreach ($documentRequest->fields ?? [] as $field)
                @php($uploaded = $documentRequest->uploads->firstWhere('field_label', $field['label']))

                <div class="bg-white shadow-sm border border-slate-200 p-4">

                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">

                        <h3 class="font-medium text-sm">
                            {{ $field['label'] }}
                        </h3>

                        @if ($uploaded)
                            <span class="px-2 py-0.5 text-[10px] bg-emerald-100 text-emerald-700">
                                Uploaded
                            </span>
                        @endif

                    </div>


                    {{-- ================= Uploaded File ================= --}}
                    @if ($uploaded)
                        <div class="bg-slate-50 border border-slate-200 p-3 text-xs flex justify-between items-center">
                            <div>
                                <p class="font-medium truncate max-w-xs">
                                    {{ $uploaded->original_name }}
                                </p>

                                <p class="text-slate-400">
                                    {{ round($uploaded->file_size / 1024 / 1024, 2) }} MB
                                    • {{ optional($uploaded->uploaded_at)->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        {{-- ================= NOT UPLOADED ================= --}}
                    @else
                        {{-- 🔴 EXPIRED --}}
                        @if ($documentRequest->isExpired())
                            <div class="border border-red-200 bg-red-50 p-4 text-center">

                                <p class="text-sm text-red-600 font-medium">
                                    Upload disabled — request expired
                                </p>

                                <p class="text-xs text-red-500 mt-1">
                                    Expired on {{ $documentRequest->expires_at->format('d M Y') }}
                                </p>

                            </div>

                            {{-- 🟢 ACTIVE --}}
                        @else
                            <div x-data="uploadComponent(
                                '{{ route('portal.upload', $token) }}',
                                '{{ $field['label'] }}',
                                {{ $maxUploadBytes ?? 0 }},
                                {{ $storageRemaining ?? 0 }}
                            )" class="space-y-2">

                                <!-- File Input -->
                                <input type="file" x-ref="fileInput" @change="selectFile"
                                    :disabled="$store.uploadManager.active"
                                    class="block w-full border border-slate-300 px-3 py-1.5 text-xs disabled:opacity-50 cursor-pointer">

                                <!-- Buttons -->
                                <div class="flex gap-2">

                                    <button @click="upload" :disabled="uploading || !file || $store.uploadManager.active"
                                        class="px-4 py-1.5 text-xs bg-slate-900 text-white hover:bg-blue-600 transition disabled:opacity-50">

                                        <span x-show="!uploading && !$store.uploadManager.active">Upload</span>
                                        <span x-show="uploading">Uploading...</span>
                                        <span x-show="!uploading && $store.uploadManager.active">Wait...</span>

                                    </button>

                                    <button x-show="file" @click="handleCancel" type="button"
                                        :disabled="$store.uploadManager.active && !uploading"
                                        class="px-4 py-1.5 text-xs border border-slate-300 hover:bg-slate-100 transition">

                                        <span x-text="uploading ? 'Cancel Upload' : 'Cancel'"></span>

                                    </button>

                                </div>

                                <!-- Progress -->
                                <div x-show="uploading" class="mt-2">
                                    <div class="w-full bg-slate-200 h-1.5 overflow-hidden">
                                        <div class="bg-blue-500 h-full transition-all" :style="'width:' + percent + '%'">
                                        </div>
                                    </div>
                                    <p class="text-[10px] mt-1 text-slate-500" x-text="percent + '%'"></p>
                                </div>

                                <!-- Message -->
                                <div x-show="message" class="text-xs" :class="success ? 'text-emerald-600' : 'text-red-600'"
                                    x-text="message"></div>

                            </div>
                        @endif
                    @endif

                </div>
            @endforeach

        </div>


        <!-- ================= RIGHT SIDE (DETAILS PANEL) ================= -->
        <div class="lg:col-span-1">

            <div class="bg-white shadow border border-slate-200 p-8 sticky top-20">

                <!-- Requested By -->
                <div class="text-sm">

                    <div class="flex items-center justify-between">

                        <h2 class="text-lg font-semibold text-slate-800">
                            Requested By
                        </h2>

                        @if (optional($documentRequest->owner->companySetting)->company_logo)
                            <img src="{{ Storage::disk('s3')->url($documentRequest->owner->companySetting->company_logo) }}"
                                class="h-16 object-contain">
                        @else
                            <span class="text-slate-400 text-xs font-semibold">
                                LOGO
                            </span>
                        @endif

                    </div>

                    <hr class="my-2">


                    <div class="space-y-1.5">

                        <div class="flex">
                            <span class="font-semibold text-slate-700 w-32">Company:</span>
                            <span class="text-slate-600">
                                {{ $documentRequest->owner->companySetting->company_name ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="flex">
                            <span class="font-semibold text-slate-700 w-32">Requested By:</span>
                            <span class="text-slate-600">
                                {{ $documentRequest->owner->name ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="flex">
                            <span class="font-semibold text-slate-700 w-32">Email:</span>
                            <span class="text-slate-600">
                                {{ $documentRequest->owner->email ?? 'N/A' }}
                            </span>
                        </div>

                        @if (optional($documentRequest->owner->companySetting)->phone)
                            <div class="flex">
                                <span class="font-semibold text-slate-700 w-32">Phone:</span>
                                <span class="text-slate-600">
                                    {{ $documentRequest->owner->companySetting->phone }}
                                </span>
                            </div>
                        @endif

                    </div>

                </div>


                <hr class="my-6">


                <!-- Request Info -->
                <div class="mb-2">

                    <h2 class="text-lg font-semibold text-slate-800">
                        Document Request Number
                    </h2>

                    <p class="text-sm text-slate-600">
                        {{ $documentRequest->request_number }}
                    </p>

                </div>


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

                    <span class="font-semibold">Status:</span>

                    <span
                        class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium whitespace-nowrap rounded-none
    {{ match ($documentRequest->status) {
        'completed' => 'bg-emerald-100 text-emerald-700',
        'in_progress' => 'bg-blue-100 text-blue-700',
        'cancelled' => 'bg-red-100 text-red-700',
        'expired' => 'bg-gray-200 text-gray-700',
        default => 'bg-amber-100 text-amber-700',
    } }}">
                        {{ ucfirst(str_replace('_', ' ', $documentRequest->status ?? 'pending')) }}
                    </span>

                </p>


                <hr class="my-6">


                <!-- Progress -->
                <div class="mt-8">

                    <div class="flex justify-between text-sm mb-2">

                        <span>{{ $uploadedCount }} of {{ $total }} completed</span>
                        <span>{{ $progress }}%</span>

                    </div>

                    <div class="w-full h-2 bg-slate-200 overflow-hidden">

                        <div class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ $progress }}%">
                        </div>

                    </div>

                </div>


                <!-- Storage Info -->
                <div class="mt-6 text-xs text-slate-500 space-y-1">

                    <div>
                        Storage Remaining:
                        <strong>
                            {{ $storageRemainingMB !== null ? $storageRemainingMB . ' MB' : 'Unlimited' }}
                        </strong>
                    </div>

                    @if ($maxUploadMB)
                        <div>
                            Max File Size:
                            <strong>{{ $maxUploadMB }} MB</strong>
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>


    <!-- ================= UPLOAD SCRIPT ================= -->
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.store('uploadManager', {
                active: false
            });

        });


        function uploadComponent(url, fieldLabel, maxBytes, remainingBytes) {

            return {

                file: null,
                uploading: false,
                percent: 0,
                message: '',
                success: false,
                xhr: null,


                selectFile(e) {

                    const files = e.target.files;

                    if (!files || files.length === 0) {
                        this.file = null;
                        return;
                    }

                    if (files.length > 1) {

                        this.message = 'Only one file can be uploaded.';
                        this.success = false;

                        this.file = null;

                        if (this.$refs.fileInput) {
                            this.$refs.fileInput.value = '';
                        }

                        return;
                    }

                    this.file = files[0];
                    this.message = '';
                    this.success = false;

                },


                cancelUpload() {

                    if (this.xhr) {
                        this.xhr.abort();
                        this.xhr = null;
                    }

                    Alpine.store('uploadManager').active = false;

                    this.resetComponent('Upload cancelled.');

                },


                handleCancel() {

                    if (this.uploading) {
                        this.cancelUpload();
                    } else {

                        this.file = null;
                        this.message = '';

                        if (this.$refs.fileInput) {
                            this.$refs.fileInput.value = '';
                        }

                    }

                },


                resetComponent(msg = '') {

                    this.uploading = false;
                    this.percent = 0;
                    this.file = null;
                    this.message = msg;
                    this.success = false;

                    if (this.$refs.fileInput) {
                        this.$refs.fileInput.value = '';
                    }

                },


                upload() {

                    if (!this.file) {
                        this.message = 'Please select a file.';
                        return;
                    }

                    if (Alpine.store('uploadManager').active) {
                        this.message = 'Another upload is already running.';
                        return;
                    }

                    if (maxBytes && this.file.size > maxBytes) {
                        this.message = 'File exceeds maximum size.';
                        return;
                    }

                    if (remainingBytes && this.file.size > remainingBytes) {
                        this.message = 'Storage limit reached.';
                        return;
                    }

                    this.uploading = true;
                    Alpine.store('uploadManager').active = true;

                    this.percent = 0;
                    this.message = '';

                    const formData = new FormData();

                    formData.append('file', this.file);
                    formData.append('field_label', fieldLabel);
                    formData.append('_token', '{{ csrf_token() }}');

                    this.xhr = new XMLHttpRequest();
                    this.xhr.open('POST', url);


                    this.xhr.upload.onprogress = (e) => {

                        if (e.lengthComputable) {
                            this.percent = Math.round((e.loaded / e.total) * 100);
                        }

                    };


                    this.xhr.onload = () => {

                        this.uploading = false;
                        Alpine.store('uploadManager').active = false;

                        let response = {};

                        try {
                            response = JSON.parse(this.xhr.responseText);
                        } catch {
                            response = {
                                success: false,
                                message: 'Invalid server response.'
                            };
                        }

                        if (this.xhr.status === 200 && response.success) {

                            this.message = response.message ?? 'Upload successful!';
                            this.success = true;

                            setTimeout(() => location.reload(), 800);

                        } else {

                            this.message = response.message ?? 'Upload failed.';
                            this.success = false;

                        }

                        this.xhr = null;

                    };


                    this.xhr.onerror = () => {

                        this.uploading = false;
                        Alpine.store('uploadManager').active = false;

                        this.message = 'Network error occurred.';
                        this.success = false;

                        this.xhr = null;

                    };


                    this.xhr.send(formData);

                }

            }

        }
    </script>

@endsection
