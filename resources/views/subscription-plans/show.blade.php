<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription Plan Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Plan Details</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('subscription-plans.edit', $subscriptionPlan) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Edit Plan
                            </a>
                            <a href="{{ route('subscription-plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Plans
                            </a>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Plan Name</h4>
                                <p class="mt-1 text-lg text-gray-900">{{ $subscriptionPlan->plan_name }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Price</h4>
                                <p class="mt-1 text-lg text-gray-900">${{ number_format($subscriptionPlan->price, 2) }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Daily Earning</h4>
                                <p class="mt-1 text-lg text-gray-900">${{ number_format($subscriptionPlan->daily_earning, 2) }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Duration</h4>
                                <p class="mt-1 text-lg text-gray-900">{{ $subscriptionPlan->duration }} days</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Limit</h4>
                                <p class="mt-1 text-lg text-gray-900">{{ $subscriptionPlan->limit }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Created At</h4>
                                <p class="mt-1 text-lg text-gray-900">{{ $subscriptionPlan->created_at->format('F j, Y g:i A') }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Last Updated</h4>
                                <p class="mt-1 text-lg text-gray-900">{{ $subscriptionPlan->updated_at->format('F j, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <form action="{{ route('subscription-plans.destroy', $subscriptionPlan) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this plan?')">
                                Delete Plan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 