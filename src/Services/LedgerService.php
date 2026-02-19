<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Services;

use FoleyBridgeSolutions\PracticeCsPI\Data\LedgerEntry;
use FoleyBridgeSolutions\PracticeCsPI\Events\PaymentWriteFailed;
use FoleyBridgeSolutions\PracticeCsPI\Events\PaymentWritten;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\LedgerWriteException;
use FoleyBridgeSolutions\PracticeCsPI\Exceptions\PracticeCsException;

/**
 * Ledger write operations against the PracticeCS API.
 *
 * Maps to PracticeCsPaymentWriter methods in TR-Pay.
 * Handles writing payments, memos, and deferred payments to PracticeCS.
 *
 * Note: allocateToInvoices(), updatePlanTracking(), settlePlanTracking(),
 * and revertPlanTracking() operate on local PaymentPlan models and stay in TR-Pay.
 */
class LedgerService
{
    /**
     * The API client instance.
     */
    protected ApiClient $api;

    /**
     * Create a new ledger service instance.
     *
     * @param  ApiClient  $api  The API client
     */
    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * Write a payment to PracticeCS.
     *
     * Creates a ledger entry of type payment/credit, applies to invoices,
     * creates billing decision collection, and records online payment.
     *
     * @param  array  $paymentData  Payment details:
     *                              - client_KEY: int (required)
     *                              - amount: float (required)
     *                              - ledger_type_KEY: int (required) — from config ledger_types
     *                              - staff_KEY: int (required)
     *                              - bank_account_KEY: int (required)
     *                              - reference: string (required) — payment reference/transaction ID
     *                              - description: string (optional)
     *                              - entry_date: string (optional, Y-m-d)
     *                              - invoices: array (optional) — invoice allocations
     *                              - group_distribution: array (optional) — for group payments
     * @return LedgerEntry The created ledger entry
     *
     * @throws LedgerWriteException
     * @throws PracticeCsException
     */
    public function writePayment(array $paymentData): LedgerEntry
    {
        $this->validatePaymentData($paymentData);

        try {
            $response = $this->api->post('/api/ledger/payments', $paymentData);

            $entry = LedgerEntry::fromArray($response['data']);

            if ($entry->success) {
                PaymentWritten::dispatch(
                    $paymentData['client_KEY'],
                    (float) $paymentData['amount'],
                    $paymentData['payment_method'],
                    $entry
                );
            }

            return $entry;
        } catch (PracticeCsException $e) {
            PaymentWriteFailed::dispatch(
                $paymentData['client_KEY'] ?? 0,
                (float) ($paymentData['amount'] ?? 0),
                $paymentData['payment_method'] ?? 'unknown',
                $e->getMessage()
            );

            throw LedgerWriteException::paymentFailed(
                $e->getMessage(),
                $e->getResponseBody()
            );
        }
    }

    /**
     * Write a memo (credit memo or debit memo) to PracticeCS.
     *
     * @param  array  $memoData  Memo details:
     *                           - client_KEY: int (required)
     *                           - amount: float (required)
     *                           - staff_KEY: int (required)
     *                           - description: string (optional)
     *                           - entry_date: string (optional, Y-m-d)
     * @param  string  $memoType  Type of memo: 'credit' or 'debit'
     * @return LedgerEntry The created ledger entry
     *
     * @throws LedgerWriteException
     * @throws PracticeCsException
     */
    public function writeMemo(array $memoData, string $memoType): LedgerEntry
    {
        $this->validateMemoData($memoData, $memoType);

        try {
            $memoData['memo_type'] = $memoType;

            $response = $this->api->post('/api/ledger/memos', $memoData);

            return LedgerEntry::fromArray($response['data']);
        } catch (PracticeCsException $e) {
            throw LedgerWriteException::memoFailed(
                $e->getMessage(),
                $e->getResponseBody()
            );
        }
    }

    /**
     * Write a deferred payment to PracticeCS.
     *
     * Used for payment plans where the payment is recorded but
     * not yet fully applied.
     *
     * @param  array  $deferredData  Deferred payment details:
     *                               - client_KEY: int (required)
     *                               - amount: float (required)
     *                               - staff_KEY: int (required)
     *                               - bank_account_KEY: int (required)
     *                               - payment_method: string (required)
     *                               - reference_number: string (optional)
     *                               - description: string (optional)
     *                               - entry_date: string (optional, Y-m-d)
     * @return LedgerEntry The created ledger entry
     *
     * @throws LedgerWriteException
     * @throws PracticeCsException
     */
    public function writeDeferredPayment(array $deferredData): LedgerEntry
    {
        $this->validateDeferredPaymentData($deferredData);

        $payment = $deferredData['payment'];

        try {
            $response = $this->api->post('/api/ledger/deferred-payments', $deferredData);

            $entry = LedgerEntry::fromArray($response['data']);

            if ($entry->success) {
                PaymentWritten::dispatch(
                    $payment['client_KEY'],
                    (float) $payment['amount'],
                    $payment['payment_method'] ?? 'deferred',
                    $entry
                );
            }

            return $entry;
        } catch (PracticeCsException $e) {
            PaymentWriteFailed::dispatch(
                $payment['client_KEY'] ?? 0,
                (float) ($payment['amount'] ?? 0),
                $payment['payment_method'] ?? 'deferred',
                $e->getMessage()
            );

            throw LedgerWriteException::paymentFailed(
                $e->getMessage(),
                $e->getResponseBody()
            );
        }
    }

    /**
     * Apply a payment to specific invoices.
     *
     * @param  int  $paymentLedgerKey  The ledger_entry_KEY of the payment
     * @param  array  $invoices  Array of invoice allocations, each with:
     *                           - ledger_entry_KEY: int — the invoice ledger entry key
     *                           - amount: float — amount to apply
     * @param  int  $staffKey  The staff_KEY performing the operation
     * @return array API response data
     *
     * @throws LedgerWriteException
     * @throws PracticeCsException
     */
    public function applyToInvoices(int $paymentLedgerKey, array $invoices, int $staffKey): array
    {
        try {
            $response = $this->api->post('/api/ledger/apply-to-invoices', [
                'payment_ledger_KEY' => $paymentLedgerKey,
                'invoices' => $invoices,
                'staff_KEY' => $staffKey,
            ]);

            return $response['data'] ?? [];
        } catch (PracticeCsException $e) {
            throw LedgerWriteException::applicationFailed(
                $e->getMessage(),
                $e->getResponseBody()
            );
        }
    }

    /**
     * Validate payment data contains all required keys with correct types.
     *
     * @param  array  $paymentData  The payment data to validate
     *
     * @throws \InvalidArgumentException If validation fails
     */
    private function validatePaymentData(array $paymentData): void
    {
        $required = [
            'client_KEY' => 'integer',
            'staff_KEY' => 'integer',
            'bank_account_KEY' => 'integer',
            'ledger_type_KEY' => 'integer',
            'amount' => 'numeric',
            'reference' => 'string',
        ];

        $this->validateRequiredKeys($paymentData, $required, 'writePayment');
    }

    /**
     * Validate memo data contains all required keys with correct types.
     *
     * @param  array  $memoData  The memo data to validate
     * @param  string  $memoType  The type of memo ('credit' or 'debit')
     *
     * @throws \InvalidArgumentException If validation fails
     */
    private function validateMemoData(array $memoData, string $memoType): void
    {
        if (! in_array($memoType, ['credit', 'debit'], true)) {
            throw new \InvalidArgumentException(
                "writeMemo: memoType must be 'credit' or 'debit', got '{$memoType}'"
            );
        }

        $required = [
            'client_KEY' => 'integer',
            'staff_KEY' => 'integer',
            'bank_account_KEY' => 'integer',
            'amount' => 'numeric',
            'reference' => 'string',
        ];

        $this->validateRequiredKeys($memoData, $required, 'writeMemo');
    }

    /**
     * Validate deferred payment data contains all required keys with correct types.
     *
     * @param  array  $deferredData  The deferred payment data to validate
     *
     * @throws \InvalidArgumentException If validation fails
     */
    private function validateDeferredPaymentData(array $deferredData): void
    {
        if (! array_key_exists('payment', $deferredData) || ! is_array($deferredData['payment'])) {
            throw new \InvalidArgumentException(
                'writeDeferredPayment: missing or invalid required key "payment" (expected array)'
            );
        }

        $payment = $deferredData['payment'];

        $required = [
            'client_KEY' => 'integer',
            'staff_KEY' => 'integer',
            'bank_account_KEY' => 'integer',
            'amount' => 'numeric',
            'reference' => 'string',
        ];

        $this->validateRequiredKeys($payment, $required, 'writeDeferredPayment (payment)');
    }

    /**
     * Validate that required keys exist in data and have the correct types.
     *
     * @param  array  $data  The data array to validate
     * @param  array  $required  Map of key => expected type ('integer', 'numeric', 'string')
     * @param  string  $methodName  The calling method name for error messages
     *
     * @throws \InvalidArgumentException If a required key is missing or has wrong type
     */
    private function validateRequiredKeys(array $data, array $required, string $methodName): void
    {
        foreach ($required as $key => $type) {
            if (! array_key_exists($key, $data)) {
                throw new \InvalidArgumentException(
                    "{$methodName}: missing required key \"{$key}\""
                );
            }

            $valid = match ($type) {
                'integer' => is_int($data[$key]),
                'numeric' => is_numeric($data[$key]),
                'string' => is_string($data[$key]),
                default => true,
            };

            if (! $valid) {
                throw new \InvalidArgumentException(
                    "{$methodName}: key \"{$key}\" must be {$type}, got ".gettype($data[$key])
                );
            }
        }
    }
}
