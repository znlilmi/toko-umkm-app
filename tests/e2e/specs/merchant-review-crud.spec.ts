import { test, expect } from '../fixtures/auth.fixture';
import { ReviewManagementPage } from '../pages/review-management.page';
import { execSync } from 'child_process';

test.describe('Kelola Ulasan Produk - Merchant', () => {
  let testComment: string;

  test.beforeAll(async () => {
    // Create a test review for the merchant's product using artisan tinker
    try {
      const setupScript = `
        $user = App\\Models\\User::where('role', 'merchant')->first();
        $product = App\\Models\\Product::where('shop_id', $user->shop->id)->where('is_active', true)->first();
        $customer = App\\Models\\User::where('role', 'customer')->first();
        $order = App\\Models\\Order::create([
          'invoice_number' => 'INV/TEST/' . strtoupper(Illuminate\\Support\\Str::random(8)),
          'customer_id' => $customer->id,
          'shop_id' => $user->shop->id,
          'total_amount' => $product->price,
          'shipping_cost' => 10000,
          'grand_total' => $product->price + 10000,
          'status' => 'completed',
          'shipping_address' => 'E2E Merchant Review Test Address',
          'courier' => 'JNE'
        ]);
        $orderItem = $order->items()->create([
          'product_id' => $product->id,
          'qty' => 1,
          'price' => $product->price,
          'subtotal' => $product->price
        ]);
        $comment = 'E2E_MERCHANT_TEST_COMMENT_' . uniqid();
        App\\Models\\Review::create([
          'product_id' => $product->id,
          'order_item_id' => $orderItem->id,
          'rating' => 4,
          'comment' => $comment
        ]);
        echo $comment;
      `.replace(/\n/g, ' ').replace(/\s+/g, ' ').trim();

      const command = `php artisan tinker --execute="${setupScript}"`;
      const output = execSync(command, { encoding: 'utf-8' }).trim();
      
      const lines = output.split('\n');
      testComment = lines[lines.length - 1].trim();
      console.log(`[E2E Merchant Review] Review created with comment: ${testComment}`);
    } catch (error) {
      console.error('Failed to create merchant test review:', error);
    }
  });

  test('Harus dapat melihat daftar ulasan pelanggan untuk produk toko', async ({ sellerPage }) => {
    const reviewPage = new ReviewManagementPage(sellerPage);

    // Step 1: Navigasi ke halaman ulasan merchant
    await reviewPage.navigateMerchant();
    await expect(sellerPage).toHaveURL(/.*merchant\/reviews/);

    // Step 2: Verifikasi ulasan pelanggan terdaftar di halaman
    await expect(sellerPage.locator('tr', { hasText: testComment })).toBeVisible();
  });
});
