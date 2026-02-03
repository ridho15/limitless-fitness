export function initOrderPackage(doc = document) {
    const form = doc.getElementById('orderForm');
    const submitBtn = doc.getElementById('submitBtn');
    const modal = doc.getElementById('confirmationModal');
    const confirmBtn = doc.getElementById('confirmBtn');
    const cancelBtn = doc.getElementById('cancelBtn');

    // Show alerts if they exist
    const successAlert = doc.getElementById('alert-success');
    const errorAlert = doc.getElementById('alert-error');
    const validationAlert = doc.getElementById('alert-validation');

    if (successAlert) {
        successAlert.classList.add('show');
        setTimeout(() => {
            successAlert.classList.remove('show');
        }, 5000);
    }

    if (errorAlert) {
        errorAlert.classList.add('show');
        setTimeout(() => {
            errorAlert.classList.remove('show');
        }, 5000);
    }

    if (validationAlert) {
        validationAlert.classList.add('show');
        setTimeout(() => {
            validationAlert.classList.remove('show');
        }, 7000);
    }

    if (!submitBtn) return;

    // Submit button click - show confirmation modal
    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();

        // Basic form validation
        const namaEl = doc.getElementById('nama');
        const emailEl = doc.getElementById('email');
        const noHpEl = doc.getElementById('no_hp');
        const alamatEl = doc.getElementById('alamat');

        const nama = (namaEl && namaEl.value || '').trim();
        const email = (emailEl && emailEl.value || '').trim();
        const noHp = (noHpEl && noHpEl.value || '').trim();
        const alamat = (alamatEl && alamatEl.value || '').trim();

        if (!nama || !email || !noHp || !alamat) {
            if (typeof window !== 'undefined' && typeof window.showAlert === 'function') {
                window.showAlert('Mohon lengkapi semua field yang wajib diisi!', 'error');
            } else {
                showAlert('Mohon lengkapi semua field yang wajib diisi!', 'error');
            }
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            if (typeof window !== 'undefined' && typeof window.showAlert === 'function') {
                window.showAlert('Format email tidak valid!', 'error');
            } else {
                showAlert('Format email tidak valid!', 'error');
            }
            return;
        }

        // Show confirmation modal
        if (modal) modal.classList.add('show');
    });

    // Confirm button - submit form
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (modal) modal.classList.remove('show');
            if (form) form.submit();
        });
    }

    // Cancel button - hide modal
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            if (modal) modal.classList.remove('show');
        });
    }

    // Close modal when clicking overlay
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('show');
            }
        });
    }

    // Make showAlert available on doc (for tests) and global
    function showAlert(message, type = 'success') {
        const alertDiv = doc.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
            ${message}
        `;

        doc.body.appendChild(alertDiv);

        // Trigger animation
        setTimeout(() => alertDiv.classList.add('show'), 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 300);
        }, 5000);
    }

    // Attach to global doc for compatibility with inline code
    // Do not overwrite an existing global `showAlert` (tests may inject a spy)
    if (typeof window !== 'undefined' && typeof window.showAlert === 'undefined') {
        window.showAlert = showAlert;
    }

    return { showAlert };
}
