import { execSync } from 'child_process';

const ROLE_MAP = {
  admin: 'admin',
  seller: 'merchant',
  buyer: 'customer',
};

export async function getSeededUser(role: 'admin' | 'seller' | 'buyer') {
  const dbRole = ROLE_MAP[role];
  
  try {
    const query = `App\\Models\\User::where('role', '${dbRole}')->first()?->toJson()`;
    const command = `php artisan tinker --execute="echo ${query}"`;
    const output = execSync(command, { encoding: 'utf-8' }).trim();
    
    const jsonStart = output.indexOf('{');
    const jsonEnd = output.lastIndexOf('}');
    
    if (jsonStart === -1 || jsonEnd === -1) {
      throw new Error(`Data user dengan role '${dbRole}' tidak ditemukan di database.`);
    }
    
    const userJson = output.substring(jsonStart, jsonEnd + 1);
    const user = JSON.parse(userJson);
    
    return {
      email: user.email,
      name: user.name,
      password: 'password123', // Password default dari seeder TokoKita
    };
  } catch (error) {
    console.warn(`[db.helper] Gagal mengambil user dari database untuk role: ${role}. Menggunakan fallback.`, error);
    
    const fallbacks = {
      admin: { email: 'admin@tokokita.com', password: 'password123' },
      seller: { email: 'budi@merchant.com', password: 'password123' },
      buyer: { email: 'adit@gmail.com', password: 'password123' },
    };
    return fallbacks[role];
  }
}

export async function getShippedOrderForBuyer() {
  try {
    const query = `App\\Models\\Order::where('status', 'shipped')->whereHas('customer')->whereHas('items.product')->with('customer', 'items.product')->first()?->toJson()`;
    let command = `php artisan tinker --execute="echo ${query}"`;
    let output = execSync(command, { encoding: 'utf-8' }).trim();
    
    let jsonStart = output.indexOf('{');
    let jsonEnd = output.lastIndexOf('}');
    
    if (jsonStart === -1 || jsonEnd === -1) {
      console.log("[db.helper] Tidak ada order shipped ditemukan. Membuat baru...");
      const setupScript = `
        $user = App\\Models\\User::where('role', 'customer')->first();
        $product = App\\Models\\Product::where('is_active', true)->first();
        $shop = App\\Models\\Shop::find($product->shop_id);
        $order = App\\Models\\Order::create([
          'invoice_number' => 'INV/TEST/' . strtoupper(Illuminate\\Support\\Str::random(8)),
          'customer_id' => $user->id,
          'shop_id' => $shop->id,
          'total_amount' => $product->price,
          'shipping_cost' => 10000,
          'grand_total' => $product->price + 10000,
          'status' => 'shipped',
          'shipping_address' => 'E2E Address',
          'courier' => 'JNE',
          'tracking_number' => 'TRACK123'
        ]);
        $order->items()->create([
          'product_id' => $product->id,
          'qty' => 1,
          'price' => $product->price,
          'subtotal' => $product->price
        ]);
        echo $order->load('customer', 'items.product')->toJson();
      `.replace(/\n/g, ' ').replace(/\s+/g, ' ').trim();
      
      command = `php artisan tinker --execute="${setupScript}"`;
      output = execSync(command, { encoding: 'utf-8' }).trim();
      jsonStart = output.indexOf('{');
      jsonEnd = output.lastIndexOf('}');
    }
    
    if (jsonStart === -1 || jsonEnd === -1) {
      throw new Error("Gagal mengambil atau membuat order shipped.");
    }
    
    const orderJson = output.substring(jsonStart, jsonEnd + 1);
    const order = JSON.parse(orderJson);
    
    return {
      email: order.customer.email,
      password: 'password123',
      invoiceNumber: order.invoice_number,
      orderId: order.id,
      productName: order.items[0].product.name,
      productSlug: order.items[0].product.slug,
    };
  } catch (error) {
    console.error("Gagal mengambil order shipped dari database:", error);
    throw error;
  }
}
