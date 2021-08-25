<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add Product Type</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    
 
</head>
<body>
	
@if(count($errors)>0 )
  <div class="alert alert-danger" role="alert">
    {{$errors['errors'][0]['msg']}}<br/>
    {{$errors['errors'][0]['clientMsg']}}
  </div>
@endif
	
<form style="width: 30%; margin: auto;" action="{{URL::to('/save-product-type')}}" method="post">
	  @csrf
	<h1>Add Product Type</h1>
  <div class="mb-3">
    <label class="form-label">Add Type Name</label>
    <input type="text" class="form-control" name="name" >
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
</html>