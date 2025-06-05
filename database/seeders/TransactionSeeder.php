<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample successful transaction
        DB::table('transactions')->insert([
            'order_id' => 1,
            'transaction_id' => 'TXN-001-' . time(),
            'payment_method' => 'bank_transfer',
            'gross_amount' => 900000.00,
            'status' => Transaction::STATUS_SETTLEMENT,
            'payment_code' => null,
            'fraud_status' => 'accept',
            'transaction_time' => now()->subHours(2),
            'settlement_time' => now()->subHours(1),
            'midtrans_response' => json_encode([
                'status_code' => '200',
                'status_message' => 'Success, transaction is found',
                'transaction_id' => 'TXN-001-' . time(),
                'order_id' => 1,
                'gross_amount' => '900000.00',
                'payment_type' => 'bank_transfer',
                'transaction_time' => now()->subHours(2)->toISOString(),
                'transaction_status' => 'settlement',
                'fraud_status' => 'accept',
                'bank' => 'bca',
                'va_numbers' => [
                    [
                        'bank' => 'bca',
                        'va_number' => '12345678901'
                    ]
                ]
            ]),
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(1),
        ]);

        // Sample pending transaction
        DB::table('transactions')->insert([
            'order_id' => 2,
            'transaction_id' => 'TXN-002-' . time(),
            'payment_method' => 'gopay',
            'gross_amount' => 700000.00,
            'status' => Transaction::STATUS_PENDING,
            'payment_code' => 'QR_CODE_12345',
            'fraud_status' => null,
            'transaction_time' => now()->subMinutes(30),
            'settlement_time' => null,
            'midtrans_response' => json_encode([
                'status_code' => '201',
                'status_message' => 'GoPay transaction is created',
                'transaction_id' => 'TXN-002-' . time(),
                'order_id' => 2,
                'gross_amount' => '700000.00',
                'payment_type' => 'gopay',
                'transaction_time' => now()->subMinutes(30)->toISOString(),
                'transaction_status' => 'pending',
                'fraud_status' => 'accept',
                'actions' => [
                    [
                        'name' => 'generate-qr-code',
                        'method' => 'GET',
                        'url' => 'https://api.midtrans.com/v2/gopay/qr-code'
                    ]
                ]
            ]),
            'created_at' => now()->subMinutes(30),
            'updated_at' => now()->subMinutes(30),
        ]);

        // Sample expired transaction
        DB::table('transactions')->insert([
            'order_id' => 3,
            'transaction_id' => 'TXN-003-' . time(),
            'payment_method' => 'bank_transfer',
            'gross_amount' => 1100000.00,
            'status' => Transaction::STATUS_EXPIRE,
            'payment_code' => null,
            'fraud_status' => null,
            'transaction_time' => now()->subDays(2),
            'settlement_time' => null,
            'midtrans_response' => json_encode([
                'status_code' => '200',
                'status_message' => 'Success, transaction is found',
                'transaction_id' => 'TXN-003-' . time(),
                'order_id' => 3,
                'gross_amount' => '1100000.00',
                'payment_type' => 'bank_transfer',
                'transaction_time' => now()->subDays(2)->toISOString(),
                'transaction_status' => 'expire',
                'fraud_status' => 'accept',
                'bank' => 'bni',
                'va_numbers' => [
                    [
                        'bank' => 'bni',
                        'va_number' => '98765432101'
                    ]
                ]
            ]),
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDay(),
        ]);

        // Sample failed transaction
        DB::table('transactions')->insert([
            'order_id' => 4,
            'transaction_id' => 'TXN-004-' . time(),
            'payment_method' => 'credit_card',
            'gross_amount' => 2200000.00,
            'status' => Transaction::STATUS_DENY,
            'payment_code' => null,
            'fraud_status' => 'deny',
            'transaction_time' => now()->subHours(3),
            'settlement_time' => null,
            'midtrans_response' => json_encode([
                'status_code' => '200',
                'status_message' => 'Success, transaction is found',
                'transaction_id' => 'TXN-004-' . time(),
                'order_id' => 4,
                'gross_amount' => '2200000.00',
                'payment_type' => 'credit_card',
                'transaction_time' => now()->subHours(3)->toISOString(),
                'transaction_status' => 'deny',
                'fraud_status' => 'deny',
                'masked_card' => '481111-1114',
                'bank' => 'bni',
                'status_message' => 'Transaction is denied by bank'
            ]),
            'created_at' => now()->subHours(3),
            'updated_at' => now()->subHours(3),
        ]);
    }
}
