/* =========================================================
   PERPUSTAKAAN DIGITAL - APP.JS
   Berisi seluruh interaktivitas: sidebar, modal, alert,
   filter otomatis, konfirmasi hapus, dan grafik dashboard.
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    initSidebar();
    initAlerts();
    initModals();
    initConfirmDelete();
    initAutoSubmitFilter();
    initTopbarDate();
    initDashboardChart();
});

/* ---------- SIDEBAR (mobile toggle) ---------- */
function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const btn = document.getElementById('btnHamburger');
    if (!sidebar || !btn) return;

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('show');
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }

    btn.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
    overlay.addEventListener('click', closeSidebar);
}

/* ---------- ALERT auto-hide & close button ---------- */
function initAlerts() {
    document.querySelectorAll('.alert').forEach(function (alert) {
        const closeBtn = alert.querySelector('.alert-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => fadeOutRemove(alert));
        }
        if (alert.hasAttribute('data-autohide')) {
            setTimeout(() => fadeOutRemove(alert), 5000);
        }
    });
}
function fadeOutRemove(el) {
    el.style.transition = 'opacity .3s ease';
    el.style.opacity = '0';
    setTimeout(() => el.remove(), 300);
}

/* ---------- MODAL generik ----------
   Penggunaan di blade:
   <button data-open-modal="modalTambah">Buka</button>
   <div class="modal-backdrop" id="modalTambah" data-modal>...</div>
   Tombol tutup: data-close-modal
------------------------------------------ */
function initModals() {
    document.querySelectorAll('[data-open-modal]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = btn.getAttribute('data-open-modal');
            const modal = document.getElementById(targetId);
            if (modal) modal.classList.add('show');

            // Jika tombol punya data-fill, isi form modal (untuk mode edit)
            if (btn.hasAttribute('data-fill')) {
                fillModalForm(modal, JSON.parse(btn.getAttribute('data-fill')));
            }
        });
    });

    document.querySelectorAll('[data-close-modal]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const modal = btn.closest('.modal-backdrop');
            if (modal) modal.classList.remove('show');
        });
    });

    // klik area gelap luar modal untuk menutup
    document.querySelectorAll('.modal-backdrop').forEach(function (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) modal.classList.remove('show');
        });
    });

    // tombol ESC menutup modal yang sedang terbuka
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-backdrop.show').forEach(m => m.classList.remove('show'));
        }
    });
}

function fillModalForm(modal, data) {
    if (!modal) return;
    Object.keys(data).forEach(function (key) {
        // Field cover tidak diisi otomatis karena input type="file"
        // tidak bisa di-set nilainya lewat JS (dibatasi browser).
        if (key === 'cover') return;

        const field = modal.querySelector('[name="' + key + '"]');
        if (field) field.value = data[key];
    });

    // Tampilkan preview cover yang sudah ada (kalau ada)
    const previewWrap = modal.querySelector('#coverPreviewWrap');
    const previewImg = modal.querySelector('#coverPreviewImg');
    if (previewWrap && previewImg) {
        if (data.cover) {
            previewImg.src = data.cover;
            previewWrap.style.display = 'block';
        } else {
            previewWrap.style.display = 'none';
        }
    }

    // Set action form jika ada data id (untuk update)
    const form = modal.querySelector('form');
    if (form && data.formAction) {
        form.setAttribute('action', data.formAction);
    }
}

/* ---------- KONFIRMASI HAPUS ----------
   <form data-confirm-delete="Yakin hapus buku ini?"> ... </form>
------------------------------------------- */
function initConfirmDelete() {
    document.querySelectorAll('[data-confirm-delete]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const msg = form.getAttribute('data-confirm-delete') || 'Yakin ingin menghapus data ini?';
            if (!confirm(msg)) {
                e.preventDefault();
            }
        });
    });
}

/* ---------- FILTER OTOMATIS ----------
   Select/dropdown dengan atribut data-auto-submit akan otomatis
   submit form induknya ketika value berubah (tanpa perlu klik tombol)
------------------------------------------- */
function initAutoSubmitFilter() {
    document.querySelectorAll('[data-auto-submit]').forEach(function (el) {
        el.addEventListener('change', function () {
            el.closest('form').submit();
        });
    });

    // Live search dengan debounce (search-box di kategori/buku/ranking)
    document.querySelectorAll('[data-live-search]').forEach(function (input) {
        let timer;
        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(() => input.closest('form').submit(), 600);
        });
    });
}

/* ---------- TANGGAL HARI INI DI TOPBAR ---------- */
function initTopbarDate() {
    const el = document.getElementById('topbarDate');
    if (!el) return;
    const hariIni = new Date();
    const opsi = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    el.textContent = hariIni.toLocaleDateString('id-ID', opsi);
}

/* ---------- GRAFIK DASHBOARD (Chart.js) ----------
   Membaca data dari elemen <script type="application/json" id="chartData">
   yang dituliskan pada dashboard.blade.php
------------------------------------------- */
function initDashboardChart() {
    const canvas = document.getElementById('kategoriChart');
    const dataEl = document.getElementById('chartData');
    if (!canvas || !dataEl || typeof Chart === 'undefined') return;

    const raw = JSON.parse(dataEl.textContent);
    const labels = raw.map(item => item.nama_kategori);
    const values = raw.map(item => item.buku_count);

    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#7c3aed', '#10b981', '#8b5cf6', '#059669',
                    '#a78bfa', '#34d399', '#6d28d9', '#6ee7b7'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 14, font: { size: 11.5 } } }
            },
            cutout: '65%'
        }
    });
}