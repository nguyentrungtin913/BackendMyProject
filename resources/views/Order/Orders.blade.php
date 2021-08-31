<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>
<body>
@if(count($errors)>0 )
    <div class="alert alert-danger" role="alert">
        {{$errors['errors'][0]['msg']}}<br/>
        {{$errors['errors'][0]['clientMsg']}}
    </div>
@endif
<table class="table table-hover" style="text-align:center;">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Order Name</th>
        <th scope="col">Order Address</th>
        <th scope="col">Order Total</th>
        <th scope="col">Order Status</th>
        <th scope="col">Order Date</th>
        <th scope="col" colspan="3">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $key => $order)
        <form action="{{URL::to('/update')}}" method="post">
            <input name ="id" value="{{$order->order_id}}" hidden>
        <tr>
            <th scope="row">1</th>
            <td>{{$order->user->name}}</td>
            <td>{{$order->order_name}}</td>
            <td>{{$order->order_address}}</td>
            <td>{{$order->order_total}}</td>
            <td>
                <select name="status" id="{{$order->order_id.'key'}}" class="form-control" onchange="test({{$order->order_id}},{{$order->order_status}})">
                    <option value="0" @if($order->order_status==0) selected @endif>Chờ xác nhận</option>
                    <option value="1" @if($order->order_status==1) selected @endif">Đang chuẩn bị hàng</option>
                    <option value="2" @if($order->order_status==2) selected @endif>Đang giao</option>
                    <option value="3" @if($order->order_status==3) selected @endif>Đã giao</option>
                </select>
<!--                --><?php
//                switch($order->order_status){
//                    case 0:
//                        echo "Chờ xác nhận";
//                        break;
//                    case 1:
//                        echo "Đang chuẩn bị hàng";
//                        break;
//                    case 2:
//                        echo "Đang giao";
//                        break;
//                    case 3:
//                        echo "Đã giao";
//                        break;
//                }
//                ?>

            </td>
            <td>{{$order->order_date}}</td>
            <td><a href="" class="btn btn-outline-success">Detail</a></td>
            <td>@if($order->order_status == 0) <a href="{{URL::to('/delete-order/'.$order->order_id)}}" class="btn btn-outline-danger">Delete</a> @endif</td>
            <td><button id="{{$order->order_id}}" class="btn btn-outline-warning" style=" display:none;">Update</button></td>
        </tr>
        </form>

    @endforeach



    </tbody>
</table>
<form action="{{URL::to('/many-updates')}}" method="post">
    @csrf
    <button id="update" style=" display:none;" class="btn btn-outline-warning">Update (<span id="number">0</span>)</button>
    <textarea id="key" name="key" hidden></textarea>
    <textarea id="value" name="value" hidden></textarea>
</form>
<script>
    function test(id,value){
        var num = document.getElementById('number').innerHTML;
        var k = document.getElementById(id+'key');
        var option = k.value;
        var key = new Array(document.getElementById('key').innerHTML);
        var values= new Array(document.getElementById('value').innerHTML);

        if(option==value){
            document.getElementById(id).style.display = 'none';
            num--;
            key+='';
            key= key.split(",");
            var index = key.indexOf(String(id));
            if (index > -1) {
                key.splice(index, 1);
            }

            values+='';
            values= values.split(",");
            values.splice(index, 1);

        }else{
           if(document.getElementById(id).style.display !== "block"){
               num++;
               key.push(id);
               values.push(option);
           }else{
               key+='';
               key= key.split(",");

               values+='';
               values= values.split(",");

               var index = key.indexOf(String(id));
               if (index > -1) {
                   values[index]= option;
               }
           }
            document.getElementById(id).style.display = 'block';
        }

        if(num!==0)
        {
            document.getElementById("update").style.display = 'block';
        }else{
            document.getElementById("update").style.display = 'none';
        }

        document.getElementById('number').innerHTML = num;
        document.getElementById('key').innerHTML = key;
        document.getElementById('value').innerHTML = values;
    }

</script>
</body>
</html>
