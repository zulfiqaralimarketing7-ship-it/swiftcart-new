document.addEventListener('DOMContentLoaded', function () {
  // Qty controls
  document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const inp = this.closest('.qty-control').querySelector('.qty-input');
      let v = parseInt(inp.value) || 1;
      if (this.dataset.dir === 'up') inp.value = Math.min(v + 1, parseInt(inp.max) || 999);
      else inp.value = Math.max(v - 1, 1);
    });
  });

  // Confirm deletes
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
      if (!confirm(el.dataset.confirm || 'Are you sure?')) e.preventDefault();
    });
  });

  // Wishlist toggle
  document.querySelectorAll('.product-wishlist').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault(); e.stopPropagation();
      this.classList.toggle('active');
      const icon = this.querySelector('i');
      icon.className = this.classList.contains('active') ? 'bi bi-heart-fill' : 'bi bi-heart';
    });
  });

  // Image preview on upload
  document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
    input.addEventListener('change', function () {
      const preview = document.getElementById(this.dataset.preview);
      if (preview && this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => preview.src = e.target.result;
        reader.readAsDataURL(this.files[0]);
      }
    });
  });

  // Drag and drop upload
  document.querySelectorAll('.upload-area').forEach(area => {
    area.addEventListener('dragover', e => { e.preventDefault(); area.classList.add('dragging'); });
    area.addEventListener('dragleave', () => area.classList.remove('dragging'));
    area.addEventListener('drop', e => {
      e.preventDefault(); area.classList.remove('dragging');
      const fileInput = area.closest('form')?.querySelector('input[type="file"]');
      if (fileInput && e.dataTransfer.files.length) {
        fileInput.files = e.dataTransfer.files;
        fileInput.dispatchEvent(new Event('change'));
      }
    });
    area.addEventListener('click', () => {
      const fileInput = area.closest('form')?.querySelector('input[type="file"]') ||
                        area.nextElementSibling;
      if (fileInput) fileInput.click();
    });
  });

  // Auto-dismiss alerts
  document.querySelectorAll('.alert[data-auto-close]').forEach(alert => {
    setTimeout(() => { alert.style.opacity = '0'; setTimeout(() => alert.remove(), 400); }, 3500);
  });
});
