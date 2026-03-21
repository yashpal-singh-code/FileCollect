@extends('layouts.app')

@section('title', 'Create Template')
@section('description', 'Create reusable document request workflows.')

@section('content')

    <div class="max-w-4xl mx-auto">

        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800">

            {{-- HEADER --}}
            <div class="px-6 py-5 border-b border-neutral-200 dark:border-neutral-800">
                <div class="flex items-center gap-3">
                    <x-lucide-file-plus class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    <div>
                        <h1 class="text-xl font-semibold text-neutral-900 dark:text-neutral-100">
                            Create Template
                        </h1>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                            Create a reusable document request workflow
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6">

                {{-- ERRORS --}}
                @if ($errors->any())
                    <div class="mb-5 p-4 border border-red-500/30 bg-red-500/10">
                        <ul class="text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('templates.store') }}" class="space-y-6">
                    @csrf

                    {{-- NAME --}}
                    <div>
                        <label class="block text-sm mb-1">Template Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full border px-3 h-9">
                    </div>

                    {{-- DESCRIPTION --}}
                    <div>
                        <label class="block text-sm mb-1">Description</label>
                        <textarea name="description" class="w-full border px-3 py-2">{{ old('description') }}</textarea>
                    </div>

                    {{-- DOCUMENT BUILDER --}}
                    <div x-data="documentBuilder(@json(old('fields')))" x-cloak class="pt-6 border-t">

                        <div class="flex justify-between mb-4">
                            <h2 class="text-sm font-semibold">Requested Documents</h2>

                            <button type="button" @click="addField" class="text-xs border px-3 h-8">
                                Add Row
                            </button>
                        </div>

                        <template x-for="(field, index) in fields" :key="field._id">
                            <div class="border p-4 mb-3">

                                <div class="flex justify-between mb-3">
                                    <span class="text-xs">Row <span x-text="index + 1"></span></span>

                                    <button type="button" @click="removeField(index)" x-show="fields.length > 1"
                                        class="text-red-600 text-xs">
                                        Remove
                                    </button>
                                </div>

                                <div class="grid md:grid-cols-2 gap-4">

                                    {{-- LABEL --}}
                                    <input type="text" :name="`fields[${index}][label]`" x-model="field.label"
                                        placeholder="Document Name" class="w-full border px-3 h-9">

                                    {{-- REQUIRED --}}
                                    <div class="flex items-center gap-2">
                                        <input type="hidden" :name="`fields[${index}][required]`" value="0">

                                        <input type="checkbox" :name="`fields[${index}][required]`" x-model="field.required"
                                            value="1">

                                        <span>Required</span>
                                    </div>

                                </div>

                                {{-- NOTE --}}
                                <input type="text" :name="`fields[${index}][note]`" x-model="field.note"
                                    placeholder="Note" class="w-full border px-3 h-9 mt-3">

                                <input type="hidden" :name="`fields[${index}][type]`" value="file">

                            </div>
                        </template>

                    </div>

                    {{-- STATUS --}}
                    <select name="is_active" class="w-full border px-3 h-9">
                        <option value="1">Active</option>
                        <option value="0">Disabled</option>
                    </select>

                    {{-- BUTTON --}}
                    <button class="px-5 py-2 border">
                        Create Template
                    </button>

                </form>
            </div>
        </div>
    </div>

    {{-- ALPINE --}}
    <script>
        function documentBuilder(oldFields = null) {
            return {
                fields: oldFields?.length ?
                    oldFields.map(field => ({
                        ...field,
                        _id: Date.now() + Math.random()
                    })) :
                    [{
                        _id: Date.now() + Math.random(),
                        label: '',
                        required: false,
                        note: ''
                    }],

                addField() {
                    this.fields.push({
                        _id: Date.now() + Math.random(),
                        label: '',
                        required: false,
                        note: ''
                    });
                },

                removeField(index) {
                    if (this.fields.length > 1) {
                        this.fields.splice(index, 1);
                    }
                }
            }
        }
    </script>

@endsection
