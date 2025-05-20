// public/js/commande.js

console.log('commande.js chargé');

document.addEventListener('DOMContentLoaded', () => {
  console.log('DOM ready, binding cart events');

  const apiBase   = '/api/cart';
  const badgeEl   = document.getElementById('cart-badge');
  const tableBody = document.querySelector('#cart-table tbody');
  const totalEl   = document.getElementById('cart-total');

  async function refreshCart() {
    console.log('refreshCart() called');
    try {
      const res  = await fetch(apiBase, { credentials: 'same-origin' });
      console.log('API /api/cart response', res.status);
      const data = await res.json();
      console.log('Cart data', data);

      // Badge
      if (badgeEl) badgeEl.textContent = data.totalItems;

      // Page commande : tableau + total
      if (tableBody && totalEl) {
        tableBody.innerHTML = '';
        data.items.forEach(({ offer, quantity, subtotal }) => {
          // Convertir en nombre
          const priceNum    = parseFloat(offer.price);
          const subtotalNum = priceNum * quantity;

          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${offer.name}</td>
            <td>${quantity}</td>
            <td>${priceNum.toFixed(2)} €</td>
            <td>${subtotalNum.toFixed(2)} €</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-danger remove-from-cart" data-id="${offer.id}">&times;</button>
            </td>
          `;
          tableBody.appendChild(tr);
        });
        totalEl.textContent = parseFloat(data.totalPrice).toFixed(2) + ' €';
      }
    } catch (err) {
      console.error('refreshCart error:', err);
    }
  }

  // initial refresh
  refreshCart();

  document.body.addEventListener('click', async e => {
    // add
    const addBtn = e.target.closest('.add-to-cart');
    if (addBtn) {
      e.preventDefault();
      const id = addBtn.dataset.id;
      console.log('Add to cart clicked, id=', id);
      try {
        const res = await fetch(`${apiBase}/add/${id}`, { method: 'POST', credentials: 'same-origin' });
        console.log('Add response', res.status);
        await refreshCart();
      } catch (err) {
        console.error('add error:', err);
      }
      return;
    }

    // remove
    const removeBtn = e.target.closest('.remove-from-cart');
    if (removeBtn) {
      e.preventDefault();
      const id = removeBtn.dataset.id;
      console.log('Remove from cart clicked, id=', id);
      try {
        const res = await fetch(`${apiBase}/remove/${id}`, { method: 'POST', credentials: 'same-origin' });
        console.log('Remove response', res.status);
        await refreshCart();
      } catch (err) {
        console.error('remove error:', err);
      }
    }
  });
});
