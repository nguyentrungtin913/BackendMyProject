<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>mockup</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>
<body>

    <form style="width: 30%; margin: auto;" enctype="multipart/form-data" action="{{URL::to('/render/'.$mockup->mockup_id)}}" method="post">
        csrf_field()
        <img src="{{URL::to('storage/app/public/mockup/'.$mockup->mockupType['type_name'].'/'.$mockup->mockup_name.'?nocache='.time())}}" height="400" >
        <div class="mb-3">
            <label  class="form-label">Image</label>
            <input type="file" onchange="showMyImage(this)" accept="image/*" class="form-control" name="image">
        </div>

        <button type="submit"  class="btn btn-outline-success">Submit</button>
    </form>


</body>
</html>
