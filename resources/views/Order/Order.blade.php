<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>order</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>
<body>

	<table class="table table-hover" style="text-align:center;">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Order Name</th>
      <th scope="col">Order Address</th>
      <th scope="col">Order Total</th>
      <th scope="col">Order Status</th>
      <th scope="col">Order Date</th>
      <th scope="col" colspan="2">Action</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($orders as $key => $order)
  	<tr>
      <th scope="row">1</th>
      <td>{{$order->order_name}}</td>
      <td>{{$order->order_address}}</td>
      <td>{{$order->order_total}}</td>
      <td>
      	<?php
	    switch($order->order_status){
	    	case 0:
	    		echo "Chờ xác nhận";
	    		break;
	    	case 1:
	    		echo "Đang chuẩn bị hàng";
	    		break;
	    	case 2:
	    		echo "Đang giao";
	    		break;
	    	case 3:
	    		echo "Đã giao";
	    		break;
	    }
	    ?>

    </td>
      <td>{{$order->order_date}}</td>
      <td><a href="" class="btn btn-outline-success">Detail</a></td>
      <td>@if($order->order_status == 0) <a href="{{URL::to('/delete-order/'.$order->order_id)}}" class="btn btn-outline-danger">Delete</a> @endif</td>
    </tr>
    @endforeach



  </tbody>
</table>
</body>
</html>
