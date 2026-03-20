<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pricing - Your SaaS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50">

    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-indigo-600">YourSaaS</h1>

            <div class="space-x-6">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-indigo-600">Home</a>
                <a href="{{ route('pricing') }}" class="text-indigo-600 font-medium">Pricing</a>

                @auth
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-indigo-600">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Login</a>
                @endauth
            </div>
        </div>
    </nav>


    <section class="py-20" x-data="{ billing: 'monthly' }">

        <div class="max-w-7xl mx-auto px-6 text-center">

            <h2 class="text-4xl font-bold mb-4">Simple & Transparent Pricing</h2>
            <p class="text-gray-500 mb-8">Choose the plan that fits your business.</p>

            <!-- Billing Toggle -->
            <div class="flex justify-center mb-12">
                <div class="bg-gray-200 p-1 rounded-full flex">

                    <button type="button" @click="billing = 'monthly'" :class="billing === 'monthly'
                            ?
                            'bg-indigo-600 text-white' :
                            'text-gray-700'" class="px-6 py-2 rounded-full text-sm transition-all duration-200">
                        Monthly
                    </button>

                    <button type="button" @click="billing = 'yearly'" :class="billing === 'yearly'
                            ?
                            'bg-indigo-600 text-white' :
                            'text-gray-700'" class="px-6 py-2 rounded-full text-sm transition-all duration-200">
                        Yearly
                    </button>

                </div>
            </div>


            <div class="overflow-x-auto bg-white rounded-2xl shadow">

                <table class="min-w-full text-sm text-left border-collapse">

                    <!-- Header -->
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-4 font-semibold">Features</th>

                            @foreach ($plans as $plan)
                            <th class="p-4 text-center font-semibold">

                                {{ $plan->name }}

                                @if ($plan->is_popular)
                                <div class="text-xs text-indigo-600 font-medium">
                                    Most Popular
                                </div>
                                @endif

                                <div class="mt-3 text-xl font-bold">
                                    @if ($plan->isFree())
                                    $0
                                    @else
                                    <span x-text="billing === 'monthly'
                                                ? '${{ number_format((float) $plan->monthly_price, 0) }}'
                                                : '${{ number_format((float) $plan->yearly_price, 0) }}'">
                                    </span>
                                    <div class="text-xs text-gray-500"
                                        x-text="billing === 'monthly' ? '/month' : '/year'">
                                    </div>
                                    @endif
                                </div>

                            </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        <!-- Core Limits -->

                        <tr>
                            <td class="p-4 font-medium bg-gray-50">Company Users</td>
                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->hasUnlimited('company_users') ? 'Unlimited' : $plan->company_users }}
                            </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="p-4 font-medium bg-gray-50">Clients</td>
                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->hasUnlimited('clients') ? 'Unlimited' : $plan->clients }}
                            </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="p-4 font-medium bg-gray-50">Document Requests</td>
                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->hasUnlimited('document_requests') ? 'Unlimited' : $plan->document_requests }}
                            </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="p-4 font-medium bg-gray-50">Templates</td>
                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->hasUnlimited('template_limit') ? 'Unlimited' : $plan->template_limit }}
                            </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="p-4 font-medium bg-gray-50">Storage</td>
                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->hasUnlimited('storage_mb') ? 'Unlimited' : $plan->storage_mb . ' MB' }}
                            </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="p-4 font-medium bg-gray-50">Max File Size</td>
                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->hasUnlimited('file_size_limit_mb') ? 'Unlimited' : $plan->file_size_limit_mb . ' MB' }}
                            </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="p-4 font-medium bg-gray-50">WhatsApp Limit</td>
                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->hasUnlimited('whatsapp_limit') ? 'Unlimited' : $plan->whatsapp_limit }}
                            </td>
                            @endforeach
                        </tr>

                        <!-- Boolean Features -->

                        <tr>
                            <td class="p-4 font-medium bg-gray-50">Client Portal</td>
                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->client_portal ? '✔' : '—' }}
                            </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="p-4 font-medium bg-gray-50">OTP Login</td>
                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->otp_login ? '✔' : '—' }}
                            </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="p-4 font-medium bg-gray-50">Approval Workflow</td>
                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->approve_workflow ? '✔' : '—' }}
                            </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="p-4 font-medium bg-gray-50">White Label</td>
                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->white_label ? '✔' : '—' }}
                            </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="p-4 font-medium bg-gray-50">Priority Support</td>
                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->priority_support ? '✔' : '—' }}
                            </td>
                            @endforeach
                        </tr>

                        <!-- Select Buttons -->
                        <tr>
                            <td class="p-4 bg-gray-50"></td>

                            @foreach ($plans as $plan)
                            <td class="p-4 text-center">
                                <form method="POST" action="{{ route('select.plan') }}">
                                    @csrf
                                    <input type="hidden" name="plan" value="{{ $plan->slug }}">
                                    <input type="hidden" name="billing_cycle" :value="billing">

                                    <button type="submit"
                                        class="bg-gray-900 text-white px-6 py-2 rounded-lg hover:bg-gray-800 transition">
                                        Get {{ $plan->name }}
                                    </button>
                                </form>
                            </td>
                            @endforeach
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </section>

</body>

</html>