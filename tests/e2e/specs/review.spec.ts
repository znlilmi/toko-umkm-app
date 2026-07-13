import { test, expect } from '@playwright/test';
import { LoginPage } from '../pages/login.page';
import { OrderPage } from '../pages/order.page';
import { getShippedOrderForBuyer } from '../helpers/db.helper';

test.describe('Alur Review Ulasan Produk - Buyer', () => {
  test('Harus dapat mengonfirmasi terima barang, mengirim ulasan, dan memverifikasi ulasan di detail produk', async ({ page }) => {
    // Step 1: Dapatkan data order shipped acak dari db.helper
    const orderData = await getShippedOrderForBuyer();
    console.log(`[E2E Review] Menggunakan user: ${orderData.email}, Invoice: ${orderData.invoiceNumber}, Produk: ${orderData.productName}`);

    // Step 2: Login sebagai buyer dari orderData
    const loginPage = new LoginPage(page);
    await loginPage.navigate();
    await loginPage.login(orderData.email, orderData.password);
    await expect(page).toHaveURL(/.*dashboard/);

    // Step 3: Navigasi ke halaman orders dan konfirmasi terima barang
    const orderPage = new OrderPage(page);
    await orderPage.navigate();
    await expect(page).toHaveURL(/.*orders/);

    // Konfirmasi Terima Barang (mengubah status menjadi 'completed')
    await orderPage.confirmDelivery(orderData.invoiceNumber);

    // Verifikasi status order berubah menjadi selesai (completed)
    const card = page.locator('.bg-white.border', { hasText: orderData.invoiceNumber });
    await expect(card.locator('span:has-text("Selesai")')).toBeVisible();

    // Step 4: Kirim Ulasan & Rating di Halaman Detail Pesanan
    const uniqueComment = `Ulasan E2E Test dari pembeli acak - ${Date.now()}`;
    await orderPage.submitReview(orderData.orderId, 5, uniqueComment);

    // Verifikasi ulasan berhasil dikirim
    await expect(page.locator('div[role="alert"]', { hasText: 'Ulasan berhasil dikirim' })).toBeVisible();
    await expect(page.locator('p:has-text("' + uniqueComment + '")')).toBeVisible();

    // Step 5: Verifikasi Ulasan Tampil di Halaman Detail Produk
    await page.goto(`/products/${orderData.productSlug}`);
    
    // Verifikasi komentar ulasan kita tampil di halaman detail produk
    const reviewSection = page.locator('.bg-white.border', { hasText: 'Ulasan Pembeli' });
    await expect(reviewSection.locator('p:has-text("' + uniqueComment + '")')).toBeVisible();
  });
});
