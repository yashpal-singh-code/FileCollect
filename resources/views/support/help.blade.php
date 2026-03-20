@extends('layouts.app')

@section('title', 'Help Center')
@section('description', 'Learn how to use FileCollect to request and collect documents.')

@section('content')

    <div class="max-w-6xl mx-auto px-4 py-6 space-y-8">

        <!-- HEADER -->
        <div>
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">
                Help Center
            </h1>

            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                Learn how to request and collect documents using FileCollect.
            </p>
        </div>


        <!-- WORKFLOW -->
        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-6">

            <h2 class="text-lg font-semibold mb-4">
                How FileCollect Works
            </h2>

            <div class="grid md:grid-cols-3 gap-4 text-sm">

                <div class="p-4 border rounded-lg dark:border-neutral-800">
                    <div class="font-medium mb-1">1. Add Client</div>
                    <p class="text-neutral-500 dark:text-neutral-400">
                        Add the client before requesting documents.
                    </p>
                </div>

                <div class="p-4 border rounded-lg dark:border-neutral-800">
                    <div class="font-medium mb-1">2. Create Template</div>
                    <p class="text-neutral-500 dark:text-neutral-400">
                        Define which documents you want from clients.
                    </p>
                </div>

                <div class="p-4 border rounded-lg dark:border-neutral-800">
                    <div class="font-medium mb-1">3. Create Team</div>
                    <p class="text-neutral-500 dark:text-neutral-400">
                        Invite team members to manage document requests.
                    </p>
                </div>

                <div class="p-4 border rounded-lg dark:border-neutral-800">
                    <div class="font-medium mb-1">4. Send Request</div>
                    <p class="text-neutral-500 dark:text-neutral-400">
                        Request documents from your client.
                    </p>
                </div>

                <div class="p-4 border rounded-lg dark:border-neutral-800">
                    <div class="font-medium mb-1">5. Client Uploads</div>
                    <p class="text-neutral-500 dark:text-neutral-400">
                        Client uploads files using email link or magic link.
                    </p>
                </div>

                <div class="p-4 border rounded-lg dark:border-neutral-800">
                    <div class="font-medium mb-1">6. Track Status</div>
                    <p class="text-neutral-500 dark:text-neutral-400">
                        Monitor uploaded documents and download them.
                    </p>
                </div>

            </div>

        </div>


        <!-- STEP 1 -->
        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-6">

            <h2 class="text-lg font-semibold mb-3">
                Step 1 — Add a Client
            </h2>

            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                Clients help you organize document requests. Every request is linked to a client.
            </p>

            <ul class="mt-3 list-disc list-inside text-sm text-neutral-600 dark:text-neutral-400 space-y-1">
                <li>Go to the <strong>Clients</strong> page</li>
                <li>Click <strong>Add Client</strong></li>
                <li>Enter name and email</li>
                <li>Save the client</li>
            </ul>

        </div>


        <!-- STEP 2 -->
        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-6">

            <h2 class="text-lg font-semibold mb-3">
                Step 2 — Create a Template
            </h2>

            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                Templates define which documents you want clients to upload.
            </p>

            <div class="mt-3 text-sm text-neutral-500 dark:text-neutral-400">

                Example template:

                Passport Copy
                Address Proof
                Bank Statement

            </div>

        </div>


        <!-- STEP 3 -->
        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-6">

            <h2 class="text-lg font-semibold mb-3">
                Step 3 — Create a Team (Optional)
            </h2>

            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                Teams allow multiple staff members to manage document requests and client communication.
            </p>

            <ul class="mt-3 list-disc list-inside text-sm text-neutral-600 dark:text-neutral-400 space-y-1">
                <li>Go to <strong>Teams</strong></li>
                <li>Invite team members</li>
                <li>Assign roles and permissions</li>
            </ul>

        </div>


        <!-- STEP 4 -->
        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-6">

            <h2 class="text-lg font-semibold mb-3">
                Step 4 — Send Document Request
            </h2>

            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                After creating clients and templates, you can request documents.
            </p>

            <ul class="mt-3 list-disc list-inside text-sm text-neutral-600 dark:text-neutral-400 space-y-1">
                <li>Go to <strong>Document Requests</strong></li>
                <li>Click <strong>Create Request</strong></li>
                <li>Select client</li>
                <li>Select template</li>
                <li>Send request</li>
            </ul>

        </div>


        <!-- STEP 5 -->
        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-6">

            <h2 class="text-lg font-semibold mb-3">
                Step 5 — Send Email or Magic Link
            </h2>

            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                FileCollect allows two ways to send document requests.
            </p>

            <div class="grid md:grid-cols-2 gap-4 mt-4 text-sm">

                <div class="p-4 border rounded-lg dark:border-neutral-800">
                    <strong>Email Request</strong>
                    <p class="mt-1 text-neutral-500 dark:text-neutral-400">
                        Client receives an email with a secure upload link.
                    </p>
                </div>

                <div class="p-4 border rounded-lg dark:border-neutral-800">
                    <strong>Magic Upload Link</strong>
                    <p class="mt-1 text-neutral-500 dark:text-neutral-400">
                        Share a direct upload link via WhatsApp, SMS, or email.
                    </p>
                </div>

            </div>

        </div>


        <!-- STEP 6 -->
        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-6">

            <h2 class="text-lg font-semibold mb-3">
                Step 6 — Client Uploads Documents
            </h2>

            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                Clients open the link and upload required files such as PDFs or images.
            </p>

        </div>


        <!-- STEP 7 -->
        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-6">

            <h2 class="text-lg font-semibold mb-3">
                Step 7 — Track Document Status
            </h2>

            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                You can monitor request progress in the Document Requests dashboard.
            </p>

            <div class="mt-3 text-sm text-neutral-500 dark:text-neutral-400">

                Status examples:

                Pending
                Uploaded
                Completed

            </div>

        </div>


        <!-- SUPPORT -->
        <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-6">

            <h2 class="text-lg font-semibold mb-2">
                Need Help?
            </h2>

            <p class="text-sm text-neutral-500 dark:text-neutral-400 mb-4">
                If you cannot find the answer here, contact our support team.
            </p>

            <a href="#"
                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
           bg-primary-600 text-white hover:bg-primary-700 transition">

                Contact Support

            </a>

        </div>


    </div>

@endsection
