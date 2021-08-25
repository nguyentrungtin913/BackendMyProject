<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Product Type</title>
	
</head>
<body>
    <a href="{{URL::to('/create-product-type')}}">Add Product Type</a>
        <div>
            <table >
                    <tr>
                      
                        <th style="border: solid thin;">STT</th>
                        <th style="border: solid thin;">Type Name</th>
                        
                        <th colspan="2" style="border: solid thin;">Action</th>
                        
                        
                    </tr>

                    @foreach($productTypes as $key => $productType) 
                    <tr>
                        <td style="border: solid thin;">1</td>
                        <td style="border: solid thin;">{{ $productType->type_name }}</td>
                      
                        <td style="border: solid thin;"><a href="{{URL::to('/edit-product-type/'.$productType->type_id)}}">Edit</a></td>
                        <td style="border: solid thin;"><a href="{{URL::to('/delete-product-type/'.$productType->type_id)}}">Delete</a></td>
                    </tr>
                    @endforeach
            </table>
        </div>
       
</body>
</html>