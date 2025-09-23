// js/totalprice.js
document.addEventListener('DOMContentLoaded', function () {
  const qtyEl   = document.getElementById('ticket-form-number');
  const outEl   = document.getElementById('ticket-form-message');
  const radios  = document.querySelectorAll('input[name="ticket_type"]');

  // Nếu không có các phần tử (ví dụ file được load ở trang khác) thì thoát
  if (!qtyEl || !outEl || !radios.length) return;

  const PRICES = { GA: 735000, NECH_AB: 1450000 };

  function currentType() {
    const checked = document.querySelector('input[name="ticket_type"]:checked');
    return checked ? checked.value : 'GA';
  }

  function calculateTotal() {
    const type = currentType();
    const unit = PRICES[type] ?? PRICES.GA;

    const qtyRaw = qtyEl.value.trim();
    const qty = parseInt(qtyRaw, 10);
    if (isNaN(qty) || qty < 1) {
      outEl.value = '';
      return;
    }

    let total = unit * qty;

    // Combo giảm giá
    if (qty >= 5 && qty < 10) {
      total = Math.round(total * 0.95); // -5%
    } else if (qty >= 10) {
      total = Math.round(total * 0.90); // -10%
    }

    outEl.value = new Intl.NumberFormat('vi-VN').format(total) + ' VND';
  }

  // Lắng nghe mọi thay đổi số lượng & loại vé
  ['input', 'change', 'keyup', 'blur'].forEach(evt => {
    qtyEl.addEventListener(evt, calculateTotal);
  });
  radios.forEach(r => r.addEventListener('change', calculateTotal));

  // Tính ngay lần đầu (nếu đã có sẵn số)
  calculateTotal();
});
