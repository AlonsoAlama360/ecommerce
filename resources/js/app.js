import './bootstrap';

// ── Mobile Menu ──
const hamburgerBtn = document.getElementById('hamburgerBtn');
const mobileMenu = document.getElementById('mobileMenu');
const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
const closeMobileMenu = document.getElementById('closeMobileMenu');
const mobileCategoriesBtn = document.getElementById('mobileCategoriesBtn');
const mobileCategorySubmenu = document.getElementById('mobileCategorySubmenu');
const mobileCategoriesIcon = document.getElementById('mobileCategoriesIcon');

if (hamburgerBtn) {
    hamburgerBtn.addEventListener('click', () => {
        mobileMenu.classList.add('active');
        mobileMenuOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
}

if (closeMobileMenu) {
    closeMobileMenu.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        document.body.style.overflow = '';
    });
}

if (mobileMenuOverlay) {
    mobileMenuOverlay.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        document.body.style.overflow = '';
    });
}

if (mobileCategoriesBtn) {
    mobileCategoriesBtn.addEventListener('click', () => {
        mobileCategorySubmenu.classList.toggle('active');
        mobileCategoriesIcon.style.transform = mobileCategorySubmenu.classList.contains('active')
            ? 'rotate(180deg)' : 'rotate(0deg)';
    });
}

// ── Desktop Mega Menu ──
const categoriesBtn = document.getElementById('categoriesBtn');
const megaMenu = document.getElementById('megaMenu');
const categoryItems = document.querySelectorAll('.category-item');
const productPanel = document.getElementById('productPanel');
const productGrid = document.getElementById('productGrid');
let megaMenuTimeout;

if (categoriesBtn) {
    categoriesBtn.addEventListener('mouseenter', () => {
        clearTimeout(megaMenuTimeout);
        megaMenu.classList.add('active');
    });

    categoriesBtn.parentElement.addEventListener('mouseleave', () => {
        megaMenuTimeout = setTimeout(() => {
            megaMenu.classList.remove('active');
            productPanel.classList.remove('active');
        }, 200);
    });

    megaMenu.addEventListener('mouseenter', () => { clearTimeout(megaMenuTimeout); });
    megaMenu.addEventListener('mouseleave', () => {
        megaMenuTimeout = setTimeout(() => {
            megaMenu.classList.remove('active');
            productPanel.classList.remove('active');
        }, 200);
    });
}

// Cache de productos por categoría
const categoryCache = {};

categoryItems.forEach(item => {
    item.addEventListener('mouseenter', async (e) => {
        const slug = e.currentTarget.dataset.category;

        if (categoryCache[slug]) {
            renderProducts(categoryCache[slug]);
            return;
        }

        try {
            const res = await fetch(`/api/categories/${slug}/products`);
            const data = await res.json();
            categoryCache[slug] = data;
            renderProducts(data);
        } catch (err) {
            productPanel.classList.remove('active');
        }
    });
});

function renderProducts(products) {
    let html = '';
    products.forEach(p => {
        html += `<a href="/producto/${p.slug}" class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded-lg transition">
            <img src="${p.image}" alt="${p.name}" class="w-14 h-14 object-cover rounded-lg" loading="lazy">
            <div class="flex-1 min-w-0"><p class="font-medium text-xs truncate">${p.name}</p><p class="text-gray-900 font-semibold text-sm">S/ ${p.price}</p></div>
        </a>`;
    });
    productGrid.innerHTML = html;
    productPanel.classList.add('active');
}

// ── Toast Notification ──
window.showToast = function(message) {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `
        <div class="toast-icon"><i class="fas fa-check text-sm"></i></div>
        <div>
            <p class="font-medium text-gray-900 text-sm">${message}</p>
        </div>
    `;
    container.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('show'));
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
};

window.showSuccessToast = function(title, message) {
    var container = document.getElementById('toastContainer');
    var toast = document.createElement('div');
    toast.className = 'toast toast-success';
    toast.innerHTML =
        '<div class="toast-icon"><i class="fas fa-check text-sm"></i></div>' +
        '<div class="flex-1">' +
            '<p class="font-bold text-gray-900 text-sm">' + title + '</p>' +
            '<p class="text-gray-500 text-xs mt-0.5">' + message + '</p>' +
        '</div>' +
        '<div class="toast-progress"></div>';
    var closeBtn = document.createElement('button');
    closeBtn.className = 'text-gray-300 hover:text-gray-500 transition flex-shrink-0';
    closeBtn.innerHTML = '<i class="fas fa-times text-xs"></i>';
    closeBtn.addEventListener('click', function() {
        toast.classList.remove('show');
        setTimeout(function() { toast.remove(); }, 500);
    });
    toast.insertBefore(closeBtn, toast.querySelector('.toast-progress'));
    container.appendChild(toast);
    requestAnimationFrame(function() { toast.classList.add('show'); });
    setTimeout(function() {
        toast.classList.remove('show');
        setTimeout(function() { toast.remove(); }, 500);
    }, 5000);
};

window.showErrorToast = function(title, message) {
    var container = document.getElementById('toastContainer');
    var toast = document.createElement('div');
    toast.className = 'toast toast-error';
    toast.innerHTML =
        '<div class="toast-icon"><i class="fas fa-exclamation text-sm"></i></div>' +
        '<div class="flex-1">' +
            '<p class="font-bold text-gray-900 text-sm">' + title + '</p>' +
            (message ? '<p class="text-gray-500 text-xs mt-0.5">' + message + '</p>' : '') +
        '</div>' +
        '<div class="toast-progress"></div>';
    var closeBtn = document.createElement('button');
    closeBtn.className = 'text-gray-300 hover:text-gray-500 transition flex-shrink-0';
    closeBtn.innerHTML = '<i class="fas fa-times text-xs"></i>';
    closeBtn.addEventListener('click', function() {
        toast.classList.remove('show');
        setTimeout(function() { toast.remove(); }, 500);
    });
    toast.insertBefore(closeBtn, toast.querySelector('.toast-progress'));
    container.appendChild(toast);
    requestAnimationFrame(function() { toast.classList.add('show'); });
    setTimeout(function() {
        toast.classList.remove('show');
        setTimeout(function() { toast.remove(); }, 500);
    }, 6000);
};

// ── Cart Sidebar ──
const cartBtn = document.getElementById('cartBtn');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function getCartEl(id) { return document.getElementById(id); }

window.openCartSidebar = function() {
    loadCartSidebar();
    getCartEl('cartSidebar').classList.add('active');
    getCartEl('cartOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
};

window.closeCartSidebar = function() {
    getCartEl('cartSidebar').classList.remove('active');
    getCartEl('cartOverlay').classList.remove('active');
    document.body.style.overflow = '';
};

function loadCartSidebar() {
    fetch('/carrito/items', {
        method: 'GET', credentials: 'same-origin',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.items || data.items.length === 0) {
            getCartEl('cartSidebarItems').innerHTML =
                '<div class="p-6"><div class="text-center py-12">' +
                '<div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">' +
                '<i class="fas fa-shopping-bag text-3xl text-gray-300"></i></div>' +
                '<p class="text-gray-500 font-medium">Tu carrito está vacío</p>' +
                '<p class="text-sm text-gray-400 mt-1">Agrega productos para comenzar</p></div></div>';
            getCartEl('cartSidebarFooter').style.display = 'none';
            getCartEl('cartSidebarCount').textContent = '0 productos';
            return;
        }

        var html = '<div class="divide-y divide-gray-100">';
        data.items.forEach(function(item) {
            var imgSrc = item.image || '/images/placeholder.png';
            var price = Number(item.price).toFixed(2);
            var lineTotal = Number(item.line_total).toFixed(2);
            html += '<div class="p-4 hover:bg-gray-50/50 transition-colors">' +
                '<div class="flex gap-4">' +
                '<a href="/producto/' + item.slug + '" class="flex-shrink-0">' +
                '<img src="' + imgSrc + '" alt="' + item.name + '" class="w-20 h-20 object-cover rounded-xl border border-gray-100"></a>' +
                '<div class="flex-1 min-w-0">' +
                '<div class="flex items-start justify-between gap-2">' +
                '<a href="/producto/' + item.slug + '" class="text-sm font-semibold text-gray-900 line-clamp-2 hover:text-[#D4A574] transition leading-snug">' + item.name + '</a>' +
                '<button onclick="removeFromCart(' + item.id + ')" class="w-7 h-7 rounded-lg hover:bg-red-50 flex items-center justify-center text-gray-300 hover:text-red-500 transition flex-shrink-0" aria-label="Eliminar ' + item.name + '">' +
                '<i class="fas fa-trash-alt text-xs"></i></button></div>' +
                '<p class="text-sm text-[#D4A574] font-semibold mt-1">S/ ' + price + '</p>' +
                '<div class="flex items-center justify-between mt-2">' +
                '<div class="flex items-center bg-gray-100 rounded-xl overflow-hidden">' +
                '<button onclick="updateCartQty(' + item.id + ',' + (item.quantity - 1) + ')" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition" aria-label="Disminuir cantidad">' +
                '<i class="fas fa-minus text-[10px]"></i></button>' +
                '<span class="w-8 h-8 flex items-center justify-center text-sm font-bold text-gray-900">' + item.quantity + '</span>' +
                '<button onclick="updateCartQty(' + item.id + ',' + (item.quantity + 1) + ')" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition" aria-label="Aumentar cantidad">' +
                '<i class="fas fa-plus text-[10px]"></i></button></div>' +
                '<span class="text-sm font-bold text-gray-900">S/ ' + lineTotal + '</span>' +
                '</div></div></div></div>';
        });
        html += '</div>';

        getCartEl('cartSidebarItems').innerHTML = html;
        getCartEl('cartSidebarFooter').style.display = 'block';
        getCartEl('cartSidebarCount').textContent = data.count + (data.count === 1 ? ' producto' : ' productos');
        getCartEl('cartSidebarTotal').textContent = 'S/ ' + Number(data.total).toFixed(2);
    })
    .catch(function(err) { console.error('Cart sidebar error:', err); });
}

window.updateCartQty = function(productId, qty) {
    if (qty < 1) { window.removeFromCart(productId); return; }
    fetch('/carrito/actualizar', {
        method: 'PATCH', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ product_id: productId, quantity: qty })
    }).then(r => r.json()).then(data => {
        updateCartBadge(data.cart_count);
        loadCartSidebar();
    });
};

window.removeFromCart = function(productId) {
    fetch('/carrito/eliminar', {
        method: 'DELETE', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ product_id: productId })
    }).then(r => r.json()).then(data => {
        updateCartBadge(data.cart_count);
        loadCartSidebar();
    });
};

function updateCartBadge(count) {
    var badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}

if (cartBtn) {
    cartBtn.addEventListener('click', function(e) {
        e.preventDefault();
        var badge = document.querySelector('.cart-badge');
        var count = badge ? parseInt(badge.textContent) : 0;
        if (count > 0) openCartSidebar();
    });
}

document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'cartOverlay') closeCartSidebar();
});

document.addEventListener('keydown', function(e) {
    var sb = getCartEl('cartSidebar');
    if (e.key === 'Escape' && sb && sb.classList.contains('active')) closeCartSidebar();
});

// ── User Dropdown ──
const userBtn = document.getElementById('userBtn');
const userDropdown = document.getElementById('userDropdown');
if (userBtn && userDropdown) {
    let userTimeout;
    userBtn.addEventListener('mouseenter', () => { clearTimeout(userTimeout); userDropdown.classList.add('active'); });
    userBtn.parentElement.addEventListener('mouseleave', () => { userTimeout = setTimeout(() => userDropdown.classList.remove('active'), 200); });
    userDropdown.addEventListener('mouseenter', () => clearTimeout(userTimeout));
    userDropdown.addEventListener('mouseleave', () => { userTimeout = setTimeout(() => userDropdown.classList.remove('active'), 200); });
}

// ── Search Modal ──
var searchBtn = document.getElementById('searchBtn');
var searchModal = document.getElementById('searchModal');
var closeSearchBtn = document.getElementById('closeSearchBtn');
var searchInput = document.getElementById('searchInput');
var searchDefault = document.getElementById('searchDefault');
var searchDynamic = document.getElementById('searchDynamic');
var searchSpinner = document.getElementById('searchSpinner');
var searchTimer = null;

function openSearch() {
    searchModal.classList.add('active');
    document.body.style.overflow = 'hidden';
    setTimeout(function() { searchInput.focus(); }, 100);
}

function closeSearch() {
    searchModal.classList.remove('active');
    document.body.style.overflow = '';
    searchInput.value = '';
    searchDefault.classList.remove('hidden');
    searchDynamic.classList.add('hidden');
}

if (searchBtn) searchBtn.addEventListener('click', openSearch);
if (closeSearchBtn) closeSearchBtn.addEventListener('click', closeSearch);

if (searchModal) {
    searchModal.addEventListener('click', function(e) {
        if (e.target === searchModal) {
            closeSearch();
        }
    });
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && searchModal && searchModal.classList.contains('active')) {
        if (searchInput.value.trim() !== '') {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        } else {
            closeSearch();
        }
    }
});

// Sugerencias rápidas
document.querySelectorAll('.search-suggestion').forEach(function(btn) {
    btn.addEventListener('click', function() {
        searchInput.value = this.textContent.trim();
        searchInput.dispatchEvent(new Event('input'));
    });
});

// Popular searches slider
(function() {
    var track = document.getElementById('popularSliderTrack');
    var btnL = document.getElementById('popularArrowLeft');
    var btnR = document.getElementById('popularArrowRight');
    if (!track || !btnL || !btnR) return;

    var SCROLL_STEP = 200;

    function refreshArrows() {
        var canLeft = track.scrollLeft > 4;
        var canRight = track.scrollLeft < track.scrollWidth - track.clientWidth - 4;
        btnL.classList.toggle('visible', canLeft);
        btnR.classList.toggle('visible', canRight);
    }

    btnL.addEventListener('click', function() { track.scrollBy({ left: -SCROLL_STEP, behavior: 'smooth' }); });
    btnR.addEventListener('click', function() { track.scrollBy({ left: SCROLL_STEP, behavior: 'smooth' }); });
    track.addEventListener('scroll', refreshArrows, { passive: true });

    // Touch drag support
    var isDown = false, startX, scrollStart;
    track.addEventListener('mousedown', function(e) { isDown = true; startX = e.pageX - track.offsetLeft; scrollStart = track.scrollLeft; track.style.cursor = 'grabbing'; });
    track.addEventListener('mouseleave', function() { isDown = false; track.style.cursor = ''; });
    track.addEventListener('mouseup', function() { isDown = false; track.style.cursor = ''; });
    track.addEventListener('mousemove', function(e) { if (!isDown) return; e.preventDefault(); var x = e.pageX - track.offsetLeft; track.scrollLeft = scrollStart - (x - startX); });

    // Refresh on modal open
    if (searchModal) {
        var observer = new MutationObserver(function() {
            if (searchModal.classList.contains('active')) {
                setTimeout(refreshArrows, 50);
            }
        });
        observer.observe(searchModal, { attributes: true, attributeFilter: ['class'] });
    }

    refreshArrows();
})();

// Búsqueda en tiempo real con debounce
if (searchInput) {
    searchInput.addEventListener('input', function() {
        var query = this.value.trim();
        clearTimeout(searchTimer);

        if (query.length < 2) {
            searchDefault.classList.remove('hidden');
            searchDynamic.classList.add('hidden');
            searchDynamic.innerHTML = '';
            searchSpinner.classList.add('hidden');
            return;
        }

        searchSpinner.classList.remove('hidden');

        searchTimer = setTimeout(function() {
            fetch('/buscar?q=' + encodeURIComponent(query), {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                searchSpinner.classList.add('hidden');
                searchDefault.classList.add('hidden');
                searchDynamic.classList.remove('hidden');

                var html = '';

                // Categorías encontradas
                if (data.categories.length > 0) {
                    html += '<p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Categorías</p>';
                    data.categories.forEach(function(cat) {
                        html += '<a href="' + cat.url + '" class="search-result-item flex items-center gap-3 px-3 py-2.5 rounded-xl">' +
                            '<div class="w-10 h-10 bg-[#E8B4B8]/20 rounded-full flex items-center justify-center flex-shrink-0">' +
                            '<i class="' + (cat.icon || 'fas fa-tag') + ' text-[#D4A574] text-sm"></i></div>' +
                            '<span class="font-medium text-gray-700">' + cat.name + '</span>' +
                            '<i class="fas fa-chevron-right text-gray-300 text-xs ml-auto"></i></a>';
                    });
                    html += '<div class="my-3 border-t border-gray-100"></div>';
                }

                // Productos encontrados
                if (data.products.length > 0) {
                    html += '<p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Productos</p>';
                    data.products.forEach(function(p) {
                        var imgSrc = p.image || '/images/placeholder.png';
                        var price = Number(p.price).toFixed(2);
                        html += '<a href="' + p.url + '" class="search-result-item flex items-center gap-3 px-3 py-2.5 rounded-xl">' +
                            '<img src="' + imgSrc + '" alt="' + p.name + '" class="w-12 h-12 object-cover rounded-lg flex-shrink-0">' +
                            '<div class="flex-1 min-w-0">' +
                            '<h4 class="font-medium text-sm text-gray-900 truncate">' + p.name + '</h4>' +
                            '<p class="text-xs text-gray-400">' + (p.category || '') + '</p></div>' +
                            '<span class="font-bold text-gray-900 flex-shrink-0">S/ ' + price + '</span></a>';
                    });
                }

                // Sin resultados
                if (data.products.length === 0 && data.categories.length === 0) {
                    html = '<div class="text-center py-10">' +
                        '<i class="fas fa-search text-4xl text-gray-200 mb-4"></i>' +
                        '<p class="text-gray-500 font-medium">No encontramos resultados para "<span class="text-gray-700">' + query + '</span>"</p>' +
                        '<p class="text-gray-400 text-sm mt-1">Intenta con otras palabras clave</p></div>';
                }

                // Link ver todos
                if (data.products.length > 0) {
                    html += '<div class="mt-3 pt-3 border-t border-gray-100">' +
                        '<a href="/catalogo?q=' + encodeURIComponent(query) + '" class="flex items-center justify-center gap-2 py-2.5 text-sm font-medium text-[#D4A574] hover:text-[#c4955e] transition">' +
                        'Ver todos los resultados <i class="fas fa-arrow-right text-xs"></i></a></div>';
                }

                searchDynamic.innerHTML = html;
            })
            .catch(function() {
                searchSpinner.classList.add('hidden');
            });
        }, 300);
    });
}

// ── Wishlist Global ──
(function() {
    var wishlistIds = [];
    var isAuthenticated = document.body.dataset.authenticated === '1';
    var loginUrl = document.body.dataset.loginUrl;
    var toggleUrl = document.body.dataset.wishlistToggleUrl;
    var countUrl = document.body.dataset.wishlistCountUrl;

    function markAsWishlisted(btn) {
        var icon = btn.querySelector('i');
        icon.classList.remove('far');
        icon.classList.add('fas');
        btn.classList.add('bg-rose-500', 'text-white', 'border-rose-500');
        btn.classList.remove('bg-white/90', 'border-gray-200');
    }

    function markAsNotWishlisted(btn) {
        var icon = btn.querySelector('i');
        icon.classList.remove('fas');
        icon.classList.add('far');
        btn.classList.remove('bg-rose-500', 'text-white', 'border-rose-500');
        if (btn.classList.contains('backdrop-blur-sm')) {
            btn.classList.add('bg-white/90');
        } else {
            btn.classList.add('border-gray-200');
        }
    }

    function handleWishlistClick(e) {
        e.preventDefault();
        e.stopPropagation();

        var btn = this;

        if (!isAuthenticated) {
            window.location.href = loginUrl;
            return;
        }

        var productId = parseInt(btn.dataset.productId);
        btn.style.pointerEvents = 'none';

        fetch(toggleUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.status === 'added') {
                wishlistIds.push(productId);
                document.querySelectorAll('.wishlist-btn[data-product-id="' + productId + '"]').forEach(markAsWishlisted);
                if (typeof window.showToast === 'function') {
                    window.showToast('Agregado a tu lista de deseos');
                }
            } else {
                wishlistIds = wishlistIds.filter(function(id) { return id !== productId; });
                document.querySelectorAll('.wishlist-btn[data-product-id="' + productId + '"]').forEach(markAsNotWishlisted);
                if (typeof window.showToast === 'function') {
                    window.showToast('Eliminado de tu lista de deseos');
                }
            }
            btn.style.pointerEvents = '';
        })
        .catch(function() {
            btn.style.pointerEvents = '';
        });
    }

    function bindWishlistButtons() {
        document.querySelectorAll('.wishlist-btn').forEach(function(btn) {
            if (btn.dataset.wishlistBound) return;
            btn.dataset.wishlistBound = '1';
            btn.addEventListener('click', handleWishlistClick);
            var productId = parseInt(btn.dataset.productId);
            if (wishlistIds.indexOf(productId) !== -1) {
                markAsWishlisted(btn);
            }
        });
    }

    if (isAuthenticated) {
        fetch(countUrl, {
            headers: { 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            wishlistIds = data.ids || [];
            bindWishlistButtons();
        })
        .catch(function() {
            bindWishlistButtons();
        });
    } else {
        bindWishlistButtons();
    }
})();
