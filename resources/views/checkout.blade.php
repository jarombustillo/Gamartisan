@extends('layouts.app')

@section('title', 'Checkout - Gamartisan')

@section('content')
<div class="pt-24 min-h-screen">
    <div class="max-w-4xl mx-auto px-6 py-8">
        <h1 class="font-display text-3xl font-bold text-forest-dark mb-8">Checkout</h1>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.store', $product) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Product Summary -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Product</h3>
                        <div class="flex items-center gap-4">
                            @if($product->prodImage)
                                <img src="{{ asset($product->prodImage) }}" alt="{{ $product->prodName }}" class="w-20 h-20 object-cover rounded-lg">
                            @else
                                <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800">{{ $product->prodName }}</h4>
                                <p class="text-sm text-gray-500">by {{ $product->artist->fullName ?? 'Unknown' }}</p>
                                <p class="text-forest font-bold mt-1">₱{{ number_format($product->prodPrice, 2) }} each</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quantity</h3>
                        <input type="number" name="ordQuantity" id="ordQuantity" value="{{ old('ordQuantity', 1) }}" min="1"
                            class="w-32 px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-forest focus:border-transparent outline-none text-center"
                            onchange="updateTotal()">
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Method</h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-forest transition-colors">
                                <input type="radio" name="payMethod" value="Cash on Delivery" checked class="text-forest focus:ring-forest">
                                <div>
                                    <p class="font-medium text-gray-800">Cash on Delivery</p>
                                    <p class="text-sm text-gray-500">Pay when you receive the item</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-forest transition-colors">
                                <input type="radio" name="payMethod" value="GCash" class="text-forest focus:ring-forest">
                                <div>
                                    <p class="font-medium text-gray-800">GCash</p>
                                    <p class="text-sm text-gray-500">Pay via GCash mobile wallet</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-forest transition-colors">
                                <input type="radio" name="payMethod" value="Bank Transfer" class="text-forest focus:ring-forest">
                                <div>
                                    <p class="font-medium text-gray-800">Bank Transfer</p>
                                    <p class="text-sm text-gray-500">Direct bank transfer</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Choose Charity -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Choose a Charity</h3>
                        <p class="text-sm text-gray-500 mb-4">10% of your purchase will be donated to the charity you select.</p>
                        <div class="space-y-3">
                            @foreach($charities as $index => $charity)
                                <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-pink-400 transition-colors">
                                    <input type="radio" name="charityID" value="{{ $charity->charityID }}" {{ $index === 0 ? 'checked' : '' }} class="mt-1 text-pink-500 focus:ring-pink-500">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $charity->charName }}</p>
                                        @if($charity->charDesc)
                                            <p class="text-sm text-gray-500">{{ Str::limit($charity->charDesc, 80) }}</p>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div>
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-28">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span id="subtotal" class="font-medium text-gray-800">₱{{ number_format($product->prodPrice, 2) }}</span>
                            </div>
                            <div class="border-t border-gray-100 pt-3">
                                <p class="text-xs text-gray-400 mb-2 uppercase tracking-wider">Revenue Breakdown</p>
                            </div>
                            <div class="flex justify-between text-green-600">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                    Seller Payout (85%)
                                </span>
                                <span id="sellerPayout" class="font-medium">₱{{ number_format($product->prodPrice * 0.85, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-pink-600">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    Charity Donation (10%)
                                </span>
                                <span id="donation" class="font-medium">₱{{ number_format($product->prodPrice * 0.10, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-indigo-600">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    Platform Fee (5%)
                                </span>
                                <span id="platformFee" class="font-medium">₱{{ number_format($product->prodPrice * 0.05, 2) }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3 flex justify-between">
                                <span class="font-semibold text-gray-800">Total</span>
                                <span id="total" class="font-bold text-xl text-forest">₱{{ number_format($product->prodPrice, 2) }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-3">* The charity donation and platform fee are included in the total price.</p>

                        <button type="submit" class="w-full mt-6 bg-forest text-white py-3 rounded-xl font-semibold hover:bg-forest-dark transition-colors shadow-lg">
                            Place Order
                        </button>

                        <a href="{{ route('products.show', $product) }}" class="block text-center text-gray-500 hover:text-gray-700 mt-3 text-sm">
                            ← Back to Product
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const unitPrice = {{ $product->prodPrice }};

    function updateTotal() {
        const qty = parseInt(document.getElementById('ordQuantity').value) || 1;
        const subtotal = unitPrice * qty;
        const sellerPayout = subtotal * 0.85;
        const donation = subtotal * 0.10;
        const platformFee = subtotal * 0.05;

        const fmt = (val) => '₱' + val.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        document.getElementById('subtotal').textContent = fmt(subtotal);
        document.getElementById('sellerPayout').textContent = fmt(sellerPayout);
        document.getElementById('donation').textContent = fmt(donation);
        document.getElementById('platformFee').textContent = fmt(platformFee);
        document.getElementById('total').textContent = fmt(subtotal);
    }
</script>
@endpush
@endsection
