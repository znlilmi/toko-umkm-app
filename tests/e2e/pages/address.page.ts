import { Page, Locator } from '@playwright/test';

export class AddressPage {
  readonly page: Page;
  readonly addAddressButton: Locator;
  readonly recipientInput: Locator;
  readonly phoneInput: Locator;
  readonly addressLineInput: Locator;
  readonly cityIdInput: Locator;
  readonly isDefaultCheckbox: Locator;
  readonly submitButton: Locator;

  constructor(page: Page) {
    this.page = page;
    this.addAddressButton = page.locator('a:has-text("Tambah Alamat Baru")');
    this.recipientInput = page.locator('#recipient_name');
    this.phoneInput = page.locator('#phone');
    this.addressLineInput = page.locator('#address_line');
    this.cityIdInput = page.locator('#city_id');
    this.isDefaultCheckbox = page.locator('#is_default');
    this.submitButton = page.locator('button:has-text("Simpan Alamat"), button:has-text("Perbarui Alamat")');
  }

  async navigate() {
    await this.page.goto('/addresses');
  }

  async createAddress(data: {
    recipient: string;
    phone: string;
    addressLine: string;
    cityId: number;
    isDefault?: boolean;
  }) {
    await this.addAddressButton.click();
    await this.recipientInput.fill(data.recipient);
    await this.phoneInput.fill(data.phone);
    await this.addressLineInput.fill(data.addressLine);
    await this.cityIdInput.fill(data.cityId.toString());
    
    if (data.isDefault) {
      await this.isDefaultCheckbox.check();
    } else {
      await this.isDefaultCheckbox.uncheck();
    }
    
    await this.submitButton.click();
  }

  async editAddress(recipient: string, updatedData: {
    recipient?: string;
    phone?: string;
    addressLine?: string;
    cityId?: number;
    isDefault?: boolean;
  }) {
    // Find the row or card containing the recipient and click Edit Alamat
    const card = this.page.locator('.bg-white.border', { hasText: recipient });
    await card.locator('a:has-text("Edit Alamat")').click();

    if (updatedData.recipient !== undefined) await this.recipientInput.fill(updatedData.recipient);
    if (updatedData.phone !== undefined) await this.phoneInput.fill(updatedData.phone);
    if (updatedData.addressLine !== undefined) await this.addressLineInput.fill(updatedData.addressLine);
    if (updatedData.cityId !== undefined) await this.cityIdInput.fill(updatedData.cityId.toString());
    
    if (updatedData.isDefault !== undefined) {
      if (updatedData.isDefault) {
        await this.isDefaultCheckbox.check();
      } else {
        await this.isDefaultCheckbox.uncheck();
      }
    }

    await this.submitButton.click();
  }

  async deleteAddress(recipient: string) {
    const card = this.page.locator('.bg-white.border', { hasText: recipient });
    
    // Set up dialog handler for the confirm dialog
    this.page.once('dialog', async dialog => {
      await dialog.accept();
    });
    
    await card.locator('button:has-text("Hapus")').click();
  }
}
