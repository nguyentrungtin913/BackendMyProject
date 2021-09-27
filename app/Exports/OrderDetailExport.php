<?php

namespace App\Exports;

use App\Models\OrderDetail;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderDetailExport implements FromCollection
{
    public function collection()
    {
        return OrderDetail::all();
    }
}