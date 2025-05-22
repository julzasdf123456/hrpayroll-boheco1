<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class TripTicketExport implements FromCollection
{
    protected $trip_ticket;

    public function __construct(string $id) {
        
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->trip_ticket;
    }
}
