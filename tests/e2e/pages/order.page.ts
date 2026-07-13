import { Page, Locator } from '@playwright/test';

export class OrderPage {
  readonly page: Page;

  constructor(page: Page) {
    this.page = page;
  }

  async navigate() {
    await this.page.goto('/orders');
  }

  async confirmDelivery(invoiceNumber: string) {
    // Find the order card by invoice number
    const card = this.page.locator('.bg-white.border', { hasText: invoiceNumber });
    
    // Set up dialog handler for window.confirm() dialog
    this.page.once('dialog', async dialog => {
      await dialog.accept();
    });

    // Click "Konfirmasi Terima Barang"
    await card.locator('button:has-text("Konfirmasi Terima Barang")').click();
  }

  async submitReview(orderId: number, rating: number, comment: string) {
    // Navigate directly to the order detail page
    await this.page.goto(`/orders/${orderId}`);
    
    // Locate the review form (there might be multiple items, so target the first one or the form)
    const reviewForm = this.page.locator('form[action*="/reviews"]').first();
    await expect(reviewForm).toBeVisible();

    // Fill rating and comment
    await reviewForm.locator('select[name="rating"]').selectOption(rating.toString());
    await reviewForm.locator('textarea[name="comment"]').fill(comment);

    // Click submit
    await reviewForm.locator('button:has-text("Kirim Ulasan")').click();
  }
}

// Re-export expect from Playwright to prevent imports duplication in spec
import { expect } from '@playwright/test';
