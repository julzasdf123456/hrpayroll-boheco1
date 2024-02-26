<?php

namespace App\Repositories;

use App\Models\Bills;
use App\Repositories\BaseRepository;

class BillsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'AccountNumber',
        'PowerPreviousReading',
        'PowerPresentReading',
        'DemandPreviousReading',
        'DemandPresentReading',
        'AdditionalKWH',
        'AdditionalKWDemand',
        'PowerKWH',
        'KWHAmount',
        'DemandKW',
        'KWAmount',
        'Charges',
        'Deductions',
        'NetAmount',
        'PowerRate',
        'DemandRate',
        'BillingDate',
        'ServiceDateFrom',
        'ServiceDateTo',
        'DueDate',
        'BillNumber',
        'Remarks',
        'AverageKWH',
        'AverageKWDemand',
        'CoreLoss',
        'Meter',
        'PR',
        'SDW',
        'Others',
        'PPA',
        'PPAAmount',
        'BasicAmount',
        'PRADiscount',
        'PRAAmount',
        'PPCADiscount',
        'PPCAAmount',
        'UCAmount',
        'MeterNumber',
        'ConsumerType',
        'BillType',
        'QCAmount',
        'EPAmount',
        'PCAmount',
        'LoanCondonation',
        'BillingPeriod',
        'UnbundledTag',
        'GenerationSystemAmt',
        'FBHCAmt',
        'FPCAAdjustmentAmt',
        'ForexAdjustmentAmt',
        'TransmissionDemandAmt',
        'TransmissionSystemAmt',
        'DistributionDemandAmt',
        'DistributionSystemAmt',
        'SupplyRetailCustomerAmt',
        'SupplySystemAmt',
        'MeteringRetailCustomerAmt',
        'MeteringSystemAmt',
        'SystemLossAmt',
        'CrossSubsidyCreditAmt',
        'MissionaryElectrificationAmt',
        'EnvironmentalAmt',
        'LifelineSubsidyAmt',
        'Item1',
        'Item2',
        'Item3',
        'Item4',
        'SeniorCitizenDiscount',
        'SeniorCitizenSubsidy',
        'UCMERefund',
        'NetPrevReading',
        'NetPresReading',
        'NetPowerKWH',
        'NetGenerationAmount',
        'CreditKWH',
        'CreditAmount',
        'NetMeteringSystemAmt',
        'DAA_GRAM',
        'DAA_ICERA',
        'ACRM_TAFPPCA',
        'ACRM_TAFxA',
        'DAA_VAT',
        'ACRM_VAT',
        'NetMeteringNetAmount',
        'ReferenceNo'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Bills::class;
    }
}
