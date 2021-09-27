<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Helpers\ReportHelper;
use Response;
use App\Exports\OrderDetailExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redirect;

class OrderDetailController extends Controller
{
    public function __construct(OrderDetail $orderDetail)
    {
       $this->orderDetail= $orderDetail;
    }

    public function exportDetailOrder(Request $request, Response $response)
    {
        //return Excel::store(new OrderDetailExport, 'invoices.xlsx', 's3');
        return Excel::download(new OrderDetailExport , 'orderDetail.xlsx');
    }    
}
