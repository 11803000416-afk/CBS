@extends('layouts.app-pro')

@section('title', 'Used Car Valuation')
@section('subtitle', 'Estimate selling price and admin commission')

@section('content')
<div class="space-y-8">
    <div class="rounded-3xl bg-white border border-gray-200 shadow-sm p-6 sm:p-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="max-w-3xl">
                <p class="inline-flex items-center rounded-full bg-blue-50 px-4 py-1 text-xs font-bold uppercase tracking-[0.2em] text-blue-700 border border-blue-100">
                    Valuation tool
                </p>
                <h1 class="mt-4 text-3xl sm:text-4xl font-bold text-gray-900 leading-tight">Used Car Valuation</h1>
                <p class="mt-3 text-gray-600 text-base sm:text-lg leading-relaxed">
                    Estimate a clean selling price using the same CBS visual system. The platform keeps a transparent
                    0.5% admin profit commission from the final value.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 min-w-[280px]">
                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-center">
                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Base</p>
                    <p class="mt-1 text-xl font-bold text-gray-900">Nu. {{ number_format($basePrice, 0) }}</p>
                </div>
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-center">
                    <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">Estimated</p>
                    <p class="mt-1 text-xl font-bold text-gray-900">Nu. {{ number_format($calculatedPrice, 0) }}</p>
                </div>
                <div class="rounded-2xl border border-orange-100 bg-orange-50 p-4 text-center">
                    <p class="text-xs font-semibold text-orange-700 uppercase tracking-wide">Admin fee</p>
                    <p class="mt-1 text-xl font-bold text-gray-900">Nu. {{ number_format($adminCommission, 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
        <div class="xl:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 px-6 sm:px-8 py-5">
                    <h2 class="text-2xl font-bold text-gray-900">Used Car Price Calculator</h2>
                    <p class="text-gray-600 mt-1">Responsive, readable, and styled to match the CBS dashboard system.</p>
                </div>

                <div class="p-6 sm:p-8">
                    <form method="GET" action="{{ route('valuation.index') }}" class="space-y-7">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="text-gray-700 font-medium">I am a</span>
                            @foreach(['buyer' => 'Buyer', 'seller' => 'Seller'] as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="{{ $value }}" class="peer sr-only" {{ $role === $value ? 'checked' : '' }}>
                                    <span class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 peer-focus-visible:ring-2 peer-focus-visible:ring-blue-200">
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">Manufacturing Year</label>
                                <select id="year" name="year" class="input-field-light rounded-2xl py-3.5">
                                    @for($y = now()->year; $y >= now()->year - 20; $y--)
                                        <option value="{{ $y }}" @selected($year === $y)>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                                <select id="city" name="city" class="input-field-light rounded-2xl py-3.5">
                                    @foreach($cities as $cityName => $factor)
                                        <option value="{{ $cityName }}" @selected($city === $cityName)>{{ $cityName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="car" class="block text-sm font-semibold text-gray-700 mb-2">Car</label>
                                <select id="car" name="car" class="input-field-light rounded-2xl py-3.5">
                                    @foreach($cars as $key => $item)
                                        <option value="{{ $key }}" @selected($selectedCar === $key)>{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="kilometers" class="block text-sm font-semibold text-gray-700 mb-2">Kilometers Driven</label>
                                <input id="kilometers" name="kilometers" type="number" min="0" value="{{ $kilometers }}" placeholder="45000" class="input-field-light rounded-2xl py-3.5">
                            </div>

                            <div>
                                <label for="owner" class="block text-sm font-semibold text-gray-700 mb-2">Ownership</label>
                                <select id="owner" name="owner" class="input-field-light rounded-2xl py-3.5">
                                    @foreach($owners as $key => $label)
                                        <option value="{{ $key }}" @selected($owner === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3.5 text-white font-bold text-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
                                    Check Value
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 sm:p-8">
                <h3 class="text-2xl font-bold text-gray-900">How the estimate works</h3>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4">
                        Base × year factor × km factor × ownership factor × city factor
                    </div>
                    <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4">
                        Admin commission = 0.5% of estimated selling price
                    </div>
                </div>
            </div>
        </div>

        <aside class="space-y-6 xl:sticky xl:top-24">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5 text-white">
                    <p class="text-xs uppercase tracking-[0.25em] text-blue-100">Result panel</p>
                    <h2 class="mt-2 text-2xl font-bold">Estimated Selling Price</h2>
                </div>

                <div class="p-6 space-y-4">
                    <div class="rounded-2xl bg-blue-50 border border-blue-100 p-4">
                        <p class="text-sm text-blue-700 font-semibold">Estimated value</p>
                        <p class="mt-1 text-3xl font-bold text-gray-900">Nu. {{ number_format($calculatedPrice, 0) }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4">
                            <p class="text-sm text-emerald-700 font-semibold">Seller receives</p>
                            <p class="mt-1 text-xl font-bold text-gray-900">Nu. {{ number_format($sellerPayout, 0) }}</p>
                        </div>
                        <div class="rounded-2xl bg-orange-50 border border-orange-100 p-4">
                            <p class="text-sm text-orange-700 font-semibold">Admin profit</p>
                            <p class="mt-1 text-xl font-bold text-gray-900">Nu. {{ number_format($adminCommission, 0) }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600 space-y-2">
                        <p><span class="font-semibold text-gray-900">Year factor:</span> {{ number_format($yearFactor, 2) }}</p>
                        <p><span class="font-semibold text-gray-900">KM factor:</span> {{ number_format($kmFactor, 2) }}</p>
                        <p><span class="font-semibold text-gray-900">Ownership factor:</span> {{ number_format($ownerFactor, 2) }}</p>
                        <p><span class="font-semibold text-gray-900">City factor:</span> {{ number_format($cityFactor, 2) }}</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
