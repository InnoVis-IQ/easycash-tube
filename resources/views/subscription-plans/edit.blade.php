<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Subscription Plan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Edit Subscription Plan</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('subscription-plans.show', $subscriptionPlan) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                View Plan
                            </a>
                            <a href="{{ route('subscription-plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Plans
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('subscription-plans.update', $subscriptionPlan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="plan_name" :value="__('Plan Name')" />
                                <x-text-input id="plan_name" class="block mt-1 w-full" type="text" name="plan_name" :value="old('plan_name', $subscriptionPlan->plan_name)" required autofocus />
                                <x-input-error :messages="$errors->get('plan_name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="price" :value="__('Price ($)')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" step="0.01" min="0" :value="old('price', $subscriptionPlan->price)" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="daily_earning" :value="__('Daily Earning ($)')" />
                                <x-text-input id="daily_earning" class="block mt-1 w-full" type="number" name="daily_earning" step="0.01" min="0" :value="old('daily_earning', $subscriptionPlan->daily_earning)" required />
                                <x-input-error :messages="$errors->get('daily_earning')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="duration" :value="__('Duration (Days)')" />
                                <x-text-input id="duration" class="block mt-1 w-full" type="number" name="duration" min="1" :value="old('duration', $subscriptionPlan->duration)" required />
                                <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="limit" :value="__('Limit')" />
                                <x-text-input id="limit" class="block mt-1 w-full" type="number" name="limit" min="1" :value="old('limit', $subscriptionPlan->limit)" required />
                                <x-input-error :messages="$errors->get('limit')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-3">
                                {{ __('Update Plan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 