import { test, expect } from '../fixtures/auth.fixture';
import { AddressPage } from '../pages/address.page';

test.describe('CRUD Alamat Pengiriman - Buyer', () => {
  test('Harus dapat melakukan Create, Read, Update, dan Delete Alamat', async ({ buyerPage }) => {
    const addressPage = new AddressPage(buyerPage);
    const uniqueId = Date.now();

    const testAddress = {
      recipient: `E2E Recipient ${uniqueId}`,
      phone: '081234567890',
      addressLine: `Jl. Testing E2E No. 42, RT 01/RW 02, Kota Testing ${uniqueId}`,
      cityId: 152,
      isDefault: true,
    };

    const updatedAddress = {
      recipient: `Updated Recipient ${uniqueId}`, // Ubah awalan nama penerima agar tidak terbentur substring match
      phone: '089876543210',
      addressLine: `Jl. Testing E2E No. 99, RT 03/RW 04, Kota Baru ${uniqueId}`,
      cityId: 153,
      isDefault: false,
    };

    // Step 1: Navigate to Addresses Page
    await addressPage.navigate();
    await expect(buyerPage).toHaveURL(/.*addresses/);

    // Step 2: Create Alamat
    await addressPage.createAddress(testAddress);
    
    // Verifikasi alamat baru muncul di daftar
    await expect(buyerPage.locator('.bg-white.border', { hasText: testAddress.recipient })).toBeVisible();
    await expect(buyerPage.locator('.bg-white.border', { hasText: testAddress.addressLine })).toBeVisible();

    // Step 3: Update Alamat
    await addressPage.editAddress(testAddress.recipient, updatedAddress);
    
    // Verifikasi alamat ter-update muncul di daftar
    await expect(buyerPage.locator('.bg-white.border', { hasText: updatedAddress.recipient })).toBeVisible();
    await expect(buyerPage.locator('.bg-white.border', { hasText: updatedAddress.addressLine })).toBeVisible();
    // Alamat lama harusnya sudah tidak ada
    await expect(buyerPage.locator('.bg-white.border', { hasText: testAddress.recipient })).toBeHidden();

    // Step 4: Delete Alamat
    await addressPage.deleteAddress(updatedAddress.recipient);
    
    // Verifikasi alamat telah dihapus
    await expect(buyerPage.locator('.bg-white.border', { hasText: updatedAddress.recipient })).toBeHidden();
  });
});
