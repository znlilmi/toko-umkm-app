import { Page, Locator } from '@playwright/test';

export class ProductPage {
  readonly page: Page;
  readonly addProductButton: Locator;
  readonly nameInput: Locator;
  readonly slugInput: Locator;
  readonly descriptionInput: Locator;
  readonly priceInput: Locator;
  readonly stockInput: Locator;
  readonly weightInput: Locator;
  readonly isActiveCheckbox: Locator;
  readonly submitButton: Locator;

  constructor(page: Page) {
    this.page = page;
    this.addProductButton = page.locator('a:has-text("Tambah Produk Baru")');
    this.nameInput = page.locator('#name');
    this.slugInput = page.locator('#slug');
    this.descriptionInput = page.locator('#description');
    this.priceInput = page.locator('#price');
    this.stockInput = page.locator('#stock');
    this.weightInput = page.locator('#weight');
    this.isActiveCheckbox = page.locator('#is_active');
    this.submitButton = page.locator('button:has-text("Simpan Produk"), button:has-text("Perbarui Produk")');
  }

  async navigate() {
    await this.page.goto('/merchant/products');
  }

  async createProduct(data: {
    name: string;
    slug: string;
    description: string;
    price: number;
    stock: number;
    weight: number;
    categoryIndex?: number;
    isActive?: boolean;
  }) {
    await this.addProductButton.click();
    await this.nameInput.fill(data.name);
    await this.slugInput.fill(data.slug);
    await this.descriptionInput.fill(data.description);
    await this.priceInput.fill(data.price.toString());
    await this.stockInput.fill(data.stock.toString());
    await this.weightInput.fill(data.weight.toString());

    // Select category checkbox (first one by default if not specified)
    const categoryCheckbox = this.page.locator('input[name="category_ids[]"]');
    if (await categoryCheckbox.count() > 0) {
      const idx = data.categoryIndex ?? 0;
      await categoryCheckbox.nth(idx).check();
    }

    if (data.isActive !== undefined) {
      if (data.isActive) {
        await this.isActiveCheckbox.check();
      } else {
        await this.isActiveCheckbox.uncheck();
      }
    }

    await this.submitButton.click();
  }

  async editProduct(productName: string, updatedData: {
    name?: string;
    slug?: string;
    description?: string;
    price?: number;
    stock?: number;
    weight?: number;
    isActive?: boolean;
  }) {
    // Find the row containing product name and click the edit SVG button
    // The edit button has href matching /merchant/products/*/edit
    const row = this.page.locator('tr', { hasText: productName });
    await row.locator('a[href*="/merchant/products/"]').filter({ has: this.page.locator('svg') }).click();

    if (updatedData.name !== undefined) await this.nameInput.fill(updatedData.name);
    if (updatedData.slug !== undefined) await this.slugInput.fill(updatedData.slug);
    if (updatedData.description !== undefined) await this.descriptionInput.fill(updatedData.description);
    if (updatedData.price !== undefined) await this.priceInput.fill(updatedData.price.toString());
    if (updatedData.stock !== undefined) await this.stockInput.fill(updatedData.stock.toString());
    if (updatedData.weight !== undefined) await this.weightInput.fill(updatedData.weight.toString());

    if (updatedData.isActive !== undefined) {
      if (updatedData.isActive) {
        await this.isActiveCheckbox.check();
      } else {
        await this.isActiveCheckbox.uncheck();
      }
    }

    await this.submitButton.click();
  }

  async deleteProduct(productName: string) {
    const row = this.page.locator('tr', { hasText: productName });
    
    this.page.once('dialog', async dialog => {
      await dialog.accept();
    });

    await row.locator('button[type="submit"]').click();
  }
}
