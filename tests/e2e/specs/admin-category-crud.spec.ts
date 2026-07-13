import { test, expect } from '../fixtures/auth.fixture';
import { CategoryPage } from '../pages/category.page';

test.describe('CRUD Kategori Global - Admin', () => {
  test('Harus dapat melakukan Create, Read, Update, dan Delete Kategori', async ({ adminPage }) => {
    const categoryPage = new CategoryPage(adminPage);
    const uniqueId = Date.now();

    const testCategory = {
      name: `Kategori E2E ${uniqueId}`,
      slug: `kategori-e2e-${uniqueId}`,
    };

    const updatedCategory = {
      name: `Grup E2E ${uniqueId}`, // Menggunakan nama "Grup" agar tidak mengandung kata "Kategori"
      slug: `grup-e2e-${uniqueId}`,
    };

    // Step 1: Navigate to Admin Categories Page
    await categoryPage.navigate();
    await expect(adminPage).toHaveURL(/.*admin\/categories/);

    // Step 2: Create Kategori
    await categoryPage.createCategory(testCategory);

    // Verifikasi kategori baru muncul di tabel
    await expect(adminPage.locator('tr', { hasText: testCategory.name })).toBeVisible();
    await expect(adminPage.locator('tr', { hasText: testCategory.slug })).toBeVisible();

    // Step 3: Update Kategori
    await categoryPage.editCategory(testCategory.name, updatedCategory);

    // Verifikasi kategori ter-update muncul di tabel
    await expect(adminPage.locator('tr', { hasText: updatedCategory.name })).toBeVisible();
    await expect(adminPage.locator('tr', { hasText: updatedCategory.slug })).toBeVisible();
    // Kategori lama dengan nama lama harusnya sudah tidak ada
    await expect(adminPage.locator('tr', { hasText: testCategory.name })).toBeHidden();

    // Step 4: Delete Kategori
    await categoryPage.deleteCategory(updatedCategory.name);

    // Verifikasi kategori telah terhapus
    await expect(adminPage.locator('tr', { hasText: updatedCategory.name })).toBeHidden();
  });
});
