// public/js/cart.js

// Met à jour le badge en récupérant le total dans /api/cart
async function refreshCartBadge() {
  try {
    const res = await fetch('/api/cart');
    const data = await res.json();
    document.getElementById('cart-badge').textContent = data.totalItems;
  } catch (e) {
    console.error('Erreur lors de la récupération du panier :', e);
  }
}

// Ajoute un listener sur tous les boutons “add-to-cart”
function bindAddButtons() {
  document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.dataset.id;
      try {
        const res = await fetch(`/api/cart/add/${id}`, { method: 'POST' });
        const data = await res.json();
        document.getElementById('cart-badge').textContent = data.totalItems;
      } catch (e) {
        console.error('Erreur lors de l\'ajout au panier :', e);
      }
    });
  });
}

document.addEventListener('DOMContentLoaded', () => {
  refreshCartBadge();
  bindAddButtons();
});
