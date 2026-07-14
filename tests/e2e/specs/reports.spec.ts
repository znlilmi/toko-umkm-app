import { test, expect } from '../fixtures/auth.fixture';
import { LoginPage } from '../pages/login.page';
import { getShippedOrderForBuyer } from '../helpers/db.helper';
import * as fs from 'fs';
import * as path from 'path';

test.describe('Printed Reports and Invoice PDF Verification', () => {
  const pdfDir = path.resolve('docs/testing/pdf-output');

  test.beforeAll(() => {
    // Ensure output directory exists
    if (!fs.existsSync(pdfDir)) {
      fs.mkdirSync(pdfDir, { recursive: true });
    }
  });

  test('1. Verify Invoice PDF Download - Customer', async ({ page }) => {
    // Retrieve a valid order for buyer
    const orderData = await getShippedOrderForBuyer();
    expect(orderData).toBeDefined();
    expect(orderData.orderId).toBeDefined();

    // Log in as the specific buyer who owns the order
    const loginPage = new LoginPage(page);
    await loginPage.navigate();
    await loginPage.login(orderData.email, orderData.password);

    // Verify redirected page after login is not login page anymore
    await expect(page).not.toHaveURL(/.*login/);

    // Navigate directly to the order details page
    await page.goto(`/orders/${orderData.orderId}`);

    // Wait for page to load and confirm invoice number is displayed
    await expect(page.locator('h1')).toContainText(orderData.invoiceNumber);

    // Set up download event listener and click download link
    const downloadPromise = page.waitForEvent('download');
    await page.locator('a:has-text("Unduh Invoice (PDF)")').click();
    const download = await downloadPromise;

    // Verify filename matching pattern and ending in .pdf
    const filename = download.suggestedFilename();
    expect(filename).toMatch(/^invoice-.*\.pdf$/);

    // Save to the destination folder
    const targetPath = path.join(pdfDir, filename);
    await download.saveAs(targetPath);

    // Verify file actually exists on filesystem and has content
    expect(fs.existsSync(targetPath)).toBe(true);
    const stats = fs.statSync(targetPath);
    expect(stats.size).toBeGreaterThan(0);
  });

  test('2. Verify Sales Report PDF Download - Merchant', async ({ sellerPage }) => {
    // Navigate to merchant orders page where the sales report print button is located
    await sellerPage.goto('/merchant/orders');

    // Wait for the sales report cetak button to be visible
    const printButton = sellerPage.locator('a:has-text("Cetak PDF")');
    await expect(printButton).toBeVisible();

    // Set up download event listener and click it
    const downloadPromise = sellerPage.waitForEvent('download');
    await printButton.click();
    const download = await downloadPromise;

    // Verify filename matching pattern and ending in .pdf
    const filename = download.suggestedFilename();
    expect(filename).toMatch(/^laporan-penjualan-.*\.pdf$/);

    // Save to the destination folder
    const targetPath = path.join(pdfDir, filename);
    await download.saveAs(targetPath);

    // Verify file actually exists on filesystem and has content
    expect(fs.existsSync(targetPath)).toBe(true);
    const stats = fs.statSync(targetPath);
    expect(stats.size).toBeGreaterThan(0);
  });

  test('3. Verify Low Stock Report PDF Download - Merchant', async ({ sellerPage }) => {
    // Navigate to merchant inventory page where the stock report print button is located
    await sellerPage.goto('/merchant/inventory');

    // Wait for the low stock print button to be visible
    const printLink = sellerPage.locator('a:has-text("Cetak Stok Kritis (PDF)")');
    await expect(printLink).toBeVisible();

    // Set up download event listener and click it
    const downloadPromise = sellerPage.waitForEvent('download');
    await printLink.click();
    const download = await downloadPromise;

    // Verify filename matching pattern and ending in .pdf
    const filename = download.suggestedFilename();
    expect(filename).toMatch(/^laporan-stok-kritis-.*\.pdf$/);

    // Save to the destination folder
    const targetPath = path.join(pdfDir, filename);
    await download.saveAs(targetPath);

    // Verify file actually exists on filesystem and has content
    expect(fs.existsSync(targetPath)).toBe(true);
    const stats = fs.statSync(targetPath);
    expect(stats.size).toBeGreaterThan(0);
  });

  test('4. Verify Commission Report PDF Download - Admin', async ({ adminPage }) => {
    // Navigate to admin dashboard where the commission report print button is located
    await adminPage.goto('/admin/dashboard');

    // Wait for the print button to be visible
    const printLink = adminPage.locator('a:has-text("Cetak Laporan Komisi (PDF)")');
    await expect(printLink).toBeVisible();

    // Set up download event listener and click it
    const downloadPromise = adminPage.waitForEvent('download');
    await printLink.click();
    const download = await downloadPromise;

    // Verify filename matching pattern and ending in .pdf
    const filename = download.suggestedFilename();
    expect(filename).toMatch(/^laporan-komisi-platform\.pdf$/);

    // Save to the destination folder
    const targetPath = path.join(pdfDir, filename);
    await download.saveAs(targetPath);

    // Verify file actually exists on filesystem and has content
    expect(fs.existsSync(targetPath)).toBe(true);
    const stats = fs.statSync(targetPath);
    expect(stats.size).toBeGreaterThan(0);
  });
});
