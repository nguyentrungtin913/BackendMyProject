<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>mockup</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>
<body>
    
    <div style="width:98%;background-color: red;margin: auto;">
      @foreach($mockupTypes as $key => $mockupType)
        <div style="border: solid thin;float: left; margin:3% 0 0 2%;">
            <a href="{{URL::to('/mockup-type/'.$mockupType->type_id)}}" class="btn btn-outline-success">
                {{$mockupType->type_name}}
            </a>        
        </div>
        @endforeach
    </div>
    
</body>
</html>