<?php

namespace App\Repositories;

use App\Models\Loans;
use App\Repositories\BaseRepository;

class LoansRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'LoanFor',
        'LoanName',
        'LoanDescription',
        'InterestRate',
        'LoanAmount',
        'Terms',
        'TermUnit',
        'EmployeeId',
        'PaymentTerm'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Loans::class;
    }
}
