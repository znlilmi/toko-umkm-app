import { Page, Locator } from '@playwright/test';

export class CategoryPage {
  readonly page: Page;
  readonly addCategoryButton: Locator;
  readonly nameInput: Locator;
  readonly slugInput: Locator;
  readonly parentSelect: Locator;
  readonly submitButton: Locator;

  constructor(page: Page) {
    this.page = page;
    this.addCategoryButton = page.locator('a:has-text("Tambah Kategori Baru")');
    this.nameInput = page.locator('#name');
    this.slugInput = page.locator('#slug');
    this.parentSelect = page.locator('#parent_id');
    this.submitButton = page.locator('button:has-text("Simpan Kategori"), button:has-text("Perbarui Kategori")');
  }

  async navigate() {
    await this.page.goto('/admin/categories');
  }

  async createCategory(data: {
    name: string;
    slug: string;
    parentId?: string;
  }) {
    await this.addCategoryButton.click();
    await this.nameInput.fill(data.name);
    await this.slugInput.fill(data.slug);

    if (data.parentId !== undefined) {
      await this.parentSelect.selectOption(data.parentId);
    }

    await this.submitButton.click();
  }

  async editCategory(categoryName: string, updatedData: {
    name?: string;
    slug?: string;
    parentId?: string;
  }) {
    // Find the row containing category name and click the edit SVG button
    // The edit button has href matching /admin/categories/*/edit
    const row = this.page.locator('tr', { hasText: categoryName });
    await row.locator('a[href*="/admin/categories/"]').filter({ has: this.page.locator('svg') }).click();

    if (updatedData.name !== undefined) await this.nameInput.fill(updatedData.name);
    if (updatedData.slug !== undefined) await this.slugInput.fill(updatedData.slug);
    if (updatedData.parentId !== undefined) {
      await this.parentSelect.selectOption(updatedData.parentId);
    }

    await this.submitButton.click();
  }

  async deleteCategory(categoryName: string) {
    const row = this.page.locator('tr', { hasText: categoryName });
    
    this.page.once('dialog', async dialog => {
      await dialog.accept();
    });

    await row.locator('button[type="submit"]').click();
  }
}
