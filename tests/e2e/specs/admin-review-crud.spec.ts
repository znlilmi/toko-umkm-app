import { test, expect } from '../fixtures/auth.fixture';
import { ReviewManagementPage } from '../pages/review-management.page';
import { execSync } from 'child_process';

test.describe('Moderasi Ulasan - Admin', () => {
  let testComment: string;

  test.beforeAll(async () => {
    // Create a test review using artisan tinker
    try {
      const setupScript = `
        $user = App\\Models\\User::where('role', 'customer')->first();
        $product = App\\Models\\Product::where('is_active', true)->first();
        $order = App\\Models\\Order::create([
          'invoice_number' => 'INV/TEST/' . strtoupper(Illuminate\\Support\\Str::random(8)),
          'customer_id' => $user->id,
          'shop_id' => $product->shop_id,
          'total_amount' => $product->price,
          'shipping_cost' => 10000,
          'grand_total' => $product->price + 10000,
          'status' => 'completed',
          'shipping_address' => 'E2E Admin Review Test Address',
          'courier' => 'JNE'
        ]);
        $orderItem = $order->items()->create([
          'product_id' => $product->id,
          'qty' => 1,
          'price' => $product->price,
          'subtotal' => $product->price
        ]);
        $comment = 'E2E_ADMIN_TEST_COMMENT_' . uniqid();
        App\\Models\\Review::create([
          'product_id' => $product->id,
          'order_item_id' => $orderItem->id,
          'rating' => 5,
          'comment' => $comment
        ]);
        echo $comment;
      `.replace(/\n/g, ' ').replace(/\s+/g, ' ').trim();

      const command = `php artisan tinker --execute="${setupScript}"`;
      const output = execSync(command, { encoding: 'utf-8' }).trim();
      
      // Extract the comment from the command output
      const lines = output.split('\n');
      testComment = lines[lines.length - 1].trim();
      console.log(`[E2E Admin Review] Review created with comment: ${testComment}`);
    } catch (error) {
      console.error('Failed to create test review:', error);
    }
  });

  test('Harus dapat melihat daftar ulasan dan menghapus ulasan bermasalah', async ({ adminPage }) => {
    const reviewPage = new ReviewManagementPage(adminPage);

    // Step 1: Navigasi ke halaman moderasi ulasan
    await reviewPage.navigateAdmin();
    await expect(adminPage).toHaveURL(/.*admin\/reviews/);

    // Step 2: Verifikasi ulasan buatan kita terdaftar di halaman
    await expect(adminPage.locator('tr', { hasText: testComment })).toBeVisible();

    // Step 3: Hapus ulasan
    await reviewPage.deleteReview(testComment);

    // Step 4: Verifikasi pesan sukses dan hilangnya ulasan dari halaman
    await expect(adminPage.locator('div[role="alert"]', { hasText: 'Ulasan berhasil dihapus' })).toBeVisible();
    await expect(adminPage.locator('tr', { hasText: testComment })).toBeHidden();
  });
});
