import { test, expect } from '../fixtures/auth.fixture';

test.describe('Dashboard Caching & Refresh - Admin', () => {
  test('Harus dapat melakukan refresh cache data dashboard', async ({ adminPage }) => {
    // Step 1: Buka dashboard admin
    await adminPage.goto('/admin/dashboard');
    await expect(adminPage.locator('h1')).toContainText('Dashboard Ringkasan Platform');

    // Step 2: Cari tombol "Refresh Data" dan klik
    const refreshBtn = adminPage.locator('a:has-text("Refresh Data")');
    await expect(refreshBtn).toBeVisible();
    await refreshBtn.click();

    // Step 3: Verifikasi pengalihan kembali dan adanya alert sukses
    await expect(adminPage).toHaveURL(/.*admin\/dashboard/);
    const successAlert = adminPage.locator('div[role="alert"]', { hasText: 'Data dashboard berhasil diperbarui' });
    await expect(successAlert).toBeVisible();
  });
});
