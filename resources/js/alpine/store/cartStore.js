document.addEventListener('alpine:init', () => {
    Alpine.store('cart', {
        items: [],

        add(product) {
            const item = this.items.find(i => i.id === product.id)
            if (item) {
                item.qty++
            } else {
                this.items.push({
                    id: product.id,
                    name: product.name,
                    price: Number(product.price),
                    qty: 1
                })
            }
        },

        increment(id) {
            const item = this.items.find(i => i.id === id)
            if (item) item.qty++
        },

        decrement(id) {
            const index = this.items.findIndex(i => i.id === id)
            if (index === -1) return

            if (this.items[index].qty > 1) {
                this.items[index].qty--
            } else {
                this.items.splice(index, 1)
            }
        },
        removeItem(id) {
            this.items = this.items.filter(i => i.id !== id);
        },

        get total() {
            return this.items.reduce((t, i) => t + i.price * i.qty, 0)
        },

        get count() {
            return this.items.reduce((t, i) => t + i.qty, 0)
        },

        clear() {
            this.items = []
        }
    })
})
