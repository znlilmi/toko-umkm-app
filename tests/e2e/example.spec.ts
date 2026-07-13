import { test, expect } from '@playwright/test';

test('has title', async ({ page }) => {
  await page.goto('/');

  // Expect title to contain 'TokoKita' or verify content.
  await expect(page).toHaveTitle(/TokoKita|Laravel/);
});
