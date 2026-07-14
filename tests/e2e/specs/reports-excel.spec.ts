import { test, expect } from '../fixtures/auth.fixture';
import * as fs from 'fs';
import * as path from 'path';

/**
 * Test Suite: Excel Spreadsheet Report Downloads
 *
 * Memverifikasi semua fitur unduhan laporan dalam format Excel (.xlsx)
 * yang tersedia untuk Merchant (Seller) di platform TokoKita.
 *
 * Reports yang diuji:
 *  1. Rekap Penjualan (Sales Report)        — /merchant/reports/sales-excel
 *  2. Mutasi Stok (Stock Mutation)           — /merchant/reports/stock-mutation-excel
 *  3. Laporan Ulasan (Customer Reviews)      — /merchant/reports/review-excel
 */
test.describe('Excel Spreadsheet Report Downloads - Merchant', () => {
  const xlsxDir = path.resolve('docs/testing/spreadsheet-output');

  test.beforeAll(() => {
    if (!fs.existsSync(xlsxDir)) {
      fs.mkdirSync(xlsxDir, { recursive: true });
    }
  });

  // ---------------------------------------------------------------------------
  // Test 1: Rekap Penjualan (Sales Report) — dari halaman /merchant/orders
  // ---------------------------------------------------------------------------
  test('1. Verify Sales Report Excel Download - Merchant', async ({ sellerPage }) => {
    await sellerPage.goto('/merchant/orders');
    await expect(sellerPage).not.toHaveURL(/.*login/);

    // Tunggu Alpine.js selesai menginisialisasi :href dinamis
    await sellerPage.waitForLoadState('networkidle');

    const exportLink = sellerPage.locator('a:has-text("Ekspor Excel")').first();
    await expect(exportLink).toBeVisible();

    const downloadPromise = sellerPage.waitForEvent('download');
    await exportLink.click();
    const download = await downloadPromise;

    const filename = download.suggestedFilename();
    // Verifikasi pola nama file: rekap-penjualan-<slug-toko>.xlsx
    expect(filename).toMatch(/^rekap-penjualan-.+\.xlsx$/);
    expect(filename.endsWith('.xlsx')).toBe(true);

    const targetPath = path.join(xlsxDir, filename);
    await download.saveAs(targetPath);

    expect(fs.existsSync(targetPath)).toBe(true);
    const stats = fs.statSync(targetPath);
    expect(stats.size).toBeGreaterThan(0);

    console.log(`[OK] Sales Excel saved: ${filename} (${stats.size} bytes)`);
  });

  // ---------------------------------------------------------------------------
  // Test 2: Mutasi Stok (Stock Mutation) — dari halaman /merchant/inventory
  // ---------------------------------------------------------------------------
  test('2. Verify Stock Mutation Excel Download - Merchant', async ({ sellerPage }) => {
    await sellerPage.goto('/merchant/inventory');
    await expect(sellerPage).not.toHaveURL(/.*login/);

    const exportLink = sellerPage.locator('a:has-text("Ekspor Mutasi Stok (Excel)")');
    await expect(exportLink).toBeVisible();

    const downloadPromise = sellerPage.waitForEvent('download');
    await exportLink.click();
    const download = await downloadPromise;

    const filename = download.suggestedFilename();
    // Verifikasi pola nama file: mutasi-stok-<slug-toko>.xlsx
    expect(filename).toMatch(/^mutasi-stok-.+\.xlsx$/);
    expect(filename.endsWith('.xlsx')).toBe(true);

    const targetPath = path.join(xlsxDir, filename);
    await download.saveAs(targetPath);

    expect(fs.existsSync(targetPath)).toBe(true);
    const stats = fs.statSync(targetPath);
    expect(stats.size).toBeGreaterThan(0);

    console.log(`[OK] Stock Mutation Excel saved: ${filename} (${stats.size} bytes)`);
  });

  // ---------------------------------------------------------------------------
  // Test 3: Laporan Ulasan Pelanggan (Customer Reviews) — dari /merchant/reviews
  // ---------------------------------------------------------------------------
  test('3. Verify Customer Reviews Excel Download - Merchant', async ({ sellerPage }) => {
    await sellerPage.goto('/merchant/reviews');
    await expect(sellerPage).not.toHaveURL(/.*login/);

    const exportLink = sellerPage.locator('a:has-text("Ekspor Ulasan (Excel)")');
    await expect(exportLink).toBeVisible();

    const downloadPromise = sellerPage.waitForEvent('download');
    await exportLink.click();
    const download = await downloadPromise;

    const filename = download.suggestedFilename();
    // Verifikasi pola nama file: laporan-ulasan-<slug-toko>.xlsx
    expect(filename).toMatch(/^laporan-ulasan-.+\.xlsx$/);
    expect(filename.endsWith('.xlsx')).toBe(true);

    const targetPath = path.join(xlsxDir, filename);
    await download.saveAs(targetPath);

    expect(fs.existsSync(targetPath)).toBe(true);
    const stats = fs.statSync(targetPath);
    expect(stats.size).toBeGreaterThan(0);

    console.log(`[OK] Reviews Excel saved: ${filename} (${stats.size} bytes)`);
  });

  // ---------------------------------------------------------------------------
  // Test 4: Rekap Penjualan dengan filter tanggal kustom
  // ---------------------------------------------------------------------------
  test('4. Verify Sales Report Excel with custom date range filter - Merchant', async ({ sellerPage }) => {
    await sellerPage.goto('/merchant/orders');
    await expect(sellerPage).not.toHaveURL(/.*login/);
    await sellerPage.waitForLoadState('networkidle');

    const startInput = sellerPage.locator('input[type="date"]').first();
    const endInput   = sellerPage.locator('input[type="date"]').last();

    const today = new Date();
    const threeMonthsAgo = new Date(today);
    threeMonthsAgo.setMonth(today.getMonth() - 3);

    const fmt = (d: Date) => d.toISOString().split('T')[0];

    if (await startInput.isVisible()) {
      await startInput.fill(fmt(threeMonthsAgo));
    }
    if (await endInput.isVisible()) {
      await endInput.fill(fmt(today));
    }

    const exportLink = sellerPage.locator('a:has-text("Ekspor Excel")').first();
    await expect(exportLink).toBeVisible();

    const downloadPromise = sellerPage.waitForEvent('download');
    await exportLink.click();
    const download = await downloadPromise;

    const filename = download.suggestedFilename();
    expect(filename).toMatch(/^rekap-penjualan-.+\.xlsx$/);
    expect(filename.endsWith('.xlsx')).toBe(true);

    const targetPath = path.join(xlsxDir, filename.replace('.xlsx', '_filtered.xlsx'));
    await download.saveAs(targetPath);

    expect(fs.existsSync(targetPath)).toBe(true);
    const stats = fs.statSync(targetPath);
    expect(stats.size).toBeGreaterThan(0);

    console.log(`[OK] Sales Excel (filtered) saved: ${filename} (${stats.size} bytes)`);
  });

  // ---------------------------------------------------------------------------
  // Test 5: Akses langsung via URL endpoint — menggunakan page.evaluate untuk
  //         memicu download tanpa page.goto() yang terblokir oleh response download
  // ---------------------------------------------------------------------------
  test('5. Verify direct URL access to Excel endpoints returns .xlsx file - Merchant', async ({ sellerPage }) => {
    const endpoints = [
      { url: '/merchant/reports/sales-excel', pattern: /^rekap-penjualan-.+\.xlsx$/ },
      { url: '/merchant/reports/stock-mutation-excel', pattern: /^mutasi-stok-.+\.xlsx$/ },
      { url: '/merchant/reports/review-excel', pattern: /^laporan-ulasan-.+\.xlsx$/ },
    ];

    for (const ep of endpoints) {
      // Gunakan waitForEvent('download') sebelum navigasi, lalu tangani error navigasi
      // yang expected karena response adalah file download bukan halaman HTML
      const downloadPromise = sellerPage.waitForEvent('download');

      // page.goto ke URL download akan throw error "Download is starting" — ini expected.
      // Gunakan catch untuk mengabaikan error navigasi dan tetap menunggu event download.
      sellerPage.goto(ep.url).catch(() => {
        // Expected: navigasi ke URL download menghasilkan error karena bukan halaman HTML
      });

      const download = await downloadPromise;
      const filename = download.suggestedFilename();
      expect(filename).toMatch(ep.pattern);
      expect(filename.endsWith('.xlsx')).toBe(true);

      const targetPath = path.join(xlsxDir, `direct_${filename}`);
      await download.saveAs(targetPath);

      expect(fs.existsSync(targetPath)).toBe(true);
      const stats = fs.statSync(targetPath);
      expect(stats.size).toBeGreaterThan(0);

      console.log(`[OK] Direct URL download OK: ${filename} (${stats.size} bytes)`);
    }
  });
});
