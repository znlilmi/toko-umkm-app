import { test as base, Page } from '@playwright/test';
import { LoginPage } from '../pages/login.page';
import { getSeededUser } from '../helpers/db.helper';

type AuthFixtures = {
  adminPage: Page;
  sellerPage: Page;
  buyerPage: Page;
};

export const test = base.extend<AuthFixtures>({
  adminPage: async ({ page }, use) => {
    const user = await getSeededUser('admin');
    const loginPage = new LoginPage(page);
    await loginPage.navigate();
    await loginPage.login(user.email, user.password);
    await use(page);
  },
  sellerPage: async ({ page }, use) => {
    const user = await getSeededUser('seller');
    const loginPage = new LoginPage(page);
    await loginPage.navigate();
    await loginPage.login(user.email, user.password);
    await use(page);
  },
  buyerPage: async ({ page }, use) => {
    const user = await getSeededUser('buyer');
    const loginPage = new LoginPage(page);
    await loginPage.navigate();
    await loginPage.login(user.email, user.password);
    await use(page);
  },
});

export { expect } from '@playwright/test';
