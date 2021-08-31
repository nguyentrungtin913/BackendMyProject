<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>mockup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>
<body style="width:100%">
<h2>Carts</h2>
<div>
    <h3 id="demo" style="float: left;display:none;">Choose: 0</h3>
    <select id = "action" class="form-control" style=" display:none;">
        <option value="0">Buy</option>
        <option value="1">Delete</option>
    </select>
</div>

<div style="width:100%;margin: auto;text-align: center;position: absolute">
    <?php
    $userId = Session::get('user_id') ?? 0;
    $folder=md5($userId);
    ?>
    @foreach($carts as $key => $cart)
        <div style="border: solid thin gray;float: left; margin:3% 0 0 3%; width: 20%;">

            <img src='{{URL::to("storage/app/public/cart/".$folder."/".$cart->cart_image."?nocache=".time())}}' height="190" width=100%>
            {{--<p>Price: {{number_format($cart->mockup->mockup_price,0)}} đ</p>--}}
            <!-- <p>Amount: <input style="width:20%" type="number" name="amount" value="1" min="1"></p> -->
            <div style="width: 100%;padding: auto;margin: 1%;text-align: center;" >
                <a href="{{URL::to('/create-order/'.$cart->cart_id)}}" class="btn btn-outline-success">Buy</a>
                <a href="{{URL::to('/delete-cart/'.$cart->cart_id)}}" class="btn btn-outline-danger">Delete</a>
            </div>
            <input type="checkbox" class="show" name="cart" value="{{$cart->cart_id}}">

        </div>
    @endforeach
</div>



<script language="javascript">
    var a_list = document.getElementsByClassName("show");
    for (var i = 0; i < a_list.length; i++){
        a_list[i].onclick = function()
        {
            var checkbox = document.getElementsByName('cart');
            var result = "";
            var num=0;
            // Lặp qua từng checkbox để lấy giá trị
            for (var i = 0; i < checkbox.length; i++){
                if (checkbox[i].checked === true){
                    result += '.' + checkbox[i].value;
                    num++;
                }
            }
            document.getElementById("demo").innerHTML = 'Choose: '+ num;
            if( document.getElementById("demo").innerHTML != 'Choose: 0')
            {
                document.getElementById("action").style.display = 'block';
                document.getElementById("demo").style.display = 'block';
            }
            else
            {
                document.getElementById("demo").style.display = 'none';
                document.getElementById("action").style.display = 'none';
            }

            document.getElementById("arr").innerHTML = result;
        };
    }
</script>
</body>

</html>
