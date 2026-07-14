import { Page, Locator } from '@playwright/test';

export class ReviewManagementPage {
  readonly page: Page;

  constructor(page: Page) {
    this.page = page;
  }

  async navigateAdmin() {
    await this.page.goto('/admin/reviews');
  }

  async navigateMerchant() {
    await this.page.goto('/merchant/reviews');
  }

  async deleteReview(commentText: string) {
    // Find the row or element that has the commentText
    const row = this.page.locator('tr', { hasText: commentText });
    
    // Set up dialog handler for window.confirm() dialog
    this.page.once('dialog', async dialog => {
      await dialog.accept();
    });

    // Click "Hapus" button in that row
    await row.locator('button:has-text("Hapus")').click();
  }
}
