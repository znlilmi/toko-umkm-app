import { Page, Locator } from '@playwright/test';

export class DashboardPage {
  readonly page: Page;
  readonly userDropdownButton: Locator;
  readonly logoutLink: Locator;

  constructor(page: Page) {
    this.page = page;
    this.userDropdownButton = page.locator('nav button').filter({ hasText: /.*/ });
    this.logoutLink = page.locator('nav form button, nav form a').filter({ hasText: /Log Out/i });
  }

  async logout() {
    await this.userDropdownButton.click();
    await this.logoutLink.click();
  }
}
