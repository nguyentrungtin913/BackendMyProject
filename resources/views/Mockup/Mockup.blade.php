<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>mockup</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>
<body>
   <a href="{{URL::to('/create-mockup')}}">Add mockup</a>
    <div style="width:98%;margin: auto;text-align: center;">
         
      @foreach($mockups as $key => $mockup)
        <div style="border: solid thin gray;float: left; margin:3% 0 0 3%;">
            <a href="{{URL::to('/show-mockup/'.$mockup->mockup_id)}}">
                <img src='{{URL::to("storage/app/public/mockup/".$mockup->mockupType["type_name"]."/".$mockup->mockup_name."?nocache=".time())}}' height="170" width="220">
            </a>
            <p>{{number_format($mockup->mockup_price,0)}} đ</p>
            <div style="width: 100%;padding: auto;margin: 1%;text-align: center;" >
                <a href="{{URL::to('/show-mockup/'.$mockup->mockup_id)}}" class="btn btn-outline-success">Render</a>
                <a href="{{URL::to('/edit-mockup/'.$mockup->mockup_id)}}" class="btn btn-outline-warning">Edit</a>
                <a href="{{URL::to('/delete-mockup/'.$mockup->mockup_id)}}" class="btn btn-outline-danger">Delete</a>
            </div>
        </div>
        @endforeach
    </div>
    
</body>
</html>