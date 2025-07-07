<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription Plans') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
                        <h3 class="text-2xl font-bold text-gray-900">All Subscription Plans</h3>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('subscription-plans.create') }}"
                               class="bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-bold py-3 px-6 rounded-lg shadow-md flex items-center gap-2 transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-400"
                               title="Create New Plan">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create New Plan
                            </a>
                            <!-- Search Bar -->
                            <form method="GET" action="" class="relative flex items-center">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" /></svg>
                                </span>
                                <input type="text" name="search" placeholder="Search plans..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full transition text-gray-700 placeholder-gray-400" value="{{ request('search') }}">
                            </form>
                        </div>
                    </div>

                    <div x-data="purchaseModal()">
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200 rounded-xl overflow-hidden">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plan Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Daily Earning</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Duration (Days)</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Limit</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($subscriptionPlans as $plan)
                                        <tr class="transition hover:bg-blue-50 even:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $plan->plan_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${{ number_format($plan->price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${{ number_format($plan->daily_earning, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $plan->duration }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $plan->limit }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    @if (Auth::user()->role === 'admin')
                                                        <a href="{{ route('subscription-plans.show', $plan) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded transition" title="View">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                            View
                                                        </a>
                                                        <a href="{{ route('subscription-plans.edit', $plan) }}" class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 hover:bg-yellow-200 rounded transition" title="Edit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m2 0a2 2 0 012 2v2m0 2v6a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h2" /></svg>
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('subscription-plans.destroy', $plan) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded transition" title="Delete" onclick="return confirm('Are you sure you want to delete this plan?')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @elseif (Auth::user()->role === 'user')
                                                        <button type="button"
                                                            class="inline-flex items-center px-3 py-1 bg-green-600 text-white hover:bg-green-700 rounded transition"
                                                            title="Purchase Plan"
                                                            @click="openPurchaseModal({
                                                                id: {{ $plan->id }},
                                                                plan_name: '{{ $plan->plan_name }}',
                                                                price: '{{ number_format($plan->price, 2) }}',
                                                                daily_earning: '{{ number_format($plan->daily_earning, 2) }}',
                                                                duration: '{{ $plan->duration }}',
                                                                limit: '{{ $plan->limit }}'
                                                            })">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                                            Purchase Plan
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No subscription plans found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <x-modal name="purchase-plan-modal" maxWidth="md">
                            <div class="p-6">
                                <h2 class="text-xl font-bold mb-4">Purchase Plan</h2>
                                <div class="mb-4">
                                    <div class="font-semibold">Plan Name:</div>
                                    <div x-text="plan.plan_name"></div>
                                </div>
                                <div class="mb-2 flex gap-4">
                                    <div>
                                        <div class="font-semibold">Price:</div>
                                        <div x-text="'$' + plan.price"></div>
                                    </div>
                                    <div>
                                        <div class="font-semibold">Daily Earning:</div>
                                        <div x-text="'$' + plan.daily_earning"></div>
                                    </div>
                                </div>
                                <div class="mb-2 flex gap-4">
                                    <div>
                                        <div class="font-semibold">Duration:</div>
                                        <div x-text="plan.duration + ' days'"></div>
                                    </div>
                                    <div>
                                        <div class="font-semibold">Limit:</div>
                                        <div x-text="plan.limit"></div>
                                    </div>
                                </div>
                                <form @submit.prevent="proceedToPayment">
                                    <div class="mb-4">
                                        <label class="block font-semibold mb-1">Payment Method</label>
                                        <select x-model="payment_method" class="w-full border rounded px-3 py-2">
                                            <option value="">Select method</option>
                                            <option value="easypaisa">Easypaisa</option>
                                            <option value="jazzcash">Jazzcash</option>
                                            <option value="nayapay">Nayapay</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block font-semibold mb-1">Account Number (Mobile Number)</label>
                                        <input type="text" x-model="account_number" class="w-full border rounded px-3 py-2" placeholder="03XXXXXXXXX">
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" class="mr-2 px-4 py-2 bg-gray-200 rounded" @click="close">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Proceed to Payment</button>
                                    </div>
                                </form>
                            </div>
                        </x-modal>
                    </div>

                    @if($subscriptionPlans->hasPages())
                        <div class="mt-4">
                            {{ $subscriptionPlans->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function purchaseModal() {
            return {
                show: false,
                plan: {},
                payment_method: '',
                account_number: '',
                openPurchaseModal(plan) {
                    this.plan = plan;
                    this.payment_method = '';
                    this.account_number = '';
                    this.show = true;
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'purchase-plan-modal' }));
                },
                close() {
                    this.show = false;
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'purchase-plan-modal' }));
                },
                proceedToPayment() {
                    // TODO: Implement payment logic or form submission
                    alert('Proceeding to payment for ' + this.plan.plan_name + ' via ' + this.payment_method + ' (' + this.account_number + ')');
                    this.close();
                }
            }
        }
    </script>
</x-app-layout> 