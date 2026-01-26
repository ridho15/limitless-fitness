import { describe, it, expect } from 'vitest'
import { formatCurrencyIDR, formatMonthsLabel } from '../utils/format'

describe('format utils', () => {
    it('formats integer currency correctly', () => {
        expect(formatCurrencyIDR(150000)).toBe('Rp150.000')
    })

    it('formats zero and non-numeric to Rp0', () => {
        expect(formatCurrencyIDR(null)).toBe('Rp0')
        expect(formatCurrencyIDR('abc')).toBe('Rp0')
    })

    it('formats months label', () => {
        expect(formatMonthsLabel(3)).toBe('3 bulan')
        expect(formatMonthsLabel(null)).toBe('0 bulan')
    })
})
