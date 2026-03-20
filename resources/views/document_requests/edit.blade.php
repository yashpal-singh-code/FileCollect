@extends('layouts.app')

@section('title', 'Edit Document Request')

@section('content')

    <div class="w-full">

        {{-- HEADER --}}
        <div class="max-w-4xl mx-auto mb-6">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                {{-- LEFT --}}
                <div class="space-y-2">

                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                            Edit Document Request
                        </h1>

                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            Update the document request settings for your client.
                        </p>
                    </div>

                    <div class="text-xs text-neutral-500 dark:text-neutral-400">
                        Client:
                        <span class="font-medium text-neutral-800 dark:text-neutral-200">
                            {{ $documentRequest->client->full_name }}
                        </span>
                        ({{ $documentRequest->client->email }})
                    </div>

                </div>

                {{-- BACK --}}
                <div class="w-full lg:w-auto">
                    <a href="{{ route('document-requests.index') }}"
                        class="w-full lg:w-auto
                          inline-flex items-center justify-center gap-2
                          h-9 px-4 text-sm font-medium border
                          border-neutral-300 dark:border-neutral-700
                          bg-neutral-100 dark:bg-neutral-800
                          text-neutral-700 dark:text-neutral-300
                          hover:bg-neutral-200 dark:hover:bg-neutral-700 transition">

                        <x-lucide-arrow-left class="w-4 h-4" />
                        Back
                    </a>
                </div>

            </div>

        </div>


        {{-- FORM CARD --}}
        <div class="max-w-4xl mx-auto">

            <div
                class="bg-white dark:bg-neutral-900
                    border border-neutral-200 dark:border-neutral-800
                    p-6 sm:p-8">

                <form method="POST" action="{{ route('document-requests.update', $documentRequest) }}" class="space-y-6">

                    @csrf
                    @method('PUT')


                    {{-- CLIENT --}}
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Client
                        </label>

                        <div
                            class="w-full h-11 px-4 flex items-center text-sm
                                bg-neutral-100 dark:bg-neutral-800
                                border border-neutral-300 dark:border-neutral-700
                                text-neutral-700 dark:text-neutral-300">

                            {{ $documentRequest->client->full_name }}

                            <span class="ml-2 text-neutral-500 text-xs">
                                ({{ $documentRequest->client->email }})
                            </span>

                        </div>
                    </div>



                    {{-- TEMPLATE --}}
                    @if ($templates->count())

                        <div x-data="templatePreview('{{ old('template_uuid', $documentRequest->template_uuid) }}')">

                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Use Template
                            </label>

                            <select name="template_uuid" x-model="selected" @change="loadTemplate"
                                class="w-full h-11 px-4 text-sm
                               bg-white dark:bg-neutral-800
                               border border-neutral-300 dark:border-neutral-700
                               text-neutral-900 dark:text-neutral-100
                               focus:outline-none focus:border-primary-500 transition cursor-pointer">

                                <option value="">Select template</option>

                                @foreach ($templates as $template)
                                    <option value="{{ $template->uuid }}"
                                        {{ old('template_uuid', $documentRequest->template_uuid) == $template->uuid ? 'selected' : '' }}>
                                        {{ $template->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('template_uuid')
                                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                            @enderror



                            {{-- TEMPLATE PREVIEW --}}
                            <div x-show="documents.length" x-cloak
                                class="mt-4 border border-neutral-200 dark:border-neutral-700 p-4 space-y-2">

                                <template x-for="doc in documents" :key="doc.label">

                                    <div class="flex items-center justify-between text-sm">

                                        <span x-text="doc.label"></span>

                                        <span x-show="doc.required" class="text-xs text-red-500">
                                            Required
                                        </span>

                                    </div>

                                </template>

                            </div>

                        </div>

                    @endif



                    {{-- MESSAGE --}}
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Message (optional)
                        </label>

                        <textarea name="message" rows="4"
                            class="w-full px-4 py-3 text-sm
                               bg-white dark:bg-neutral-800
                               border border-neutral-300 dark:border-neutral-700
                               text-neutral-900 dark:text-neutral-100
                               focus:outline-none focus:border-primary-500 transition">{{ old('message', $documentRequest->message) }}</textarea>
                    </div>



                    {{-- EXPIRY DATE --}}
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Due Date (optional)

                            <p class="text-xs text-neutral-500 mt-1">
                                If date not selected, the request will expire in 7 days.
                            </p>

                        </label>

                        <input type="date" name="expires_at"
                            value="{{ old('expires_at', optional($documentRequest->expires_at)->toDateString()) }}"
                            min="{{ now()->toDateString() }}"
                            class="w-full h-11 px-4 text-sm
                               bg-white dark:bg-neutral-800
                               border border-neutral-300 dark:border-neutral-700
                               text-neutral-900 dark:text-neutral-100
                               focus:outline-none focus:border-primary-500 transition cursor-pointer">
                    </div>



                    {{-- ACTION BUTTONS --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">

                        <button type="submit" x-data="{ loading: false }" @click="loading=true" :disabled="loading"
                            class="w-full sm:w-auto
                               inline-flex items-center justify-center gap-2
                               h-10 px-5 text-sm font-medium
                               border
                               text-primary-600 border-primary-600/30 bg-primary-600/10
                               hover:bg-primary-600 hover:text-white
                               dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                               dark:hover:bg-primary-500 dark:hover:text-white
                               disabled:opacity-60 disabled:cursor-not-allowed
                               transition cursor-pointer">

                            <x-lucide-save x-show="!loading" class="w-4 h-4" />
                            <span x-text="loading ? 'Updating...' : 'Update Request'"></span>

                        </button>


                        <a href="{{ route('document-requests.index') }}"
                            class="inline-flex items-center justify-center gap-2
    h-10 px-5 text-sm font-medium
    border
    text-neutral-600 border-neutral-300 bg-neutral-100
    hover:bg-neutral-200
    dark:text-neutral-300 dark:border-neutral-700 dark:bg-neutral-800
    dark:hover:bg-neutral-700
    transition cursor-pointer">

                            <x-lucide-circle-x class="w-4 h-4" />
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- TEMPLATE SCRIPT --}}
    <script>
        function templatePreview(oldTemplate = '') {
            return {

                selected: oldTemplate,
                templates: @js($templateJson),
                documents: [],

                init() {
                    if (this.selected) {
                        this.loadTemplate()
                    }
                },

                loadTemplate() {

                    const template = this.templates.find(
                        t => t.uuid === this.selected
                    )

                    this.documents = Array.isArray(template?.fields) ?
                        template.fields : []

                }
            }
        }
    </script>

@endsection
