// Simple Node-based test runner to avoid npm dependency issues
(async () => {
  try {
    const path = require('path')
    const { pathToFileURL } = require('url')

  const utilPath = path.resolve(__dirname, '..', 'utils', 'format.mjs')
    const mod = await import(pathToFileURL(utilPath).href)
    const { formatCurrencyIDR, formatMonthsLabel } = mod
    const assert = require('assert')

    assert.strictEqual(formatCurrencyIDR(150000), 'Rp150.000')
    assert.strictEqual(formatCurrencyIDR(null), 'Rp0')
    assert.strictEqual(formatMonthsLabel(3), '3 bulan')

    console.log('JS node tests passed')
    process.exit(0)
  } catch (err) {
    console.error('JS node tests failed')
    console.error(err)
    process.exit(2)
  }
})()
