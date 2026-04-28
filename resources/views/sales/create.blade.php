@extends('layouts.app')

@section('title', 'Point of Sale')

@section('styles')
<style>
    :root {
        --color-editorial: #802030; /* Deep Maroon */
        --color-bg-workspace: #f8f9fa;
        --color-border-arch: #f1f1f1;
        --sidebar-checkout: 420px;
    }

    body {
        background-color: var(--color-bg-workspace);
        font-family: 'Inter', sans-serif;
        color: #1a1a1a;
        overflow: hidden;
    }

    .pos-layout-shell {
        display: flex;
        height: calc(100vh - 80px); /* Adjust for top navbar */
        gap: 1.5rem;
        padding: 1.5rem 2rem;
    }

    /* Product Explorer Side */
    .pos-explorer-side {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        min-width: 0;
    }

    .pos-action-tray {
        background: white;
        padding: 1.25rem;
        border-radius: 24px;
        border: 1px solid var(--color-border-arch);
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }

    .arch-search-field {
        position: relative;
        display: flex;
        align-items: center;
    }

    .arch-search-field i {
        position: absolute;
        left: 1.25rem;
        color: #adb5bd;
    }

    .arch-search-input {
        width: 100%;
        padding: 0.85rem 1.25rem 0.85rem 3rem;
        background: #f8f9fa;
        border: 1px solid #eee;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.2s;
        outline: none;
    }

    .arch-search-input:focus {
        background: white;
        border-color: var(--color-editorial);
        box-shadow: 0 10px 20px rgba(128, 32, 48, 0.05);
    }

    .category-ribbon {
        display: flex;
        gap: 0.75rem;
        overflow-x: auto;
        padding-bottom: 0.25rem;
        scrollbar-width: none;
    }
    .category-ribbon::-webkit-scrollbar { display: none; }

    .arch-category-btn {
        white-space: nowrap;
        padding: 0.65rem 1.5rem;
        background: white;
        border: 1px solid #eee;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #adb5bd;
        cursor: pointer;
        transition: all 0.2s;
    }

    .arch-category-btn.active {
        background: var(--color-editorial);
        color: white;
        border-color: var(--color-editorial);
    }

    .arch-product-grid {
        flex: 1;
        overflow-y: auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 220px));
        gap: 1.25rem;
        padding-right: 0.5rem;
        align-content: start;
    }

    .arch-product-card {
        background: white;
        border-radius: 20px;
        padding: 1rem;
        border: 1px solid var(--color-border-arch);
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .arch-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.04);
        border-color: var(--color-editorial);
    }

    .arch-img-frame {
        width: 100%;
        aspect-ratio: 1/1;
        background: #fdfdfd;
        border-radius: 18px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .arch-img-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s;
    }

    .arch-product-card:hover .arch-img-frame img { transform: scale(1.1); }

    .arch-product-name { font-size: 0.9rem; font-weight: 700; color: #1a1a1a; margin-top: 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .arch-product-price { font-size: 1rem; font-weight: 800; color: var(--color-editorial); }

    /* Checkout Side */
    .pos-checkout-side {
        width: var(--sidebar-checkout);
        background: white;
        border-radius: 30px;
        border: 1px solid var(--color-border-arch);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.03);
    }

    .checkout-upper {
        padding: 2rem;
        background: #fdf2f4;
        border-bottom: 1px solid rgba(128, 32, 48, 0.05);
    }

    .checkout-upper h2 { font-family: 'Bodoni Moda', serif; font-size: 1.75rem; font-weight: 800; color: var(--color-editorial); }
    .checkout-upper span { font-size: 0.7rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; }

    .cart-shelf {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .arch-cart-item {
        display: flex;
        gap: 1.25rem;
        padding: 1.25rem;
        background: #fcfcfc;
        border: 1px solid #f1f1f1;
        border-radius: 20px;
        align-items: center;
    }

    .item-qty-control {
        display: flex;
        align-items: center;
        background: white;
        border: 1px solid #eee;
        border-radius: 100px;
        padding: 0.25rem;
        gap: 0.75rem;
    }

    .qty-step-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: none;
        background: #f8f9fa;
        color: #1a1a1a;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .qty-step-btn:hover { background: var(--color-editorial); color: white; }

    .checkout-summary-box {
        padding: 2rem;
        background: #fdfdfd;
        border-top: 1px solid var(--color-border-arch);
    }

    .summary-line { display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.85rem; font-weight: 600; color: #999; }
    .summary-line.grand-total { border-top: 1px solid #eee; margin-top: 1.25rem; padding-top: 1.25rem; font-size: 2rem; font-weight: 800; color: #1a1a1a; }

    .checkout-primary-btn {
        background: var(--color-editorial);
        color: white;
        border: none;
        width: 100%;
        padding: 1.25rem;
        border-radius: 18px;
        font-size: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 10px 25px rgba(128, 32, 48, 0.2);
    }

    .checkout-primary-btn:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(128, 32, 48, 0.3); }

    /* Modal Styling */
    .arch-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(8px);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .arch-modal-box {
        background: white;
        border-radius: 30px;
        padding: 3rem;
        width: 100%;
        max-width: 550px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.2);
    }

    .arch-field-label { display: block; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #adb5bd; margin-bottom: 0.75rem; }
    .arch-select-custom {
        width: 100%;
        padding: 1rem;
        background: #f8f9fa;
        border: 1px solid #eee;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.9rem;
        outline: none;
    }
</style>
@endsection

@section('content')
<div class="pos-layout-shell">
    <!-- PRODUCT EXPLORER -->
    <div class="pos-explorer-side">
        <div class="pos-action-tray">
            <div class="arch-search-field">
                <i class="fas fa-search"></i>
                <input type="text" id="posProductSearch" class="arch-search-input" placeholder="Search V'S Fashion...">
            </div>
            <div class="category-ribbon" id="categoryScroll">
                <div class="arch-category-btn active" data-category="all">All V'S Fashion</div>
                @foreach($products->groupBy('category.name')->keys() as $category)
                    <div class="arch-category-btn" data-category="{{ $category }}">{{ $category }}</div>
                @endforeach
            </div>
        </div>

        <div class="arch-product-grid" id="posProductGrid">
            @foreach($products as $product)
                @if($product->firstAvailableBatch)
                    <div class="arch-product-card" 
                         data-id="{{ $product->id }}" 
                         data-name="{{ $product->name }}" 
                         data-size="{{ $product->size }}"
                         data-price="{{ $product->firstAvailableBatch->selling_price }}" 
                         data-category="{{ $product->category->name }}"
                         onclick="posAddToCart({{ json_encode(['id' => $product->id, 'name' => $product->name, 'size' => $product->size, 'price' => $product->firstAvailableBatch->selling_price, 'image' => $product->image_path ? asset('storage/' . $product->image_path) : null]) }})">
                        <div class="arch-img-frame">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                            @else
                                <i class="fas fa-archive" style="font-size: 3rem; color: #f1f1f1;"></i>
                            @endif
                        </div>
                        <div>
                            <div class="arch-product-name">{{ $product->name }} {{ $product->size ? '('.$product->size.')' : '' }}</div>
                            <div class="arch-product-price">₱{{ number_format($product->firstAvailableBatch->selling_price, 2) }}</div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- CHECKOUT PANEL -->
    <div class="pos-checkout-side">
        <div class="checkout-upper">
            <span>V's Sales Receipt</span>
            <h2>Current Order</h2>
            <div id="orderId" style="font-size: 0.8rem; font-weight: 800; color: #999; margin-top: 0.5rem;">#ARC-{{ date('ymd') }}-{{ rand(1000, 9999) }}</div>
        </div>

        <div class="cart-shelf" id="posCartList">
            <div style="height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #adb5bd; gap: 1.5rem; opacity: 0.5;">
                <i class="fas fa-shopping-basket" style="font-size: 5rem;"></i>
                <p style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.15em;">Awaiting Selections</p>
            </div>
        </div>

        <div class="checkout-summary-box">
            <div class="summary-line">
                <span>Items Subtotal</span>
                <span id="posItemsCount">0 Items</span>
            </div>
            <div class="summary-line">
            </div>
            <div class="summary-line grand-total">
                <span>Total</span>
                <span id="posTotalAmount">₱0.00</span>
            </div>
            
            <div style="display: grid; grid-template-columns: 100px 1fr; gap: 1rem; margin: 1.5rem 0;">
                <button onclick="posClearCart()" style="background: #f8f9fa; border: none; border-radius: 14px; font-weight: 800; font-size: 0.7rem; color: #adb5bd; text-transform: uppercase;">
                    Clear
                </button>
                <input type="hidden" id="posBranch" value="San Carlos">
                <div style="background: #fdf2f4; padding: 0.75rem; border-radius: 14px; border: 1px solid rgba(128, 32, 48, 0.1); font-weight: 800; font-size: 0.7rem; color: var(--color-editorial); display: flex; align-items: center; justify-content: center; grid-column: 1 / -1; text-transform: uppercase; letter-spacing: 0.05em;">
                    <i class="fas fa-map-marker-alt" style="margin-right: 0.5rem; font-size: 0.8rem;"></i> Location: San Carlos Branch
                </div>
            </div>

            <button class="checkout-primary-btn" onclick="openPaymentModal()">
                Complete Sales <i class="fas fa-arrow-right" style="margin-left: 0.75rem;"></i>
            </button>
        </div>
    </div>
</div>

<!-- PAYMENT MODAL -->
<div id="paymentModal" class="arch-modal-overlay">
    <div class="arch-modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
            <h2 style="font-family: 'Bodoni Moda', serif; font-size: 2rem; font-weight: 800; color: var(--color-editorial); margin: 0;">Payment Settlement</h2>
            <button onclick="closePaymentModal()" style="background: none; border: none; font-size: 2rem; cursor: pointer; color: #adb5bd;">&times;</button>
        </div>

        <form id="finalSaleForm" action="{{ route('sales.store') }}" method="POST">
            @csrf
            <input type="hidden" id="hiddenItems" name="items">
            <input type="hidden" name="type" value="in_store">
            
            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label class="arch-field-label">Payment Method</label>
                    <select name="payment_method" class="arch-select-custom" required id="finalPaymentMethod">
                        <option value="cash">Cash Settlement</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                        <option value="online_transfer">Electronic Transfer</option>
                    </select>
                </div>
            </div>

            <!-- Discount Options Section -->
            <div style="background: #fff8f8; padding: 1.5rem; border-radius: 20px; border: 2px dashed #ffe0e6; margin-bottom: 2rem;">
                <div style="font-weight: 800; color: #802030; text-transform: uppercase; font-size: 0.75rem; margin-bottom: 1rem; letter-spacing: 0.05em;">Customer Discount</div>
                <div style="display: flex; gap: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; flex: 1;">
                        <input type="radio" name="discount_type" value="none" checked onchange="updateDiscount()">
                        <span style="font-weight: 600; color: #1a1a1a;">No Discount</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; flex: 1;">
                        <input type="radio" name="discount_type" value="pwd" onchange="updateDiscount()">
                        <span style="font-weight: 600; color: #1a1a1a;">PWD (12%)</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; flex: 1;">
                        <input type="radio" name="discount_type" value="senior_citizen" onchange="updateDiscount()">
                        <span style="font-weight: 600; color: #1a1a1a;">Senior Citizen (20%)</span>
                    </label>
                </div>
            </div>

            <div style="background: #f8f9fa; padding: 2rem; border-radius: 24px; margin-bottom: 2.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem;">
                    <span style="font-weight: 800; color: #adb5bd; text-transform: uppercase; font-size: 0.75rem;">Total Settlement</span>
                    <strong id="modalTotalDue" style="font-size: 1.5rem; color: #1a1a1a;">₱0.00</strong>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label class="arch-field-label">Cash Received</label>
                    <input type="number" id="cashReceived" class="arch-select-custom" style="padding: 1.25rem; font-size: 1.5rem; font-weight: 800; color: var(--color-editorial);" placeholder="0.00">
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 1.25rem;">
                    <span style="font-weight: 800; color: #adb5bd; text-transform: uppercase; font-size: 0.75rem; align-self: center;">Change Due</span>
                    <strong id="cashChange" style="color: #3c5e5e;">₱0.00</strong>
                </div>
            </div>

            <input type="hidden" name="branch" id="finalBranch">

            <button type="button" onclick="submitPOSSale()" class="checkout-primary-btn">
                Confirm Sale
            </button>
        </form>
    </div>
</div>

<script>
let posCart = [];

function posAddToCart(product) {
    const existing = posCart.find(item => item.id === product.id);
    if (existing) {
        existing.qty++;
    } else {
        posCart.push({ ...product, qty: 1 });
    }
    renderPosCart();
    
    // Feedback
    const card = document.querySelector(`.arch-product-card[data-id="${product.id}"]`);
    card.classList.add('pulse');
    setTimeout(() => card.classList.remove('pulse'), 200);
}

function posUpdateQty(id, delta) {
    const item = posCart.find(i => i.id === id);
    if (item) {
        item.qty += delta;
        if (item.qty <= 0) {
            posCart = posCart.filter(i => i.id !== id);
        }
        renderPosCart();
    }
}

function renderPosCart() {
    const list = document.getElementById('posCartList');
    const itemsCountEl = document.getElementById('posItemsCount');
    const totalAmountEl = document.getElementById('posTotalAmount');
    
    if (posCart.length === 0) {
        list.innerHTML = `
            <div style="height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #adb5bd; gap: 1.5rem; opacity: 0.5;">
                <i class="fas fa-shopping-basket" style="font-size: 5rem;"></i>
                <p style="font-weight: 800; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.15em;">Awaiting Selections</p>
            </div>
        `;
        itemsCountEl.textContent = '0 Items';
        totalAmountEl.textContent = '₱0.00';
        return;
    }

    let total = 0;
    let count = 0;

    list.innerHTML = posCart.map(item => {
        const itemTotal = item.price * item.qty;
        total += itemTotal;
        count += item.qty;
        
        return `
            <div class="arch-cart-item">
                <div style="width: 45px; height: 45px; background: white; border-radius: 10px; overflow: hidden; flex-shrink: 0; border: 1px solid #eee;">
                    ${item.image ? `<img src="${item.image}" style="width:100%; height:100%; object-fit:cover;">` : `<div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#eee;"><i class="fas fa-image"></i></div>`}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.4rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${item.name} ${item.size ? '('+item.size+')' : ''}</div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div class="item-qty-control">
                            <button class="qty-step-btn" onclick="posUpdateQty(${item.id}, -1)">&minus;</button>
                            <span style="font-size: 0.75rem; font-weight: 800; min-width: 15px; text-align: center;">${item.qty}</span>
                            <button class="qty-step-btn" onclick="posUpdateQty(${item.id}, 1)">&plus;</button>
                        </div>
                        <span style="font-size: 0.7rem; font-weight: 600; color: #adb5bd;">@ ₱${Number(item.price).toLocaleString()}</span>
                    </div>
                </div>
                <div style="font-weight: 800; color: var(--color-editorial); font-size: 0.9rem;">
                    ₱${Number(itemTotal).toLocaleString()}
                </div>
            </div>
        `;
    }).join('');

    itemsCountEl.textContent = count + ' Items';
    const subtotal = total;
    const discountType = document.querySelector('input[name="discount_type"]:checked')?.value || 'none';
    let discountAmount = 0;
    
    if (discountType === 'pwd') {
        discountAmount = subtotal * 0.12;
    } else if (discountType === 'senior_citizen') {
        discountAmount = subtotal * 0.20;
    }
    
    const finalTotal = subtotal - discountAmount;
    
    totalAmountEl.textContent = '₱' + subtotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('modalTotalDue').textContent = '₱' + finalTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    
    // Store discount info for form submission
    window.discountInfo = { type: discountType, amount: discountAmount, subtotal: subtotal, finalTotal: finalTotal };
    
    // Update change calculation if modal is open
    if (document.getElementById('paymentModal').style.display === 'flex') {
        updateChangeCalculation();
    }
}

function posClearCart() {
    if (posCart.length > 0 && confirm('Clear this V\'S Fashion order?')) {
        posCart = [];
        renderPosCart();
    }
}

function updateDiscount() {
    renderPosCart();
}

// Search
document.getElementById('posProductSearch').addEventListener('input', posFilter);
document.querySelectorAll('.arch-category-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelector('.arch-category-btn.active').classList.remove('active');
        btn.classList.add('active');
        posFilter();
    });
});

function posFilter() {
    const search = document.getElementById('posProductSearch').value.toLowerCase();
    const category = document.querySelector('.arch-category-btn.active').dataset.category;
    
    document.querySelectorAll('.arch-product-card').forEach(card => {
        const name = (card.dataset.name + " " + (card.dataset.size || "")).toLowerCase();
        const cat = card.dataset.category;
        const matchesSearch = name.includes(search);
        const matchesCat = category === 'all' || cat === category;
        
        card.style.display = (matchesSearch && matchesCat) ? 'flex' : 'none';
    });
}

function openPaymentModal() {
    if (posCart.length === 0) return alert('Please add items to cart.');
    const branch = document.getElementById('posBranch').value;
    if (!branch) return alert('Please select a branch location.');

    const subtotal = posCart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const discountType = document.querySelector('input[name="discount_type"]:checked')?.value || 'none';
    let discountAmount = 0;
    
    if (discountType === 'pwd') {
        discountAmount = subtotal * 0.12;
    } else if (discountType === 'senior_citizen') {
        discountAmount = subtotal * 0.20;
    }
    
    const finalTotal = subtotal - discountAmount;
    window.discountInfo = { type: discountType, amount: discountAmount, subtotal: subtotal, finalTotal: finalTotal };
    
    document.getElementById('modalTotalDue').textContent = '₱' + finalTotal.toLocaleString(undefined, { minimumFractionDigits: 2 });
    document.getElementById('paymentModal').style.display = 'flex';
    document.getElementById('cashReceived').value = '';
    document.getElementById('cashChange').textContent = '₱0.00';
    document.getElementById('cashReceived').focus();
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
}

function updateChangeCalculation() {
    const total = window.discountInfo?.finalTotal || posCart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const received = parseFloat(document.getElementById('cashReceived').value) || 0;
    const change = Math.max(0, received - total);
    document.getElementById('cashChange').textContent = '₱' + change.toLocaleString(undefined, { minimumFractionDigits: 2 });
}

document.getElementById('cashReceived').addEventListener('input', updateChangeCalculation);

function submitPOSSale() {
    if (posCart.length === 0) {
        alert('Cart is empty. Please add items.');
        return;
    }

    const items = posCart.map(item => ({
        product_id: item.id,
        quantity: item.qty,
        unit_price: item.price
    }));
    
    document.getElementById('hiddenItems').value = JSON.stringify(items);
    document.getElementById('finalBranch').value = document.getElementById('posBranch').value;
    
    // Get discount information
    const discountType = document.querySelector('input[name="discount_type"]:checked')?.value || 'none';
    const discountAmount = window.discountInfo?.amount || 0;
    
    // Update hidden inputs with current values
    const form = document.getElementById('finalSaleForm');
    
    // Remove old discount inputs if they exist
    const oldDiscountTypeInputs = form.querySelectorAll('input[name="discount_type"][type="hidden"]');
    const oldDiscountAmountInputs = form.querySelectorAll('input[name="discount_amount"][type="hidden"]');
    oldDiscountTypeInputs.forEach(input => input.remove());
    oldDiscountAmountInputs.forEach(input => input.remove());
    
    // Create new hidden inputs for discount data
    const discountTypeInput = document.createElement('input');
    discountTypeInput.type = 'hidden';
    discountTypeInput.name = 'discount_type';
    discountTypeInput.value = discountType;
    form.appendChild(discountTypeInput);
    
    const discountAmountInput = document.createElement('input');
    discountAmountInput.type = 'hidden';
    discountAmountInput.name = 'discount_amount';
    discountAmountInput.value = discountAmount;
    form.appendChild(discountAmountInput);
    
    const cashReceivedInput = document.createElement('input');
    cashReceivedInput.type = 'hidden';
    cashReceivedInput.name = 'cash_received';
    cashReceivedInput.value = parseFloat(document.getElementById('cashReceived').value) || 0;
    form.appendChild(cashReceivedInput);
    
    form.submit();
}

window.onclick = function(event) {
    if (event.target == document.getElementById('paymentModal')) {
        closePaymentModal();
    }
}
</script>
@endsection
