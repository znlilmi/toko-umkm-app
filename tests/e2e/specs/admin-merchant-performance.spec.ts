import { test, expect } from '../fixtures/auth.fixture';

test.describe('Performa Penjual - Admin', () => {
  test('Harus dapat mengakses halaman performa penjual dan melihat visualisasi grafik serta ringkasan tabel', async ({ adminPage }) => {
    // Step 1: Buka dashboard admin
    await adminPage.goto('/admin/dashboard');

    // Step 2: Klik menu "Performa Penjual" di sidebar
    await adminPage.locator('a:has-text("Performa Penjual")').click();

    // Step 3: Verifikasi URL dan heading halaman
    await expect(adminPage).toHaveURL(/.*admin\/reports\/merchant-performance/);
    await expect(adminPage.locator('h1')).toContainText('Performa & Omzet Penjual');

    // Step 4: Verifikasi adanya canvas chart dan tabel ringkasan
    const chartCanvas = adminPage.locator('#merchantPerformanceChart');
    await expect(chartCanvas).toBeVisible();

    const summaryTableHeading = adminPage.locator('h3:has-text("Tabel Ringkasan Performa Merchant")');
    await expect(summaryTableHeading).toBeVisible();

    // Verify that merchant rows exist
    const tableRows = adminPage.locator('table tbody tr');
    await expect(tableRows.first()).toBeVisible();
  });
});
