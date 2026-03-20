@extends('layouts.app')

@section('title', 'Edit Template')
@section('description', 'Update your document request template.')

@section('content')


    <div class="max-w-4xl mx-auto">

        <div class="bg-white dark:bg-neutral-900
                    border border-neutral-200 dark:border-neutral-800">

            {{-- HEADER --}}
            <div class="px-6 py-5 border-b border-neutral-200 dark:border-neutral-800">

                <div class="flex items-center gap-3">
                    <x-lucide-file-pen class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    <div>
                        <h1 class="text-xl font-semibold text-neutral-900 dark:text-neutral-100">
                            Edit Template
                        </h1>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                            Update your document request workflow
                        </p>
                    </div>
                </div>

            </div>

            <div class="p-6">

                @if ($inUse)
                    <div class="bg-amber-50 border border-amber-200 text-amber-700 text-sm p-3 rounded-none mb-4">
                        This template is already used in document requests. Changes will only apply to future requests.
                    </div>
                @endif

                <form method="POST" action="{{ route('templates.update', $template) }}" class="space-y-6">

                    @csrf
                    @method('PUT')


                    {{-- TEMPLATE NAME --}}
                    <div>
                        <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                            Template Name *
                        </label>

                        <div class="relative">
                            <x-lucide-file-text class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400" />

                            <input type="text" name="name" value="{{ old('name', $template->name) }}" required
                                class="w-full h-9 pl-10 pr-3 text-sm
                                          bg-white dark:bg-neutral-900
                                          text-neutral-900 dark:text-neutral-100
                                          placeholder-neutral-400 dark:placeholder-neutral-500
                                          border border-neutral-300 dark:border-neutral-700
                                          focus:outline-none focus:border-primary-500
                                          transition">
                        </div>
                    </div>


                    {{-- DESCRIPTION --}}
                    <div>
                        <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                            Description
                        </label>

                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 text-sm
                                         bg-white dark:bg-neutral-900
                                         text-neutral-900 dark:text-neutral-100
                                         placeholder-neutral-400 dark:placeholder-neutral-500
                                         border border-neutral-300 dark:border-neutral-700
                                         focus:outline-none focus:border-primary-500
                                         transition">{{ old('description', $template->description) }}</textarea>
                    </div>



                    {{-- DOCUMENT BUILDER --}}
                    <div x-data="documentBuilder({{ Js::from(old('fields', $template->fields ?? [])) }})" class="pt-6 border-t border-neutral-200 dark:border-neutral-800">

                        <div class="flex items-center justify-between mb-4">

                            <h2 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                                Requested Documents
                            </h2>

                            <button type="button" @click="addField"
                                class="h-8 px-3 inline-flex items-center gap-1
                                           text-xs font-medium
                                           border
                                           text-primary-600 border-primary-600/30 bg-primary-600/10
                                           hover:bg-primary-600 hover:text-white
                                           dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                                           dark:hover:bg-primary-500 dark:hover:text-white
                                           transition cursor-pointer">

                                <x-lucide-plus class="w-3.5 h-3.5" />
                                Add Row
                            </button>

                        </div>


                        {{-- ROWS --}}
                        <template x-for="(field, index) in fields" :key="index">

                            <div
                                class="border border-neutral-200 dark:border-neutral-700
                                        p-4 mb-3">

                                {{-- HEADER --}}
                                <div class="flex items-center justify-between mb-3">

                                    <span class="text-xs font-semibold text-neutral-500">
                                        Row <span x-text="index + 1"></span>
                                    </span>

                                    <button type="button" x-show="fields.length > 1" @click="removeField(index)"
                                        class="text-red-600 text-xs font-semibold hover:underline cursor-pointer">
                                        Remove
                                    </button>

                                </div>


                                <div class="grid md:grid-cols-2 gap-4">

                                    {{-- LABEL --}}
                                    <div>
                                        <label class="block text-xs text-neutral-500 dark:text-neutral-400 mb-1">
                                            Document Name *
                                        </label>

                                        <input type="text" :name="`fields[${index}][label]`" x-model="field.label"
                                            required
                                            class="w-full h-9 px-3 text-sm
                                                      bg-white dark:bg-neutral-900
                                                      text-neutral-900 dark:text-neutral-100
                                                      placeholder-neutral-400 dark:placeholder-neutral-500
                                                      border border-neutral-300 dark:border-neutral-700
                                                      focus:outline-none focus:border-primary-500
                                                      transition">
                                    </div>


                                    {{-- REQUIRED --}}
                                    <div class="flex items-center gap-2 mt-6">

                                        <input type="hidden" :name="`fields[${index}][required]`" value="0">

                                        <input type="checkbox" :name="`fields[${index}][required]`" value="1"
                                            x-model="field.required"
                                            class="w-4 h-4
                                                      border-neutral-300 dark:border-neutral-600
                                                      bg-white dark:bg-neutral-800
                                                      text-primary-600 cursor-pointer">

                                        <span class="text-sm text-neutral-700 dark:text-neutral-300">
                                            Required document
                                        </span>

                                    </div>

                                </div>


                                {{-- NOTE --}}
                                <div class="mt-3">
                                    <label class="block text-xs text-neutral-500 dark:text-neutral-400 mb-1">
                                        Note
                                    </label>

                                    <input type="text" :name="`fields[${index}][note]`" x-model="field.note"
                                        class="w-full h-9 px-3 text-sm
                                                  bg-white dark:bg-neutral-900
                                                  text-neutral-900 dark:text-neutral-100
                                                  placeholder-neutral-400 dark:placeholder-neutral-500
                                                  border border-neutral-300 dark:border-neutral-700
                                                  focus:outline-none focus:border-primary-500
                                                  transition">
                                </div>

                                <input type="hidden" :name="`fields[${index}][type]`" value="file">

                            </div>

                        </template>

                    </div>



                    {{-- STATUS --}}
                    <div>
                        <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">
                            Status
                        </label>

                        <select name="is_active"
                            class="w-full h-9 px-3 text-sm
                                       bg-white dark:bg-neutral-900
                                       text-neutral-900 dark:text-neutral-100
                                       border border-neutral-300 dark:border-neutral-700
                                       focus:outline-none focus:border-primary-500
                                       transition">

                            <option value="1" {{ old('is_active', $template->is_active) ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0" {{ !old('is_active', $template->is_active) ? 'selected' : '' }}>
                                Disabled
                            </option>

                        </select>
                    </div>



                    {{-- ACTIONS --}}
                    <div class="pt-6 flex flex-col sm:flex-row gap-3">

                        <button type="submit"
                            class="w-full sm:w-auto h-9 px-5
                                       inline-flex items-center justify-center gap-2
                                       text-sm font-semibold
                                       border
                                       text-primary-600 border-primary-600/30 bg-primary-600/10
                                       hover:bg-primary-600 hover:text-white
                                       dark:text-primary-400 dark:border-primary-400/40 dark:bg-primary-400/10
                                       dark:hover:bg-primary-500 dark:hover:text-white
                                       transition cursor-pointer">

                            <x-lucide-save class="w-4 h-4" />
                            Update Template
                        </button>

                        <a href="{{ route('templates.index') }}"
                            class="w-full sm:w-auto h-9 px-5
                                  inline-flex items-center justify-center gap-2
                                  text-sm font-semibold
                                  border border-neutral-300 dark:border-neutral-700
                                  text-neutral-700 dark:text-neutral-300
                                  hover:bg-neutral-100 dark:hover:bg-neutral-800
                                  transition cursor-pointer">

                            <x-lucide-circle-x class="w-4 h-4" />
                            Cancel
                        </a>

                    </div>

                </form>

            </div>
        </div>

    </div>


    {{-- ALPINE NORMALIZATION --}}
    <script>
        function documentBuilder(initialFields) {

            let safeFields = [];

            if (Array.isArray(initialFields)) {
                safeFields = initialFields;
            } else if (typeof initialFields === 'string' && initialFields.length) {
                try {
                    safeFields = JSON.parse(initialFields);
                } catch {
                    safeFields = [];
                }
            }

            safeFields = safeFields
                .filter(field => field && typeof field === 'object')
                .map(field => ({
                    label: field.label ?? '',
                    type: field.type ?? 'file',
                    required: field.required === true ||
                        field.required === 1 ||
                        field.required === "1",
                    note: typeof field.note === 'string' ? field.note : ''
                }));

            if (!safeFields.length) {
                safeFields = [{
                    label: '',
                    type: 'file',
                    required: false,
                    note: ''
                }];
            }

            return {

                fields: safeFields,

                addField() {
                    this.fields.unshift({
                        label: '',
                        type: 'file',
                        required: false,
                        note: ''
                    });

                    this.$nextTick(() => {
                        document.querySelector('[name="fields[0][label]"]')?.focus();
                    });
                },

                removeField(index) {
                    if (this.fields.length === 1) return;
                    this.fields.splice(index, 1);
                }
            }
        }
    </script>

@endsection
