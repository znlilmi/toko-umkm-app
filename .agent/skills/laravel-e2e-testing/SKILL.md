---
name: laravel-e2e-testing
description: Panduan penulisan test end-to-end (E2E) menggunakan Playwright untuk proyek TokoKita, mencakup struktur folder, konvensi, custom fixtures per role, helper seeder, dan Page Object Model (POM).
---

# Panduan Penulisan E2E Testing Playwright - TokoKita

Skill ini memandu perancangan, penulisan, dan pemeliharaan pengujian end-to-end (E2E) menggunakan Playwright pada proyek **TokoKita**. Panduan ini memastikan semua pengujian berjalan secara konsisten, efisien, dan mudah dipelihara.

---

## 1. Struktur Folder `tests/e2e/`

Seluruh kode pengujian E2E diletakkan di dalam folder `tests/e2e/` dengan struktur modular sebagai berikut:

```text
tests/e2e/
├── fixtures/
│   └── auth.fixture.ts        # Custom fixtures untuk login per role (admin, seller/merchant, buyer/customer)
├── helpers/
│   └── db.helper.ts           # Helper untuk berinteraksi dengan database/Artisan Tinker
├── pages/
│   ├── login.page.ts          # Page Object Model (POM) untuk halaman Login
│   ├── dashboard.page.ts      # Page Object Model (POM) untuk halaman Dashboard (Seller/Admin)
│   └── ...
├── specs/
│   ├── auth.spec.ts           # Test spec untuk modul Autentikasi
│   ├── product.spec.ts        # Test spec untuk manajemen Produk
│   ├── transaction.spec.ts    # Test spec untuk transaksi Pembelian
│   └── ...
└── example.spec.ts            # Contoh test spec dasar
```

---

## 2. Konvensi Penamaan File & Test Case

1. **Nama File Spec**:
   - Format: `<nama-modul>.spec.ts`
   - Gunakan huruf kecil (lowercase) dan pemisah tanda hubung (kebab-case) jika terdiri dari beberapa kata (contoh: `product-management.spec.ts`).
   - Letakkan di dalam folder `tests/e2e/specs/` atau langsung di bawah `tests/e2e/`.

2. **Nama File Page Object (POM)**:
   - Format: `<nama-halaman>.page.ts`
   - Gunakan format kebab-case (contoh: `product-detail.page.ts`).

3. **Penulisan Test Case**:
   - Gunakan `test.describe('Nama Modul', () => { ... })` untuk mengelompokkan pengujian.
   - Berikan nama test case yang jelas menggambarkan skenario yang diuji:
     ```typescript
     test('should display error message on invalid credentials', async ({ page }) => { ... });
     ```

---

## 3. Pola Page Object Model (POM)

Untuk halaman yang sering diakses atau memiliki interaksi kompleks, wajib menggunakan pola Page Object Model. Berikut adalah contoh struktur untuk halaman Login dan Dashboard:

### A. LoginPage (`tests/e2e/pages/login.page.ts`)
```typescript
import { Page, Locator } from '@playwright/test';

export class LoginPage {
  readonly page: Page;
  readonly emailInput: Locator;
  readonly passwordInput: Locator;
  readonly loginButton: Locator;
  readonly errorMessage: Locator;

  constructor(page: Page) {
    this.page = page;
    this.emailInput = page.locator('input[type="email"]');
    this.passwordInput = page.locator('input[type="password"]');
    this.loginButton = page.locator('button[type="submit"]');
    this.errorMessage = page.locator('.text-red-500'); // Sesuaikan class CSS error
  }

  async navigate() {
    await this.page.goto('/login');
  }

  async login(email: string, password: string) {
    await this.emailInput.fill(email);
    await this.passwordInput.fill(password);
    await this.loginButton.click();
  }
}
```

### B. DashboardPage (`tests/e2e/pages/dashboard.page.ts`)
```typescript
import { Page, Locator } from '@playwright/test';

export class DashboardPage {
  readonly page: Page;
  readonly sidebar: Locator;
  readonly userProfileDropdown: Locator;
  readonly logoutButton: Locator;
  readonly welcomeHeading: Locator;

  constructor(page: Page) {
    this.page = page;
    this.sidebar = page.locator('aside');
    this.userProfileDropdown = page.locator('#user-menu-button');
    this.logoutButton = page.locator('button:has-text("Logout")');
    this.welcomeHeading = page.locator('h1');
  }

  async navigate() {
    await this.page.goto('/dashboard');
  }

  async logout() {
    await this.userProfileDropdown.click();
    await this.logoutButton.click();
  }
}
```

---

## 4. Helper Mengambil Akun Seeder dari Database

Agar pengujian tidak bergantung pada data statis (hardcoded) yang rentan berubah, gunakan helper `tests/e2e/helpers/db.helper.ts` untuk mengambil akun seeder secara dinamis langsung dari database menggunakan command `php artisan tinker`.

### `tests/e2e/helpers/db.helper.ts`
```typescript
import { execSync } from 'child_process';

// Pemetaan role antara istilah E2E (seller/buyer) dengan role di database (merchant/customer)
const ROLE_MAP = {
  admin: 'admin',
  seller: 'merchant',
  buyer: 'customer',
};

export async function getSeededUser(role: 'admin' | 'seller' | 'buyer') {
  const dbRole = ROLE_MAP[role];
  
  try {
    // Eksekusi tinker untuk mendapatkan user pertama dengan role terkait dalam format JSON
    const query = `App\\\\Models\\\\User::where('role', '${dbRole}')->first()?.toJson()`;
    const command = `php artisan tinker --execute="echo ${query}"`;
    const output = execSync(command, { encoding: 'utf-8' }).trim();
    
    // Temukan blok JSON dari output command
    const jsonStart = output.indexOf('{');
    const jsonEnd = output.lastIndexOf('}');
    
    if (jsonStart === -1 || jsonEnd === -1) {
      throw new Error(`Data user dengan role '${dbRole}' tidak ditemukan.`);
    }
    
    const userJson = output.substring(jsonStart, jsonEnd + 1);
    const user = JSON.parse(userJson);
    
    return {
      email: user.email,
      name: user.name,
      // Menggunakan password default dari seeder TokoKita (password123)
      password: 'password123',
    };
  } catch (error) {
    console.warn(`[db.helper] Gagal mengambil user dari database untuk role: ${role}. Menggunakan fallback kredensial standar.`, error);
    
    // Fallback kredensial default jika query database gagal
    const fallbacks = {
      admin: { email: 'admin@tokokita.com', password: 'password123' },
      seller: { email: 'budi@merchant.com', password: 'password123' },
      buyer: { email: 'adit@gmail.com', password: 'password123' },
    };
    return fallbacks[role];
  }
}
```

---

## 5. Custom Fixtures untuk Login per Role

Playwright mendukung perluasan (extension) class `test` untuk menyediakan halaman yang sudah otomatis login berdasarkan role tertentu. Ini sangat menghemat waktu penulisan script test.

### `tests/e2e/fixtures/auth.fixture.ts`
```typescript
import { test as base, Page } from '@playwright/test';
import { LoginPage } from '../pages/login.page';
import { getSeededUser } from '../helpers/db.helper';

// Definisikan tipe fixture baru
type AuthFixtures = {
  adminPage: Page;
  sellerPage: Page;
  buyerPage: Page;
};

// Buat custom test runner dengan meng-extend base test
export const test = base.extend<AuthFixtures>({
  adminPage: async ({ page }, use) => {
    const user = await getSeededUser('admin');
    const loginPage = new LoginPage(page);
    await loginPage.navigate();
    await loginPage.login(user.email, user.password);
    
    // Kembalikan page yang sudah terautentikasi untuk digunakan di test
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
```

### Contoh Penggunaan Fixture di Spec File (`tests/e2e/specs/product.spec.ts`)
```typescript
// Import custom test dari auth.fixture, BUKAN dari @playwright/test langsung
import { test, expect } from '../fixtures/auth.fixture';
import { DashboardPage } from '../pages/dashboard.page';

test.describe('Manajemen Produk oleh Seller', () => {
  // Gunakan sellerPage fixture agar test otomatis dimulai dalam keadaan login
  test('should display dashboard for authenticated seller', async ({ sellerPage }) => {
    const dashboardPage = new DashboardPage(sellerPage);
    await dashboardPage.navigate();
    
    // Verifikasi halaman dashboard termuat
    await expect(dashboardPage.welcomeHeading).toContainText('Dashboard');
    await expect(sellerPage).toHaveURL(/.*dashboard/);
  });
});
```

---

## 6. Cara Menjalankan E2E Testing

Gunakan perintah-perintah berikut untuk menjalankan test:

- **Menjalankan semua test**:
  ```bash
  npx playwright test
  ```

- **Menjalankan test secara interaktif (UI Mode)**:
  ```bash
  npx playwright test --ui
  ```

- **Menjalankan file test tertentu**:
  ```bash
  npx playwright test tests/e2e/specs/auth.spec.ts
  ```

- **Menjalankan test dengan debug mode**:
  ```bash
  npx playwright test --debug
  ```

> [!IMPORTANT]
> **Menjalankan Test pada Proyek ESM (`"type": "module"`)**:
> Jika proyek Anda dikonfigurasi menggunakan tipe ES Modules (terdapat `"type": "module"` di dalam `package.json`), menjalankan `npx playwright test` langsung mungkin akan memicu kesalahan `ERR_UNKNOWN_FILE_EXTENSION` karena Node tidak mengenali ekstensi `.ts` secara langsung.
> 
> Untuk mengatasinya, jalankan pengujian dengan menyertakan loader `tsx` melalui variabel lingkungan `NODE_OPTIONS`:
> - **Windows (PowerShell)**:
>   ```powershell
>   $env:NODE_OPTIONS="--experimental-loader tsx"; npx playwright test
>   ```
> - **Linux / macOS (Bash)**:
>   ```bash
>   NODE_OPTIONS="--experimental-loader tsx" npx playwright test
>   ```
