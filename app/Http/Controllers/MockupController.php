<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mockup;
use App\Models\MockupType;
use App\Helpers\APIRender;
use App\Helpers\Random;
use App\Helpers\DataHelper;
use ImageMagick\MagickCoderErrorException;
use Illuminate\Support\Facades\Redirect;
use App\Transformers\MockupTransformer;

class MockupController extends Controller
{
    public function __construct(Mockup $mockup,MockupType $mockupType, APIRender $apiRender, Random $random, MockupTransformer $mockupTransformer)
    {
       $this->mockup= $mockup;
       $this->mockupType= $mockupType;
       $this->apiRender = $apiRender;
       $this->random = $random;
       $this->mockupTransformer = $mockupTransformer;
    }
    public function index(Request $request)
    {
        $params=$request->all();
        $perPage = $params['perPage'] ?? 0;
        $with = $params['with'] ?? [];
        
        $orderBy = $this->mockup->orderBy($params['sortBy'] ?? null, $params['sortType'] ?? null);

        $query = $this->mockup->filter($this->mockup::query(), $params)->orderBy($orderBy['sortBy'], $orderBy['sortType']);

        $query = $this->mockup->includes($query,$with);
        $data = DataHelper::getList($query, $this->mockupTransformer,$perPage,'List All Mockup');
        //$mockups= $this->mockupTransformer->transformCollection($query->get());
        return $data;

        //return view('Mockup.Mockup')->with(compact('mockups'));
    }
    public function show($mockupId)
    {
       $mockup = $this->mockup->find($mockupId);
       return view('Mockup.RenderMockup')->with(compact('mockup'));
    }
    public function render(Request $request,$mockupId)
    {
        $param= $request->all();
        $design=$param['image'] ?? null;

        $moc = $this->mockup->find($mockupId);
        $type = $moc->mockupType['type_name'];
        $side = $moc->mockup_side;
        $mockup= 'storage/app/public/mockup/'.$type.'/'.$moc->mockup_name;
        $array = explode("_",$moc->mockup_name);

        if($type == 'poster')
        {
            $type.= "_".$array[2];
        }else if($type == 'puzzle')
        {
            $arr = explode(".",$array[2]);
            $type.= "_".$arr[0];
        }elseif($type == 'petbowl')
        {
            $type .= "_".$array[1];
        }elseif($type == 'doormat')
        {
            $type.= "_".$array[4];
        }
               
        
        $render = $this->apiRender->render($type,$side,$mockup, $design);

        $image = $this->random->character(10);
        $path="./htdocs/MyProject/public/storage/app/public/cache/".$image.".jpg";

        $render->writeImages($path, true);
        $request->session()->put('image', $image);
        $request->session()->put('mockupId', $mockupId);
        return Redirect::to('/image-render');
        //echo $design."<br>".$mockup."<br>".$side."<br>".$type."<br>";
    }
    public function insert()
    {
        $mockupTypes= $this->mockupType->get();
        return view('Mockup.MockupAdd')->with(compact('mockupTypes'));
    }
    public function save(Request $request)
    {
        $param = $request->all();
        $mockupType=$this->mockupType->where('type_id',$param['type'])->first();
        $get_image = request('image');
        
        
        $new_image =  $param['name'].'.'.$get_image->getClientOriginalExtension();

        $get_image->move('storage/app/public/mockup/'.$mockupType->type_name.'/',$new_image);

        
        $mockup = new Mockup();
            $mockup->mockup_name =  $new_image;
            $mockup->mockup_type =  $param['type'];
            $mockup->mockup_side =  $param['side'];
        $mockup->save();
        return Redirect::to('/mockups')->with('success', 'Thêm mockup thành công !');
   
    }
    public function delete($mockupId)
    {
        $mockup = $this->mockup->find($mockupId);

        $mockupType=$this->mockupType->where('type_id',$mockup->mockup_type)->first();
        

        $destinationPath = 'storage/app/public/mockup/'.$mockupType->type_name.'/'.$mockup->mockup_name;
        //|| $destinationPath!='storage/app/public/mockup/'.$mockupType->type_name.'/'
        if (file_exists($destinationPath)){
            unlink($destinationPath);
        }
        $mockup->delete(); 

        return Redirect::to('/mockups')->with('success', 'xóa mockup thành công !');
    }
    public function edit($mockupId)
    {
         $mockup = $this->mockup->where('mockup_id',$mockupId)->first();
         $mockupTypes = $this->mockupType->get();
         return view('Mockup.MockupEdit')->with(compact('mockup','mockupTypes'));
    }
    public function update($mockupId, Request $request)
    {
        $param = $request->all(); 
        $mockup = $this->mockup->where('mockup_id',$mockupId)->first();

        $mockupType = $this->mockupType->where('type_id',$mockup->mockup_type)->first();
        $getImage = request('image');
        $typeNew = $param['type'];
        $typeOld = $mockup->mockup_type;

        $arr = explode(".",$param['name']);
        $name = $arr[0];

        $destinationPath = 'storage/app/public/mockup/'.$mockupType->type_name.'/'.$mockup->mockup_name;
        $mocType = $this->mockupType->where('type_id',$typeNew)->first();

        if(!empty($getImage))
        {
            
           // echo  $destinationPath;
            if (file_exists($destinationPath)){
                unlink($destinationPath);
            }
            $newImage=$name.'.'.$getImage->getClientOriginalExtension();
            //echo '<br>storage/app/public/mockup/'.$mocType->type_name.'/'.$newImage;
            $getImage->move('storage/app/public/mockup/'.$mocType->type_name.'/',$newImage);
            
            $mockup->mockup_name = $newImage;
            $mockup->mockup_type = $mocType->type_id;
        }else{
            $arr1 = explode(".",$mockup->mockup_name);
            $oldExtend = $arr1[1];
            $image = $name.'.'.$oldExtend;
            

            $newImage= 'storage/app/public/mockup/'.$mocType->type_name.'/'.$image;

            if (file_exists($destinationPath)){
                rename($destinationPath, $newImage);
            }
            if($typeNew != $typeOld)
            {
                $mockup->mockup_type = $mocType->type_id;
            }
            $mockup->mockup_name = $image;        
        }
        $mockup->mockup_side =  $param['side'];
        $mockup->save();
        return Redirect::to('/mockups')->with('success', 'Cập nhật mockup thành công !');
    }
    public function imageRender()
    {
        return view('Mockup.Render');
    }
}
