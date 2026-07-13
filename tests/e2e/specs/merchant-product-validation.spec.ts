import { test, expect } from '../fixtures/auth.fixture';

test.describe('Validasi Form & Konfirmasi Hapus Produk - Merchant', () => {
  test('Harus melakukan validasi real-time dan dialog konfirmasi hapus', async ({ sellerPage }) => {
    // 1. Navigasi ke Form Tambah Produk
    await sellerPage.goto('/merchant/products/create');
    await expect(sellerPage).toHaveURL(/.*merchant\/products\/create/);

    // 2. Uji validasi Nama (< 5 karakter)
    const nameInput = sellerPage.locator('#name');
    const nameError = sellerPage.locator('#error-name');
    await nameInput.fill('Kopi');
    await expect(nameError).toHaveText('Nama produk minimal harus 5 karakter.');

    // Perbaiki nama agar valid
    const uniqueName = `Valid E2E Product ${Date.now()}`;
    await nameInput.fill(uniqueName);
    await expect(nameError).toHaveText('');

    // 3. Uji validasi Harga negatif
    const priceInput = sellerPage.locator('#price');
    const priceError = sellerPage.locator('#error-price');
    await priceInput.fill('-5000');
    await expect(priceError).toHaveText('Harga jual tidak boleh negatif.');

    // Perbaiki harga agar valid
    await priceInput.fill('50000');
    await expect(priceError).toHaveText('');

    // 4. Uji validasi Stok desimal
    const stockInput = sellerPage.locator('#stock');
    const stockError = sellerPage.locator('#error-stock');
    await stockInput.fill('10.5');
    await expect(stockError).toHaveText('Stok harus berupa angka bulat.');

    // Uji validasi Stok negatif
    await stockInput.fill('-10');
    await expect(stockError).toHaveText('Stok tidak boleh negatif.');

    // Perbaiki stok agar valid
    await stockInput.fill('20');
    await expect(stockError).toHaveText('');

    // Isi berat & kategori untuk meloloskan submit
    await sellerPage.locator('#weight').fill('150');
    await sellerPage.locator('input[type="checkbox"]').first().check();

    // Submit form (membuat produk sementara untuk ditest hapus)
    await sellerPage.locator('button:has-text("Simpan Produk")').click();
    await expect(sellerPage).toHaveURL(/.*merchant\/products/);

    // 5. Uji Konfirmasi Dialog Hapus (Batal / Dismiss)
    const productRow = sellerPage.locator('tr', { hasText: uniqueName });
    await expect(productRow).toBeVisible();

    // Listener untuk menolak (Dismiss) konfirmasi dialog
    sellerPage.once('dialog', async dialog => {
      expect(dialog.message()).toContain('Apakah Anda yakin ingin menghapus produk ini?');
      await dialog.dismiss();
    });

    // Klik tombol hapus
    await productRow.locator('button[type="submit"]').click();

    // Produk harus masih tetap ada di tabel
    await expect(productRow).toBeVisible();

    // 6. Uji Konfirmasi Dialog Hapus (Setuju / Accept)
    sellerPage.once('dialog', async dialog => {
      expect(dialog.message()).toContain('Apakah Anda yakin ingin menghapus produk ini?');
      await dialog.accept();
    });

    // Klik tombol hapus lagi
    await productRow.locator('button[type="submit"]').click();

    // Produk sekarang harus sudah terhapus
    await expect(productRow).toBeHidden();
  });
});
