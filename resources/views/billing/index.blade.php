<!DOCTYPE html>
<html lang="en">

<head>
    <title>Processing Payment - FileCollect</title>

    @include('layouts.head')

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen flex items-center justify-center">

    <!-- BACKGROUND -->
    <div class="absolute inset-0 bg-linear-to-br from-blue-50 via-white to-indigo-50"></div>

    <div class="relative w-full max-w-md">

        <!-- CARD -->
        <div class="border border-gray-200 bg-white p-8 space-y-6 shadow-sm">

            <!-- HEADER -->
            <div class="flex items-center gap-3">
                <div class="p-2 border border-blue-200 bg-blue-50">
                    <x-lucide-credit-card class="w-5 h-5 text-blue-600" />
                </div>

                <div>
                    <h2 id="title" class="text-lg font-semibold">
                        Processing Payment
                    </h2>
                    <p class="text-xs text-gray-500">
                        Secure checkout in progress
                    </p>
                </div>
            </div>

            <!-- LOADER -->
            <div class="flex justify-center py-6">
                <div class="relative">
                    <div class="w-12 h-12 border-2 border-gray-200"></div>
                    <div class="absolute inset-0 border-2 border-blue-600 border-t-transparent animate-spin"></div>
                </div>
            </div>

            <!-- MESSAGE -->
            <p id="message" class="text-sm text-center text-gray-600">
                Initializing Razorpay subscription...
            </p>

            {{-- <!-- HINT -->
            <div class="flex items-center justify-center gap-2 text-xs text-gray-500">
                <x-lucide-info class="w-4 h-4" />
                <span>Use UPI: <span class="font-medium text-gray-700">success@razorpay</span></span>
            </div> --}}

            <!-- ERROR -->
            <div id="error" class="text-sm text-red-600 flex items-start gap-2">
                <x-lucide-x-circle class="w-4 h-4 mt-0.5" />
                <span></span>
            </div>

            <!-- RETRY -->
            <div id="retry-container" class="hidden">
                <button onclick="startPayment()"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium flex items-center justify-center gap-2 transition">

                    <x-lucide-refresh-ccw class="w-4 h-4" />
                    Retry Payment
                </button>
            </div>

        </div>

        <!-- FOOTER -->
        <p class="text-center text-xs text-gray-400 mt-4">
            Secured by Razorpay • FileCollect
        </p>

    </div>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        const plan = "{{ session('selected_plan') }}";
        const billing = "{{ session('selected_billing') }}";

        async function startPayment() {

            document.getElementById('error').classList.add('hidden');
            document.getElementById('retry-container').classList.add('hidden');
            document.getElementById('title').innerText = "Processing Payment...";
            document.getElementById('message').innerText = "Please wait while we prepare checkout.";

            try {

                let response = await fetch('/subscriptions/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        plan,
                        billing
                    })
                });

                if (!response.ok) {
                    throw new Error("Server error");
                }

                let data = await response.json();

                if (!data.id) {
                    throw new Error("Subscription failed");
                }

                openRazorpay(data.id);

            } catch (e) {
                showError("Failed to start payment. Try again.");
            }
        }

        function openRazorpay(subscriptionId) {

            let options = {
                key: "{{ config('services.razorpay.key') }}",
                subscription_id: subscriptionId,
                name: "FileCollect",
                description: "Secure Document Collection SaaS",

                handler: async function(response) {

                    try {

                        let verify = await fetch('/subscriptions/verify', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf
                            },
                            body: JSON.stringify({
                                payment_id: response.razorpay_payment_id,
                                subscription_id: response.razorpay_subscription_id,
                                signature: response.razorpay_signature
                            })
                        });

                        let result = await verify.json();

                        if (result.status === 'success') {

                            document.getElementById('title').innerText = "Payment Successful";
                            document.getElementById('message').innerText = "Redirecting to dashboard...";

                            setTimeout(() => {
                                window.location.href = '/dashboard';
                            }, 1200);

                        } else {
                            throw new Error("Verification failed");
                        }

                    } catch (e) {
                        showError("Payment verification failed.");
                    }
                },

                modal: {
                    ondismiss: function() {
                        showError("Payment cancelled.");
                    }
                },

                prefill: {
                    name: "{{ auth()->user()->first_name ?? '' }}",
                    email: "{{ auth()->user()->email ?? '' }}"
                },

                theme: {
                    color: "#2563eb"
                }
            };

            let rzp = new Razorpay(options);

            rzp.on('payment.failed', function(response) {
                showError(response.error.description || "Payment failed. Use UPI.");
            });

            rzp.open();
        }

        function showError(message) {

            document.getElementById('title').innerText = "Payment Issue";
            document.getElementById('message').innerText = "";

            const errorBox = document.getElementById('error');
            errorBox.classList.remove('hidden');
            errorBox.querySelector('span').innerText = message;

            document.getElementById('retry-container').classList.remove('hidden');
        }

        document.addEventListener('DOMContentLoaded', startPayment);
    </script>

</body>

</html>
