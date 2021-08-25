<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Product</title>
	
</head>
<body>
    <a href="{{URL::to('/create-product')}}">Add Product</a>
        <div>
            <table >
                    <tr>
                      
                        <th style="border: solid thin;">Name</th>
                        <th style="border: solid thin;">Product Type</th>
                        <th style="border: solid thin;">Amount</th>
                        <th style="border: solid thin;">Price</th>
                        <th style="border: solid thin;">Image</th>
                        <th colspan="2" style="border: solid thin;">Action</th>
                        
                        
                    </tr>
                    @foreach($products as $key => $product)
                    <tr>
                        <td style="border: solid thin;">{{ $product->product_name }}</td>
                        <td style="border: solid thin;">{{ $product->productType['type_name']}}</td>
                        <td style="border: solid thin;">{{ $product->product_amount }}</td>
                       
                        <td style="border: solid thin;">{{ number_format($product->product_price,0) }}<span> VNĐ</span></td>
                         <td style="border: solid thin;"><img src="{{URL::to('storage/app/public/upload/products/'.$product->product_image)}}" height="100" width="100"></td>
                        <td style="border: solid thin;"><a href="{{URL::to('/edit-product/'.$product->product_id)}}">Edit</a></td>
                        <td style="border: solid thin;"><a href="{{URL::to('/delete-product/'.$product->product_id)}}">Delete</a></td>
                    </tr>
                    @endforeach
            </table>
        </div>
       
</body>
</html>