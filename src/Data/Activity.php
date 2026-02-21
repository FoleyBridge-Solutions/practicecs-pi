<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * Activity data object returned from the PracticeCS API.
 *
 * @property-read int $activityKey
 * @property-read string $activityId
 * @property-read string $description
 * @property-read string|null $longDescription
 * @property-read int|null $activityCategoryKey
 * @property-read int $activityClassKey
 * @property-read int $activityStatusKey
 * @property-read int|null $billingRateTypeKey
 * @property-read float $unitPrice
 * @property-read float $unitCost
 * @property-read bool $roundExtension
 * @property-read bool $chargeSalesTax
 * @property-read bool $chargeServiceTax
 * @property-read string|null $glAccount
 * @property-read int $rateTypeKey
 * @property-read int $activityMethodKey
 * @property-read int $activitySurchargeMethodKey
 * @property-read float $surchargeRate
 * @property-read string|null $createDateUtc
 * @property-read string|null $updateDateUtc
 * @property-read array|null $category
 */
class Activity
{
    public function __construct(
        public readonly int $activityKey,
        public readonly string $activityId,
        public readonly string $description,
        public readonly ?string $longDescription = null,
        public readonly ?int $activityCategoryKey = null,
        public readonly int $activityClassKey = 1,
        public readonly int $activityStatusKey = 1,
        public readonly ?int $billingRateTypeKey = null,
        public readonly float $unitPrice = 0.0,
        public readonly float $unitCost = 0.0,
        public readonly bool $roundExtension = false,
        public readonly bool $chargeSalesTax = false,
        public readonly bool $chargeServiceTax = false,
        public readonly ?string $glAccount = null,
        public readonly int $rateTypeKey = 1,
        public readonly int $activityMethodKey = 1,
        public readonly int $activitySurchargeMethodKey = 1,
        public readonly float $surchargeRate = 0.0,
        public readonly ?string $createDateUtc = null,
        public readonly ?string $updateDateUtc = null,
        public readonly ?array $category = null,
    ) {}

    /**
     * Create an Activity from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            activityKey: (int) $data['activity_KEY'],
            activityId: $data['activity_id'],
            description: $data['description'],
            longDescription: $data['long_description'] ?? null,
            activityCategoryKey: isset($data['activity_category_KEY']) ? (int) $data['activity_category_KEY'] : null,
            activityClassKey: (int) ($data['activity_class_KEY'] ?? 1),
            activityStatusKey: (int) ($data['activity_status_KEY'] ?? 1),
            billingRateTypeKey: isset($data['billing_rate_type_KEY']) ? (int) $data['billing_rate_type_KEY'] : null,
            unitPrice: (float) ($data['unit_price'] ?? 0),
            unitCost: (float) ($data['unit_cost'] ?? 0),
            roundExtension: (bool) ($data['round_extension'] ?? false),
            chargeSalesTax: (bool) ($data['charge_sales_tax'] ?? false),
            chargeServiceTax: (bool) ($data['charge_service_tax'] ?? false),
            glAccount: $data['gl_account'] ?? null,
            rateTypeKey: (int) ($data['rate_type_KEY'] ?? 1),
            activityMethodKey: (int) ($data['activity_method_KEY'] ?? 1),
            activitySurchargeMethodKey: (int) ($data['activity_surcharge_method_KEY'] ?? 1),
            surchargeRate: (float) ($data['surcharge_rate'] ?? 0),
            createDateUtc: $data['create_date_utc'] ?? null,
            updateDateUtc: $data['update_date_utc'] ?? null,
            category: $data['category'] ?? null,
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'activity_KEY' => $this->activityKey,
            'activity_id' => $this->activityId,
            'description' => $this->description,
            'long_description' => $this->longDescription,
            'activity_category_KEY' => $this->activityCategoryKey,
            'activity_class_KEY' => $this->activityClassKey,
            'activity_status_KEY' => $this->activityStatusKey,
            'billing_rate_type_KEY' => $this->billingRateTypeKey,
            'unit_price' => $this->unitPrice,
            'unit_cost' => $this->unitCost,
            'round_extension' => $this->roundExtension,
            'charge_sales_tax' => $this->chargeSalesTax,
            'charge_service_tax' => $this->chargeServiceTax,
            'gl_account' => $this->glAccount,
            'rate_type_KEY' => $this->rateTypeKey,
            'activity_method_KEY' => $this->activityMethodKey,
            'activity_surcharge_method_KEY' => $this->activitySurchargeMethodKey,
            'surcharge_rate' => $this->surchargeRate,
            'create_date_utc' => $this->createDateUtc,
            'update_date_utc' => $this->updateDateUtc,
            'category' => $this->category,
        ];
    }
}
