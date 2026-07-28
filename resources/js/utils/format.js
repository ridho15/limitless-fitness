export function formatCurrencyIDR(amount) {
    // Ensure number
    const n = Number(amount) || 0;
    return 'Rp' + n.toLocaleString('id-ID');
}

export function formatMonthsLabel(months) {
    const m = Number(months) || 0;
    return m + (m === 1 ? ' bulan' : ' bulan');
}
