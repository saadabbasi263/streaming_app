<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\User as User;
use App\Models\QRCodes as QRCodes;
use App\Models\Template as Template;
use Validator;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Support\Facades\File; 
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use DB;
class QRController extends Controller
{

    public function QRgenerate(Request $request)
    {
       
        $rules = [
            'data' => 'required',
      
 
        ];

$validator = Validator::make($request->all(), $rules);
if ($validator-> fails()) {
    return responseValidationError('Fields Validation Failed.', $validator->errors());
} 
try{
    $path = public_path().'/images';
    File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
    $time = date('His');
    $name='QR-'.$time.'.svg';
    $userId = Auth::id();
$data=json_decode($request->getContent(),true);

$data=$data['data'];

if(is_array($data))
{
$data=json_encode($data);
}
$key = Str::random(10);

$qr=QrCode::size(300)->generate(''.env('APP_URL').'/api/view-qr/'.$key,public_path('images/'.$name) );


$ins_data=QRCodes::insertGetId([
    'user_id' => $userId,
    'data' => $data,
    'key' => $key,
    'path' => env('APP_URL').'/images'.'/'.$name
]);

return response()->json([
    "code" => 200,
    "path"=>  env('APP_URL').'/images'.'/'.$name,  
    "message" => "QR Generated Successfully!"
]);
}
catch (\Throwable $th) {
    return response()->json([
        "code" => 500,
        'status' => "error",
        "message" => "Internal Server Error",
        'error' => $th->getMessage()
    ]);
    
    }

}
public function QRview(Request $request,$key)
{
   
$validator = Validator::make(['key' => $key],[

'key' => 'required|string|min:1|exists:qrcodes,key',
]);

if ($validator->fails()) {
return responseValidationError('Fields Validation Failed.', $validator->errors());
}
try
{
   
    $getData=QRCodes::where('key',$key)->where('status',1)->pluck('data')->first();
    if(!is_null(json_decode($getData)))
    {
   $getData=json_decode($getData);
    }
    return response()->json([
    $getData
    ]);
}

catch (\Throwable $th) {
    return response()->json([
        "code" => 500,
        'status' => "error",
        "message" => "Internal Server Error",
        'error' => $th->getMessage()
    ]);
    
    }

}

public function getTemplates(Request $request)
{

try{
$get_Template=Template::get(['id','front','back'])->toArray();
$path=env('APP_URL').'/';
return response()->json([
    "code" => 200,
    "path" => $path,
    "data"=>  $get_Template,   
    "message" => "User Registered!"
]);
}
catch (\Throwable $th) {
    return response()->json([
        "code" => 500,
        'status' => "error",
        "message" => "Internal Server Error",
        'error' => $th->getMessage()
    ]);
    
    }

}


}