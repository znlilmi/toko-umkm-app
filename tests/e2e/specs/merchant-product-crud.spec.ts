import { test, expect } from '../fixtures/auth.fixture';
import { ProductPage } from '../pages/product.page';

test.describe('CRUD Produk Toko - Merchant', () => {
  test('Harus dapat melakukan Create, Read, Update, dan Delete Produk', async ({ sellerPage }) => {
    const productPage = new ProductPage(sellerPage);
    const uniqueId = Date.now();

    const testProduct = {
      name: `Produk E2E ${uniqueId}`,
      slug: `produk-e2e-${uniqueId}`,
      description: 'Deskripsi produk hasil pengujian E2E Playwright.',
      price: 99000,
      stock: 50,
      weight: 250,
      categoryIndex: 0,
      isActive: true,
    };

    const updatedProduct = {
      name: `Barang E2E ${uniqueId}`,
      slug: `barang-e2e-${uniqueId}`,
      description: 'Deskripsi produk hasil pengujian E2E Playwright yang diperbarui.',
      price: 125000,
      stock: 75,
      weight: 300,
      isActive: true,
    };

    // Step 1: Navigate to Merchant Products Page
    await productPage.navigate();
    await expect(sellerPage).toHaveURL(/.*merchant\/products/);

    // Step 2: Create Produk
    await productPage.createProduct(testProduct);

    // Verifikasi produk baru muncul di tabel dengan harga yang tepat
    const productRow = sellerPage.locator('tr', { hasText: testProduct.name });
    await expect(productRow).toBeVisible();
    await expect(productRow).toContainText(`Rp ${testProduct.price.toLocaleString('id-ID')}`);

    // Step 3: Update Produk
    await productPage.editProduct(testProduct.name, updatedProduct);

    // Verifikasi produk ter-update muncul di tabel dengan harga yang tepat
    const updatedRow = sellerPage.locator('tr', { hasText: updatedProduct.name });
    await expect(updatedRow).toBeVisible();
    await expect(updatedRow).toContainText(`Rp ${updatedProduct.price.toLocaleString('id-ID')}`);
    
    // Produk lama dengan nama lama harusnya sudah tidak ada
    await expect(sellerPage.locator('tr', { hasText: testProduct.name })).toBeHidden();

    // Step 4: Delete Produk
    await productPage.deleteProduct(updatedProduct.name);

    // Verifikasi produk telah terhapus
    await expect(sellerPage.locator('tr', { hasText: updatedProduct.name })).toBeHidden();
  });
});
