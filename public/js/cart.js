/**
 * Cart Management System
 * Gerencia o carrinho de compras usando LocalStorage
 */

class CartManager {
    constructor() {
        this.storageKey = 'app_cart';
        this.items = [];
        this.load();
    }

    load() {
        const stored = localStorage.getItem(this.storageKey);
        this.items = stored ? JSON.parse(stored) : [];
        this.dispatchUpdate();
    }

    save() {
        localStorage.setItem(this.storageKey, JSON.stringify(this.items));
        this.dispatchUpdate();
    }

    dispatchUpdate() {
        window.dispatchEvent(new CustomEvent('cart-updated', { detail: this.items }));
    }

    addItem(product) {
        const price = this._parsePrice(product.price);
        const existingItem = this.items.find(item => item.id == product.id);

        if (existingItem) {
            existingItem.quantity += (product.quantity || 1);
            if (product.observation) existingItem.observation = product.observation;
        } else {
            this.items.push({
                id: product.id,
                name: product.name,
                price: price,
                quantity: product.quantity || 1,
                image: product.image || null,
                observation: product.observation || ''
            });
        }

        this.save();
        return this.items;
    }

    removeItem(productId) {
        this.items = this.items.filter(item => item.id != productId);
        this.save();
        return this.items;
    }

    updateQuantity(productId, quantity) {
        const item = this.items.find(item => item.id == productId);
        if (item) {
            const newQty = parseInt(quantity);
            if (newQty > 0) {
                item.quantity = newQty;
            } else {
                this.removeItem(productId);
            }
            this.save();
        }
        return this.items;
    }

    clear() {
        this.items = [];
        this.save();
    }

    getTotal() {
        return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
    }

    getCount() {
        return this.items.reduce((count, item) => count + item.quantity, 0);
    }

    getItems() {
        return this.items;
    }
    
    _parsePrice(price) {
        if (typeof price === 'number') return price;
        if (typeof price === 'string') {
            // Se tiver vírgula, assume formato BRL/Europeu (ex: 1.000,00)
            if (price.includes(',')) {
                return parseFloat(price.replace(/[^\d,]/g, '').replace(',', '.'));
            }
            return parseFloat(price);
        }
        return 0;
    }

    formatMoney(value) {
        return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }
}

// Inicializar o gerenciador de carrinho globalmente
window.cartManager = new CartManager();