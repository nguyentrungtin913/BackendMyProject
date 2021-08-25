<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>order</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>
<body>
	 <div style="width:100%;margin: auto;text-align: center;">
        <?php
            $userId = Session::get('user_id') ?? 0;
            $folder=md5($userId);
        ?>
        <form action="{{URL::to('/save-order')}}" method="post" style="width:70%;background-color: red;margin: auto;text-align: center;">
        	@csrf
        	<h1>Order</h1>
        	<div style="margin:auto;">
        		<label>Name Custommer </label>
        		<input type="text" name="name">
        	</div>
        	<div style="margin:auto;">
        		<label>Address Custommer </label>
        		<input type="text" name="address">
        	</div>
        	<input type="text" name="image" hidden value="{{$cart->cart_image}}">
			<div style="border: solid thin gray;float: left; margin:3% 0 0 3%; width: 16%;">
	            <img src='{{URL::to("storage/app/public/cart/".$folder."/".$cart->cart_image."?nocache=".time())}}' height="190" width=100%> 
	            <p>Price: {{number_format($cart->mockup->mockup_price,0)}} đ</p>
	            <p>Amount: <input style="width:20%" type="number" name="amount" value="1" min="1"></p>          
       		</div>
        <button>Buy</button>
        </form>
        
    </div>  
</body>
</html>