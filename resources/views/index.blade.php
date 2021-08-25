<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>
<body>
	<!-- {{Session::get('user_id')}} -->
	<a href="{{URL::to('/products')}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Product</a>
	<a href="{{URL::to('/product-types')}}" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">Product Type</a>
	<a href="{{URL::to('/mockups')}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Mockup</a>
	<a href="{{URL::to('/mockup-types')}}" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">Mockup Type</a>
	<a href="{{URL::to('/cart')}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Cart</a>
	<a href="{{URL::to('/order')}}" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">Order</a>
</body>
</html>