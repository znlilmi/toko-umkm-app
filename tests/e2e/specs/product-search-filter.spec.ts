import { test, expect } from '@playwright/test';

test.describe('Pencarian & Penyaringan Produk - Katalog', () => {
  test.beforeEach(async ({ page }) => {
    // Navigasi ke katalog produk
    await page.goto('/products');
  });

  test('Harus dapat mencari produk berdasarkan nama', async ({ page }) => {
    const searchForm = page.locator('form[action*="/products"]').first();
    const searchInput = searchForm.locator('input[name="q"]');

    // Cari dengan kata kunci "Kopi"
    await searchInput.fill('Kopi');
    await searchForm.locator('button:has-text("Cari")').click();

    // Verifikasi URL memiliki query parameter q
    await expect(page).toHaveURL(/.*q=Kopi.*/);

    // Kopi Arabika Gayo Premium (85.000) harus tampil
    await expect(page.locator('a:has-text("Kopi Arabika Gayo Premium")')).toBeVisible();
    await expect(page.locator('a:has-text("Kopi Robusta Temanggung")')).toBeVisible();

    // Teh Melati Keraton (15.000) tidak boleh tampil
    await expect(page.locator('a:has-text("Teh Melati Keraton Bag")')).toBeHidden();
  });

  test('Harus dapat memfilter produk berdasarkan kategori', async ({ page }) => {
    // Saring ke kategori Makanan & Minuman
    const categoryLink = page.locator('a:has-text("Makanan & Minuman")').first();
    await categoryLink.click();

    // Verifikasi URL memiliki query parameter category
    await expect(page).toHaveURL(/.*category=makanan-minuman.*/);

    // Kategori terpilih harus aktif (berlatar belakang khusus)
    await expect(page.locator('a:has-text("Makanan & Minuman")').first()).toHaveClass(/bg-indigo-50/);
  });

  test('Harus dapat menyaring produk berdasarkan rentang harga', async ({ page }) => {
    const priceForm = page.locator('form:has(#min_price)');
    const minInput = priceForm.locator('#min_price');
    const maxInput = priceForm.locator('#max_price');

    // Set filter harga 20.000 s/d 50.000
    await minInput.fill('20000');
    await maxInput.fill('50000');
    await priceForm.locator('button:has-text("Terapkan")').click();

    // Verifikasi URL memiliki parameter harga
    await expect(page).toHaveURL(/.*min_price=20000.*/);
    await expect(page).toHaveURL(/.*max_price=50000.*/);

    // Kopi Robusta Temanggung (45.000) harus tampil
    await expect(page.locator('a:has-text("Kopi Robusta Temanggung")')).toBeVisible();

    // Kopi Arabika Gayo Premium (85.000) harus tersembunyi (melebihi maks)
    await expect(page.locator('a:has-text("Kopi Arabika Gayo Premium")')).toBeHidden();

    // Teh Melati Keraton (15.000) harus tersembunyi (kurang dari min)
    await expect(page.locator('a:has-text("Teh Melati Keraton Bag")')).toBeHidden();
  });

  test('Harus dapat memadukan pencarian, kategori, dan rentang harga sekaligus (Composed)', async ({ page }) => {
    // 1. Cari nama "Kopi"
    const searchForm = page.locator('form[action*="/products"]').first();
    await searchForm.locator('input[name="q"]').fill('Kopi');
    await searchForm.locator('button:has-text("Cari")').click();

    // 2. Filter Kategori "Makanan & Minuman"
    await page.locator('a:has-text("Makanan & Minuman")').first().click();

    // 3. Filter Rentang Harga 50.000 s/d 90.000
    const priceForm = page.locator('form:has(#min_price)');
    await priceForm.locator('#min_price').fill('50000');
    await priceForm.locator('#max_price').fill('90000');
    await priceForm.locator('button:has-text("Terapkan")').click();

    // Verifikasi URL mencakup semua parameter
    await expect(page).toHaveURL(/.*q=Kopi.*/);
    await expect(page).toHaveURL(/.*category=makanan-minuman.*/);
    await expect(page).toHaveURL(/.*min_price=50000.*/);
    await expect(page).toHaveURL(/.*max_price=90000.*/);

    // Kopi Arabika Gayo Premium (85.000) harus tampil
    await expect(page.locator('a:has-text("Kopi Arabika Gayo Premium")')).toBeVisible();

    // Kopi Robusta Temanggung (45.000) harus tersembunyi
    await expect(page.locator('a:has-text("Kopi Robusta Temanggung")')).toBeHidden();
  });
});
