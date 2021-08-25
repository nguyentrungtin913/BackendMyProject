<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add Product Type</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    
 
</head>
<body>
	
<form style="width: 30%; margin: auto;" action="{{URL::to('/update-product-type/'.$productType->type_id)}}" method="post">
	  @csrf
	<h1>Edit Product Type</h1>
  <div class="mb-3">
    <label class="form-label">Old Type Name</label>
    <input type="text" class="form-control" value="{{$productType->type_name}}" readonly>
    
  </div>
  <div class="mb-3">
 		<label class="form-label">New Type Name</label>
	  <input type="text" class="form-control" name="name">
	</div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
</html>