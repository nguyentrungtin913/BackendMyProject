<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add Product</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    
 
</head>
<body>
<div style="width:30%; float: left;">
  <img id="showImage"
              style="text-align: center; width:300px; margin-top: 10px;"
             src="{{URL::to('storage/app/public/upload/products/'.$product['product_image'])}}" />
</div>
<form style="width: 30%; margin: auto;" enctype="multipart/form-data" 
		 action="{{URL::to('/update-product/'.$product['product_id'])}}" method="post">
	  @csrf
	<h1>Edit Product</h1>
  <div class="mb-3">
    <label class="form-label">Name Product</label>
    <input type="text" class="form-control" name="name" value="{{$product['product_name']}}">
  </div>

  <div class="mb-3">
    <label  class="form-label">Price</label>
    <input type="text" class="form-control" name="price" value="{{$product['product_price']}}">
  </div>

  <div class="mb-3">
    <label  class="form-label">Type</label>
    <select name = "type" class="form-control">
          <option>--Choose--</option>
      @foreach($productTypes as $key => $productType) 
          <option value="{{$productType->type_id}}" 
            @if(($productType->type_id)==($product['product_type'])) {{'selected'}} @endif>{{$productType->type_name}}</option>
      @endforeach
    </select>
  </div>

  <div class="mb-3">
    <label  class="form-label">Amount</label>
    <input type="number" step="100" class="form-control" name="amount" value="{{$product['product_amount']}}">
  </div>

  <div class="mb-3">
  	<label  class="form-label">Image</label>
 	  <input type="file" onchange="showMyImage(this)" accept="image/*" class="form-control" name="image" >
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
  
</form>

<script type="text/javascript">
    
    function showMyImage(fileInput) {
      var files = fileInput.files;
      for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var imageType = /image.*/;
        if (!file.type.match(imageType)) {
          continue;
        }
        var img = document.getElementById("showImage");
        img.file = file;
        var reader = new FileReader();
        reader.onload = (function(aImg) {
          return function(e) {
            aImg.src = e.target.result;
          };
        })(img);
        reader.readAsDataURL(file);
      }
    }
  </script>
</body>
</html>