<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PdfReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    /**
     * Test downloading order invoice as PDF for customer.
     */
    public function test_customer_can_download_invoice_pdf(): void
    {
        $customer = User::where('role', 'customer')->first();
        $order = Order::where('customer_id', $customer->id)->first();

        if ($customer && $order) {
            $response = $this->actingAs($customer)
                ->get("/orders/{$order->id}/invoice");

            $response->assertStatus(200);
            $response->assertHeader('content-type', 'application/pdf');
        } else {
            $this->markTestSkipped('No customer or order found in seeded DB.');
        }
    }

    /**
     * Test downloading merchant sales report PDF.
     */
    public function test_merchant_can_download_sales_report_pdf(): void
    {
        $merchant = User::where('role', 'merchant')->first();

        if ($merchant && $merchant->shop) {
            $response = $this->actingAs($merchant)
                ->get("/merchant/reports/sales-pdf");

            $response->assertStatus(200);
            $response->assertHeader('content-type', 'application/pdf');
        } else {
            $this->markTestSkipped('No merchant or shop found in seeded DB.');
        }
    }

    /**
     * Test downloading merchant low stock report PDF.
     */
    public function test_merchant_can_download_low_stock_report_pdf(): void
    {
        $merchant = User::where('role', 'merchant')->first();

        if ($merchant && $merchant->shop) {
            $response = $this->actingAs($merchant)
                ->get("/merchant/reports/low-stock-pdf");

            $response->assertStatus(200);
            $response->assertHeader('content-type', 'application/pdf');
        } else {
            $this->markTestSkipped('No merchant or shop found in seeded DB.');
        }
    }

    /**
     * Test downloading admin commission report PDF.
     */
    public function test_admin_can_download_commission_report_pdf(): void
    {
        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            $response = $this->actingAs($admin)
                ->get("/admin/reports/commission-pdf");

            $response->assertStatus(200);
            $response->assertHeader('content-type', 'application/pdf');
        } else {
            $this->markTestSkipped('No admin found in seeded DB.');
        }
    }
}
