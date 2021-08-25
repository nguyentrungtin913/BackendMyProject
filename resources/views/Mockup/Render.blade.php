<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>render</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>
<body>
	@if(count($errors)>0 )
  <div class="alert alert-danger" role="alert">
    {{$errors['errors'][0]['msg']}}<br/>
    {{$errors['errors'][0]['clientMsg']}}
  </div>
@endif
	<form action="" method="post" style="width:50%;margin: auto;">
		<img style="width:70%" src='{{URL::to("storage/app/public/cache/".session("image").".jpg"."?nocache=".time())}}'>
		<br>
		 <div style="width: 100%;padding: auto;margin: 1%;text-align: center;" >
            <a href="{{URL::to('/create-cart')}}" class="btn btn-outline-success">Add Cart</a>
            <a href="{{URL::to('/')}}" class="btn btn-outline-warning">Buy</a>
        </div>
	</form>
</body>
</html>