<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    public $table = 'Bills';

    protected $connection = 'sqlsrv_billing';

    public $fillable = [
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

    protected $casts = [
        'ServicePeriodEnd' => 'datetime',
        'AccountNumber' => 'string',
        'PowerPreviousReading' => 'decimal:2',
        'PowerPresentReading' => 'decimal:2',
        'DemandPreviousReading' => 'float',
        'DemandPresentReading' => 'float',
        'AdditionalKWH' => 'float',
        'AdditionalKWDemand' => 'float',
        'PowerKWH' => 'decimal:2',
        'DemandKW' => 'float',
        'BillingDate' => 'datetime',
        'ServiceDateFrom' => 'datetime',
        'ServiceDateTo' => 'datetime',
        'DueDate' => 'datetime',
        'BillNumber' => 'string',
        'Remarks' => 'string',
        'AverageKWH' => 'float',
        'AverageKWDemand' => 'float',
        'CoreLoss' => 'float',
        'MeterNumber' => 'string',
        'ConsumerType' => 'string',
        'BillType' => 'string',
        'BillingPeriod' => 'datetime',
        'UnbundledTag' => 'boolean',
        'NetPrevReading' => 'decimal:2',
        'NetPresReading' => 'decimal:2',
        'NetPowerKWH' => 'decimal:2',
        'NetGenerationAmount' => 'decimal:2',
        'CreditKWH' => 'decimal:2',
        'CreditAmount' => 'decimal:2',
        'NetMeteringSystemAmt' => 'decimal:2',
        'DAA_GRAM' => 'decimal:2',
        'DAA_ICERA' => 'decimal:2',
        'ACRM_TAFPPCA' => 'decimal:2',
        'ACRM_TAFxA' => 'decimal:2',
        'DAA_VAT' => 'decimal:2',
        'ACRM_VAT' => 'decimal:2',
        'ReferenceNo' => 'string'
    ];

    public static array $rules = [
        'AccountNumber' => 'required|string|max:20',
        'PowerPreviousReading' => 'nullable|numeric',
        'PowerPresentReading' => 'nullable|numeric',
        'DemandPreviousReading' => 'nullable|numeric',
        'DemandPresentReading' => 'nullable|numeric',
        'AdditionalKWH' => 'nullable|numeric',
        'AdditionalKWDemand' => 'nullable|numeric',
        'PowerKWH' => 'nullable|numeric',
        'KWHAmount' => 'nullable',
        'DemandKW' => 'nullable|numeric',
        'KWAmount' => 'nullable',
        'Charges' => 'nullable',
        'Deductions' => 'nullable',
        'NetAmount' => 'nullable',
        'PowerRate' => 'nullable',
        'DemandRate' => 'nullable',
        'BillingDate' => 'nullable',
        'ServiceDateFrom' => 'nullable',
        'ServiceDateTo' => 'nullable',
        'DueDate' => 'nullable',
        'BillNumber' => 'nullable|string|max:10',
        'Remarks' => 'nullable|string|max:128',
        'AverageKWH' => 'nullable|numeric',
        'AverageKWDemand' => 'nullable|numeric',
        'CoreLoss' => 'nullable|numeric',
        'Meter' => 'nullable',
        'PR' => 'nullable',
        'SDW' => 'nullable',
        'Others' => 'nullable',
        'PPA' => 'nullable',
        'PPAAmount' => 'nullable',
        'BasicAmount' => 'nullable',
        'PRADiscount' => 'nullable',
        'PRAAmount' => 'nullable',
        'PPCADiscount' => 'nullable',
        'PPCAAmount' => 'nullable',
        'UCAmount' => 'nullable',
        'MeterNumber' => 'nullable|string|max:20',
        'ConsumerType' => 'nullable|string|max:20',
        'BillType' => 'nullable|string|max:10',
        'QCAmount' => 'nullable',
        'EPAmount' => 'nullable',
        'PCAmount' => 'nullable',
        'LoanCondonation' => 'nullable',
        'BillingPeriod' => 'nullable',
        'UnbundledTag' => 'nullable|boolean',
        'GenerationSystemAmt' => 'nullable',
        'FBHCAmt' => 'nullable',
        'FPCAAdjustmentAmt' => 'nullable',
        'ForexAdjustmentAmt' => 'nullable',
        'TransmissionDemandAmt' => 'nullable',
        'TransmissionSystemAmt' => 'nullable',
        'DistributionDemandAmt' => 'nullable',
        'DistributionSystemAmt' => 'nullable',
        'SupplyRetailCustomerAmt' => 'nullable',
        'SupplySystemAmt' => 'nullable',
        'MeteringRetailCustomerAmt' => 'nullable',
        'MeteringSystemAmt' => 'nullable',
        'SystemLossAmt' => 'nullable',
        'CrossSubsidyCreditAmt' => 'nullable',
        'MissionaryElectrificationAmt' => 'nullable',
        'EnvironmentalAmt' => 'nullable',
        'LifelineSubsidyAmt' => 'nullable',
        'Item1' => 'nullable',
        'Item2' => 'nullable',
        'Item3' => 'nullable',
        'Item4' => 'nullable',
        'SeniorCitizenDiscount' => 'nullable',
        'SeniorCitizenSubsidy' => 'nullable',
        'UCMERefund' => 'nullable',
        'NetPrevReading' => 'nullable|numeric',
        'NetPresReading' => 'nullable|numeric',
        'NetPowerKWH' => 'nullable|numeric',
        'NetGenerationAmount' => 'nullable|numeric',
        'CreditKWH' => 'nullable|numeric',
        'CreditAmount' => 'nullable|numeric',
        'NetMeteringSystemAmt' => 'nullable|numeric',
        'DAA_GRAM' => 'nullable|numeric',
        'DAA_ICERA' => 'nullable|numeric',
        'ACRM_TAFPPCA' => 'nullable|numeric',
        'ACRM_TAFxA' => 'nullable|numeric',
        'DAA_VAT' => 'nullable|numeric',
        'ACRM_VAT' => 'nullable|numeric',
        'NetMeteringNetAmount' => 'nullable',
        'ReferenceNo' => 'nullable|string|max:30'
    ];

    
}
