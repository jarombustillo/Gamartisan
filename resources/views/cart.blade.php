@extends('layouts.app')

@section('title', 'Shopping Cart - Gamartisan')

@section('content')
<div class="pt-24 min-h-screen">
    <div class="max-w-5xl mx-auto px-6 py-8">
        <h1 class="text-3xl font-display font-bold text-forest-dark mb-8">Shopping Cart</h1>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                </svg>
                <p class="text-gray-500 text-lg mb-2">Your cart is empty</p>
                <p class="text-gray-400 mb-6">Browse our products and add something you love!</p>
                <a href="{{ route('products') }}" class="inline-flex items-center px-6 py-3 bg-forest text-white rounded-lg font-medium hover:bg-forest-dark transition-colors">
                    Browse Products
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Select All -->
                    <div class="flex items-center gap-3 px-2">
                        <input type="checkbox" id="select-all" checked
                            class="w-5 h-5 text-forest rounded border-gray-300 focus:ring-forest cursor-pointer">
                        <label for="select-all" class="text-sm font-medium text-gray-600 cursor-pointer">Select All</label>
                    </div>

                    @foreach($cartItems as $item)
                        <div class="cart-item bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex gap-4 items-center"
                             data-price="{{ $item->product->prodPrice }}"
                             data-item-id="{{ $item->cartItemID }}"
                             data-update-url="{{ route('cart.update', $item) }}"
                             data-token="{{ csrf_token() }}">

                            <!-- Checkbox -->
                            <input type="checkbox" class="item-checkbox w-5 h-5 text-forest rounded border-gray-300 focus:ring-forest cursor-pointer flex-shrink-0" checked data-id="{{ $item->cartItemID }}">

                            <!-- Product Image -->
                            <a href="{{ route('products.show', $item->product) }}" class="flex-shrink-0">
                                @if($item->product->prodImage)
                                    <img src="{{ asset($item->product->prodImage) }}" alt="{{ $item->product->prodName }}" class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </a>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('products.show', $item->product) }}" class="font-semibold text-gray-800 hover:text-forest transition-colors">
                                    {{ $item->product->prodName }}
                                </a>
                                <p class="text-sm text-gray-500">by {{ $item->product->artist->fullName ?? 'Unknown Artist' }}</p>
                                <p class="text-forest font-bold mt-1">₱{{ number_format($item->product->prodPrice, 2) }}</p>
                            </div>

                            <!-- Quantity -->
                            <div class="flex items-center gap-1">
                                <button type="button" class="qty-btn qty-minus w-8 h-8 rounded-lg border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors">−</button>
                                <input type="number" class="qty-input w-14 px-1 py-1.5 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none" value="{{ $item->quantity }}" min="1">
                                <button type="button" class="qty-btn qty-plus w-8 h-8 rounded-lg border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors">+</button>
                            </div>

                            <!-- Subtotal -->
                            <div class="text-right min-w-[90px]">
                                <p class="item-subtotal font-bold text-gray-800">₱{{ number_format($item->product->prodPrice * $item->quantity, 2) }}</p>
                            </div>

                            <!-- Remove -->
                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-28">
                        <h2 class="font-semibold text-lg text-gray-800 mb-4">Order Summary</h2>

                        <div class="space-y-2 mb-4 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal (<span id="summary-count">{{ $cartItems->sum('quantity') }}</span> items)</span>
                                <span id="summary-subtotal">₱{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-pink-600">
                                <span>Charity Donation (10%)</span>
                                <span id="summary-donation">₱{{ number_format($total * 0.10, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>Platform Fee (5%)</span>
                                <span id="summary-fee">₱{{ number_format($total * 0.05, 2) }}</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4 mb-6">
                            <div class="flex justify-between text-lg font-bold text-forest-dark">
                                <span>Total</span>
                                <span id="summary-total">₱{{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <form action="{{ route('cart.checkout') }}" method="POST" id="checkout-form">
                            @csrf

                            <!-- Hidden selected items (updated by JS) -->
                            <div id="selected-items-container">
                                @foreach($cartItems as $item)
                                    <input type="hidden" name="selected_items[]" value="{{ $item->cartItemID }}" class="selected-item-input" data-id="{{ $item->cartItemID }}">
                                @endforeach
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                                <select name="payMethod" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none">
                                    <option value="Cash on Delivery">Cash on Delivery</option>
                                    <option value="GCash">GCash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>

                            <!-- Charity -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Donate 10% to</label>
                                <select name="charityID" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-forest/20 focus:border-forest outline-none">
                                    @foreach($charities as $charity)
                                        <option value="{{ $charity->charityID }}">{{ $charity->charName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" id="place-order-btn" class="w-full bg-forest text-white py-3 rounded-xl font-semibold text-lg hover:bg-forest-dark transition-colors shadow-lg hover:shadow-xl">
                                Place Order
                            </button>
                        </form>

                        <!-- Charity Note -->
                        <div class="mt-4 bg-pink-50 rounded-lg p-3 flex items-start gap-2">
                            <svg class="w-5 h-5 text-pink-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            <p class="text-xs text-pink-600">10% of your purchase will be donated to support a charitable cause.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function formatPeso(amount) {
    return '₱' + amount.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function recalculate() {
    let total = 0;
    let totalItems = 0;
    const container = document.getElementById('selected-items-container');

    // Clear hidden inputs
    container.innerHTML = '';

    document.querySelectorAll('.cart-item').forEach(row => {
        const price = parseFloat(row.dataset.price);
        const qtyInput = row.querySelector('.qty-input');
        const qty = parseInt(qtyInput.value) || 1;
        const checkbox = row.querySelector('.item-checkbox');
        const subtotal = price * qty;
        const itemId = row.dataset.itemId;

        row.querySelector('.item-subtotal').textContent = formatPeso(subtotal);

        if (checkbox.checked) {
            total += subtotal;
            totalItems += qty;

            // Add hidden input for this selected item
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'selected_items[]';
            hidden.value = itemId;
            container.appendChild(hidden);
        }

        // Dim unchecked items
        row.style.opacity = checkbox.checked ? '1' : '0.5';
    });

    document.getElementById('summary-count').textContent = totalItems;
    document.getElementById('summary-subtotal').textContent = formatPeso(total);
    document.getElementById('summary-donation').textContent = formatPeso(total * 0.10);
    document.getElementById('summary-fee').textContent = formatPeso(total * 0.05);
    document.getElementById('summary-total').textContent = formatPeso(total);

    // Disable button if nothing selected
    const btn = document.getElementById('place-order-btn');
    if (totalItems === 0) {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

// Checkbox listeners
document.querySelectorAll('.item-checkbox').forEach(cb => {
    cb.addEventListener('change', () => {
        recalculate();
        updateSelectAll();
    });
});

// Select All
const selectAll = document.getElementById('select-all');
if (selectAll) {
    selectAll.addEventListener('change', function () {
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        recalculate();
    });
}

function updateSelectAll() {
    const all = document.querySelectorAll('.item-checkbox');
    const checked = document.querySelectorAll('.item-checkbox:checked');
    if (selectAll) {
        selectAll.checked = all.length === checked.length;
        selectAll.indeterminate = checked.length > 0 && checked.length < all.length;
    }
}

// Save quantities via AJAX silently
function saveQuantity(row) {
    const url = row.dataset.updateUrl;
    const token = row.dataset.token;
    const qty = parseInt(row.querySelector('.qty-input').value) || 1;

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ quantity: qty, _method: 'PUT' })
    }).catch(() => {});
}

document.querySelectorAll('.cart-item').forEach(row => {
    const qtyInput = row.querySelector('.qty-input');
    const minusBtn = row.querySelector('.qty-minus');
    const plusBtn = row.querySelector('.qty-plus');

    minusBtn.addEventListener('click', () => {
        let val = parseInt(qtyInput.value) || 1;
        if (val > 1) {
            qtyInput.value = val - 1;
            recalculate();
            saveQuantity(row);
        }
    });

    plusBtn.addEventListener('click', () => {
        let val = parseInt(qtyInput.value) || 1;
        qtyInput.value = val + 1;
        recalculate();
        saveQuantity(row);
    });

    qtyInput.addEventListener('input', () => {
        let val = parseInt(qtyInput.value);
        if (isNaN(val) || val < 1) qtyInput.value = 1;
        recalculate();
        saveQuantity(row);
    });
});
</script>
@endsection
