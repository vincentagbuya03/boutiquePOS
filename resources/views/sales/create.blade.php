@extends('layouts.app')

@section('title', 'POS Terminal')

@section('styles')
<style>
    :root {
        --pos-accent: #802030;
        --pos-bg: #fdfdfd;
        --pos-glass: rgba(255, 255, 255, 0.7);
        --pos-border: rgba(0, 0, 0, 0.05);
        --receipt-width: 330px;
    }

    body {
        background-color: #f8f9fa;
        overflow: hidden;
    }

    .pos-shell {
        display: flex;
        height: calc(100vh - 88px);
        gap: 1rem;
        padding: 0.85rem 1.5rem;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Product Explorer */
    .pos-main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        min-width: 0;
    }

    .pos-top-control {
        background: var(--pos-glass);
        backdrop-filter: blur(10px);
        padding: 0.85rem 1rem;
        border-radius: 20px;
        border: 1px solid var(--pos-border);
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .search-wrap {
        position: relative;
    }

    .search-wrap i {
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
    }

    .search-pos-input {
        width: 100%;
        padding: 0.8rem 1.25rem 0.8rem 3.25rem;
        background: #f1f3f5;
        border: 2px solid transparent;
        border-radius: 14px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s;
        outline: none;
    }

    .search-pos-input:focus {
        background: white;
        border-color: var(--pos-accent);
        box-shadow: 0 10px 25px rgba(128, 32, 48, 0.05);
    }

    .filter-ribbon {
        display: flex;
        gap: 0.75rem;
        overflow-x: auto;
        padding-bottom: 0.25rem;
        scrollbar-width: none;
    }
    .filter-ribbon::-webkit-scrollbar { display: none; }

    .filter-pill {
        white-space: nowrap;
        padding: 0.55rem 1.1rem;
        background: white;
        border: 1px solid #eee;
        border-radius: 100px;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #adb5bd;
        cursor: pointer;
        transition: all 0.3s;
    }

    .filter-pill.active {
        background: var(--pos-accent);
        color: white;
        border-color: var(--pos-accent);
        box-shadow: 0 8px 20px rgba(128, 32, 48, 0.15);
    }

    .product-scroller {
        flex: 1;
        overflow-y: auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(145px, 175px));
        grid-auto-rows: min-content;
        align-content: start;
        align-items: start;
        gap: 1rem;
        padding-bottom: 1rem;
        scrollbar-width: thin;
        scrollbar-color: #eee transparent;
    }

    .product-scroller::-webkit-scrollbar { width: 6px; }
    .product-scroller::-webkit-scrollbar-thumb { background: #eee; border-radius: 10px; }

    .pos-product-card {
        background: white;
        border-radius: 16px;
        padding: 0.65rem;
        border: 1px solid var(--pos-border);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
        min-height: 0;
    }

    .pos-product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 28px rgba(0,0,0,0.04);
        border-color: var(--pos-accent);
    }

    .pos-img-box {
        width: 100%;
        aspect-ratio: 4/3;
        background: #f9f9f9;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .pos-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pos-img-box i {
        font-size: 1.8rem;
        color: #eee;
    }

    .pos-card-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .pos-card-name {
        font-weight: 800;
        font-size: 0.78rem;
        color: #1a1a1a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pos-card-price {
        font-weight: 900;
        color: var(--pos-accent);
        font-size: 0.95rem;
    }

    /* Receipt Side */
    .pos-receipt-panel {
        width: var(--receipt-width);
        background: white;
        border-radius: 24px;
        border: 1px solid var(--pos-border);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0,0,0,0.04);
    }

    .receipt-header {
        padding: 1.1rem 1.35rem;
        background: #fdf2f4;
        position: relative;
        overflow: hidden;
    }

    .receipt-header::after {
        content: "";
        position: absolute;
        bottom: -12px;
        left: 0;
        right: 0;
        height: 24px;
        background: white;
        border-radius: 100% 100% 0 0;
    }

    .receipt-header span {
        font-size: 0.58rem;
        font-weight: 800;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        display: block;
        margin-bottom: 0.25rem;
    }

    .receipt-header h2 {
        font-family: 'Bodoni Moda', serif;
        font-size: 1.25rem;
        font-weight: 900;
        color: var(--pos-accent);
        margin: 0;
    }

    .cart-container {
        flex: 1;
        overflow-y: auto;
        padding: 0.85rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .empty-cart-state {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #e9ecef;
        gap: 1rem;
    }

    .empty-cart-state i { font-size: 2.4rem; }
    .empty-cart-state p { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #adb5bd; }

    .cart-item-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #fcfcfc;
        border-radius: 14px;
        border: 1px solid #f1f1f1;
    }

    .cart-item-thumb {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: white;
        overflow: hidden;
        border: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-item-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .cart-item-main { flex: 1; min-width: 0; }
    .cart-item-title { font-size: 0.78rem; font-weight: 800; color: #1a1a1a; margin-bottom: 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    
    .qty-widget {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        background: white;
        padding: 0.25rem 0.5rem;
        border-radius: 100px;
        border: 1px solid #eee;
        width: fit-content;
    }

    .qty-btn {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: none;
        background: #f8f9fa;
        font-size: 0.8rem;
        font-weight: 900;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .qty-btn:hover { background: var(--pos-accent); color: white; }

    .summary-section {
        padding: 1.35rem;
        background: white;
        border-top: 1px solid #f8f9fa;
    }

    .total-line {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px dashed #f1f1f1;
    }

    .total-label { font-size: 0.78rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; }
    .total-value { font-size: 1.8rem; font-weight: 900; color: #1a1a1a; letter-spacing: -0.02em; }

    .pos-btn-primary {
        background: var(--pos-accent);
        color: white;
        border: none;
        width: 100%;
        padding: 1rem;
        border-radius: 16px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-top: 1rem;
        transition: all 0.4s;
        box-shadow: 0 10px 30px rgba(128, 32, 48, 0.25);
    }

    .pos-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 40px rgba(128, 32, 48, 0.35);
    }

    /* Modal */
    .pos-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(15px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }

    .pos-modal {
        background: white;
        border-radius: 32px;
        width: 100%;
        max-width: 520px;
        padding: 2.5rem;
        position: relative;
        animation: modalIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 40px 100px rgba(0,0,0,0.2);
    }

    .modal-close {
        position: absolute;
        right: 1.5rem;
        top: 1.5rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid #f1f1f1;
        background: white;
        color: #adb5bd;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: #fdf2f4;
        color: var(--pos-accent);
        border-color: #fdf2f4;
    }

    .payment-highlight-box {
        background: #fdf2f4;
        border-radius: 24px;
        padding: 1.75rem;
        border: 1px solid rgba(128, 32, 48, 0.05);
        margin-bottom: 2rem;
    }

    .amount-input-wrap {
        position: relative;
        background: white;
        border: 2px solid #eee;
        border-radius: 18px;
        padding: 1rem 1.5rem;
        transition: all 0.3s;
        display: flex;
        align-items: center;
    }

    .amount-input-wrap:focus-within {
        border-color: var(--pos-accent);
        box-shadow: 0 10px 25px rgba(128, 32, 48, 0.08);
    }

    .amount-input-wrap input {
        border: none;
        background: transparent;
        font-size: 1.75rem;
        font-weight: 900;
        color: #1a1a1a;
        width: 100%;
        outline: none;
        text-align: right;
    }

    .amount-input-wrap .currency-prefix {
        font-size: 1.25rem;
        font-weight: 800;
        color: #adb5bd;
    }

    .discount-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        margin-bottom: 2rem;
    }

    .discount-option {
        cursor: pointer;
    }

    .discount-option input { display: none; }

    .discount-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.35rem;
        padding: 0.75rem;
        border-radius: 16px;
        border: 2px solid #f1f1f1;
        transition: all 0.3s;
        text-align: center;
    }

    .discount-box i { font-size: 0.9rem; color: #adb5bd; }
    .discount-box span { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: #adb5bd; }

    .discount-option input:checked + .discount-box {
        border-color: var(--pos-accent);
        background: #fdf2f4;
    }

    .discount-option input:checked + .discount-box i,
    .discount-option input:checked + .discount-box span {
        color: var(--pos-accent);
    }

    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.9) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="pos-shell">
    <!-- LEFT: PRODUCT EXPLORER -->
    <div class="pos-main-content">
        <div class="pos-top-control">
            <div class="search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" id="posSearch" class="search-pos-input" placeholder="Search the collection...">
            </div>
            <div class="filter-ribbon">
                <div class="filter-pill active" data-category="all">All Pieces</div>
                @foreach($products->groupBy('category.name')->keys() as $category)
                    <div class="filter-pill" data-category="{{ $category }}">{{ $category }}</div>
                @endforeach
            </div>
        </div>

        <div class="product-scroller" id="productGrid">
            @foreach($products as $product)
                @if($product->firstAvailableBatch)
                <div class="pos-product-card" 
                     data-id="{{ $product->id }}" 
                     data-name="{{ $product->name }}" 
                     data-category="{{ $product->category->name }}"
                     data-size="{{ $product->size }}"
                     onclick="addToCart({{ json_encode(['id' => $product->id, 'name' => $product->name, 'size' => $product->size, 'price' => $product->firstAvailableBatch->selling_price, 'image' => $product->image_path ? asset('storage/' . $product->image_path) : null]) }})">
                    <div class="pos-img-box">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                        @else
                            <i class="fas fa-image"></i>
                        @endif
                    </div>
                    <div class="pos-card-info">
                        <span class="pos-card-name">{{ $product->name }} {{ $product->size ? '('.$product->size.')' : '' }}</span>
                        <span class="pos-card-price">₱{{ number_format($product->firstAvailableBatch->selling_price, 2) }}</span>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- RIGHT: RECEIPT PANEL -->
    <div class="pos-receipt-panel">
        <div class="receipt-header">
            <span>Official Register</span>
            <h2>Current Order</h2>
            <div id="orderNumber" style="font-size: 0.7rem; font-weight: 800; color: #adb5bd; margin-top: 0.5rem; letter-spacing: 0.1em;">#VFS-{{ date('ymd') }}-{{ rand(1000,9999) }}</div>
        </div>

        <div class="cart-container" id="cartItemsList">
            <div class="empty-cart-state">
                <i class="fas fa-shopping-bag"></i>
                <p>Bag is empty</p>
            </div>
        </div>

        <div class="summary-section">
            <div class="d-flex justify-content-between mb-2">
                <span style="font-weight: 700; color: #adb5bd; font-size: 0.72rem; text-transform: uppercase;">Subtotal</span>
                <span id="subtotalDisplay" style="font-weight: 800; color: #1a1a1a;">₱0.00</span>
            </div>
            <div class="d-flex justify-content-between">
                <span style="font-weight: 700; color: #adb5bd; font-size: 0.72rem; text-transform: uppercase;">Items</span>
                <span id="itemsCountDisplay" style="font-weight: 800; color: #1a1a1a;">0 Units</span>
            </div>

            <div class="total-line">
                <span class="total-label">Total</span>
                <span class="total-value" id="totalDisplay">₱0.00</span>
            </div>

            <div style="background: #fdf2f4; padding: 0.65rem; border-radius: 14px; margin-top: 1rem; border: 1px solid rgba(128, 32, 48, 0.08); display: flex; align-items: center; justify-content: center; gap: 0.45rem;">
                <i class="fas fa-location-dot" style="color: var(--pos-accent); font-size: 0.8rem;"></i>
                <span style="font-weight: 800; font-size: 0.6rem; color: var(--pos-accent); text-transform: uppercase; letter-spacing: 0.05em;">San Carlos Branch</span>
            </div>

            <button class="pos-btn-primary" onclick="openCheckout()">
                Proceed to Checkout
            </button>
        </div>
    </div>
</div>

<!-- CHECKOUT MODAL -->
<div id="checkoutModal" class="pos-modal-overlay">
    <div class="pos-modal">
        <button onclick="closeCheckout()" class="modal-close">&times;</button>
        
        <h3 style="font-family: 'Bodoni Moda', serif; font-size: 1.75rem; font-weight: 900; color: var(--pos-accent); margin-bottom: 2rem;">Payment Details</h3>

        <form id="posSaleForm" action="{{ route('sales.store') }}" method="POST">
            @csrf
            <input type="hidden" id="saleItems" name="items">
            
            <div class="mb-4">
                <label style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: #adb5bd; margin-bottom: 0.5rem; display: block; letter-spacing: 0.05em;">Payment Method</label>
                <select name="payment_method" id="paymentMethod" class="form-select" style="border-radius: 14px; padding: 0.85rem; font-weight: 700; border: 2px solid #f1f1f1; font-size: 0.9rem;" required onchange="togglePaymentFields()">
                    <option value="cash">Cash Settlement</option>
                    <option value="gcash">GCash Transfer</option>
                </select>
            </div>

            <div class="payment-highlight-box">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span style="font-weight: 800; color: var(--pos-accent); text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em;">Amount Due</span>
                    <strong id="checkoutTotal" style="font-size: 1.75rem; color: #1a1a1a; letter-spacing: -0.02em;">₱0.00</strong>
                </div>

                <div id="cashFields">
                    <label style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: #adb5bd; margin-bottom: 0.5rem; display: block; letter-spacing: 0.05em;">Amount Received</label>
                    <div class="amount-input-wrap">
                        <span class="currency-prefix">₱</span>
                        <input type="number" id="amountReceived" step="0.01" placeholder="0.00">
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top" style="border-color: rgba(128, 32, 48, 0.1) !important;">
                        <span style="font-weight: 800; color: #adb5bd; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.05em;">Change Due</span>
                        <strong id="changeDue" style="font-size: 1.35rem; color: #198754;">₱0.00</strong>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: #adb5bd; margin-bottom: 0.75rem; display: block; letter-spacing: 0.05em;">Special Discounts</span>
                <div class="discount-options">
                    <label class="discount-option">
                        <input type="radio" name="discount_type" value="none" checked onchange="calcTotal()">
                        <div class="discount-box">
                            <i class="fas fa-percentage"></i>
                            <span>None</span>
                        </div>
                    </label>
                    <label class="discount-option">
                        <input type="radio" name="discount_type" value="pwd" onchange="calcTotal()">
                        <div class="discount-box">
                            <i class="fas fa-wheelchair"></i>
                            <span>PWD</span>
                        </div>
                    </label>
                    <label class="discount-option">
                        <input type="radio" name="discount_type" value="senior_citizen" onchange="calcTotal()">
                        <div class="discount-box">
                            <i class="fas fa-user-shield"></i>
                            <span>Senior</span>
                        </div>
                    </label>
                </div>
            </div>

            <button type="button" onclick="finalizeSale()" class="pos-btn-primary" style="margin-top: 0; padding: 1.1rem; font-size: 0.9rem;">
                Confirm Sale
            </button>
        </form>
    </div>
</div>

<script>
let cart = [];

function addToCart(product) {
    const item = cart.find(i => i.id === product.id);
    if (item) {
        item.qty++;
    } else {
        cart.push({ ...product, qty: 1 });
    }
    renderCart();
}

function updateQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (item) {
        item.qty += delta;
        if (item.qty <= 0) cart = cart.filter(i => i.id !== id);
        renderCart();
    }
}

function renderCart() {
    const list = document.getElementById('cartItemsList');
    const subtotalEl = document.getElementById('subtotalDisplay');
    const countEl = document.getElementById('itemsCountDisplay');
    const totalEl = document.getElementById('totalDisplay');

    if (cart.length === 0) {
        list.innerHTML = `<div class="empty-cart-state"><i class="fas fa-shopping-bag"></i><p>Bag is empty</p></div>`;
        subtotalEl.textContent = '₱0.00';
        countEl.textContent = '0 Units';
        totalEl.textContent = '₱0.00';
        return;
    }

    let subtotal = 0;
    let count = 0;

    list.innerHTML = cart.map(item => {
        subtotal += item.price * item.qty;
        count += item.qty;
        return `
            <div class="cart-item-row">
                <div class="cart-item-thumb">
                    ${item.image ? `<img src="${item.image}">` : `<i class="fas fa-image" style="color:#eee"></i>`}
                </div>
                <div class="cart-item-main">
                    <div class="cart-item-title">${item.name} ${item.size ? '('+item.size+')' : ''}</div>
                    <div class="qty-widget">
                        <button class="qty-btn" onclick="updateQty(${item.id}, -1)">&minus;</button>
                        <span style="font-size: 0.8rem; font-weight: 800; min-width: 15px; text-align: center;">${item.qty}</span>
                        <button class="qty-btn" onclick="updateQty(${item.id}, 1)">&plus;</button>
                    </div>
                </div>
                <div style="font-weight: 900; color: var(--pos-accent); font-size: 1rem;">
                    ₱${(item.price * item.qty).toLocaleString()}
                </div>
            </div>
        `;
    }).join('');

    subtotalEl.textContent = '₱' + subtotal.toLocaleString(undefined, { minimumFractionDigits: 2 });
    countEl.textContent = count + ' Units';
    totalEl.textContent = '₱' + subtotal.toLocaleString(undefined, { minimumFractionDigits: 2 });
    
    window.subtotal = subtotal;
}

function calcTotal() {
    const discountType = document.querySelector('input[name="discount_type"]:checked').value;
    let discount = 0;
    if (discountType === 'pwd') discount = window.subtotal * 0.12;
    if (discountType === 'senior_citizen') discount = window.subtotal * 0.20;
    
    const finalTotal = window.subtotal - discount;
    document.getElementById('checkoutTotal').textContent = '₱' + finalTotal.toLocaleString(undefined, { minimumFractionDigits: 2 });
    window.finalTotal = finalTotal;
    window.discountAmount = discount;
    
    calcChange();
}

function calcChange() {
    const received = parseFloat(document.getElementById('amountReceived').value) || 0;
    const change = Math.max(0, received - window.finalTotal);
    document.getElementById('changeDue').textContent = '₱' + change.toLocaleString(undefined, { minimumFractionDigits: 2 });
}

function openCheckout() {
    if (cart.length === 0) return;
    document.getElementById('checkoutModal').style.display = 'flex';
    calcTotal();
    document.getElementById('amountReceived').focus();
}

function closeCheckout() {
    document.getElementById('checkoutModal').style.display = 'none';
}

function togglePaymentFields() {
    const method = document.getElementById('paymentMethod').value;
    const cashFields = document.getElementById('cashFields');
    if (method === 'gcash') {
        cashFields.style.display = 'none';
        document.getElementById('amountReceived').value = window.finalTotal;
    } else {
        cashFields.style.display = 'block';
        document.getElementById('amountReceived').value = '';
    }
}

function finalizeSale() {
    const items = cart.map(i => ({ product_id: i.id, quantity: i.qty, unit_price: i.price }));
    document.getElementById('saleItems').value = JSON.stringify(items);
    
    // Add discount amount to form
    const form = document.getElementById('posSaleForm');
    const discountInput = document.createElement('input');
    discountInput.type = 'hidden';
    discountInput.name = 'discount_amount';
    discountInput.value = window.discountAmount;
    form.appendChild(discountInput);
    
    const receivedInput = document.createElement('input');
    receivedInput.type = 'hidden';
    receivedInput.name = 'cash_received';
    receivedInput.value = document.getElementById('amountReceived').value || window.finalTotal;
    form.appendChild(receivedInput);
    
    form.submit();
}

document.getElementById('amountReceived').addEventListener('input', calcChange);

// Filter logic
document.getElementById('posSearch').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const cat = document.querySelector('.filter-pill.active').dataset.category;
    filterProducts(search, cat);
});

document.querySelectorAll('.filter-pill').forEach(pill => {
    pill.addEventListener('click', function() {
        document.querySelector('.filter-pill.active').classList.remove('active');
        this.classList.add('active');
        const search = document.getElementById('posSearch').value.toLowerCase();
        filterProducts(search, this.dataset.category);
    });
});

function filterProducts(search, category) {
    document.querySelectorAll('.pos-product-card').forEach(card => {
        const name = (card.dataset.name + (card.dataset.size || '')).toLowerCase();
        const matchesSearch = name.includes(search);
        const matchesCat = category === 'all' || card.dataset.category === category;
        card.style.display = (matchesSearch && matchesCat) ? 'flex' : 'none';
    });
}
</script>
@endsection
