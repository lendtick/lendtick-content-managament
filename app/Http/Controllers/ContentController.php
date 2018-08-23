<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\RestCurl;
use App\Helpers\Api;
use App\Helpers\PutImage;
use Illuminate\Support\Facades\Mail;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\ServiceException;
use App\Helpers\AzureStorageService;

use App\Repositories\ContentRepo;


class ContentController  extends Controller
{ 

    public function __construct(ContentRepo $ContentRepo)
    {
        $this->ContentRepo = $ContentRepo;
    }


    /**
    * @SWG\Post(
    *     path="/content", 
    *     description="Upload content to azure blob storage",
    *     operationId="auth",
    *     consumes={"application/json"},
    *     produces={"application/json"}, 
    *     @SWG\Parameter(
    *         description="Path",
    *         in="query",
    *         name="path",
    *         required=true,
    *         type="string"
    *     ), 
    *     @SWG\Response(
    *         response="200",
    *         description="successful"
    *     ),
    *     summary="Content Management",
    *     tags={
    *         "Content Management"
    *     }
    * )
    * */

     public function index(Request $request){

         try {

            $this->validate($request, [
                'path'            => 'required'
            ]);   

            $Url = $request->path;
            $ImageName = time().'.jpg';

            $ResultPut = PutImage::save($Url, $ImageName);
            if($ResultPut) $Content = base_path().'/public/'.$ImageName;  

            $Endpoint       = env('BLOB_DEFAULT_ENDPOINTS_PROTOCOL');
            $AccountName    = env('BLOB_ACCOUNT_NAME');
            $AccountKey     = env('BLOB_ACCOUNT_KEY');
            $Container      = env('BLOB_CONTAINER');

            $ConnectionString  = "DefaultEndpointsProtocol=".$Endpoint;
            $ConnectionString .= ";AccountName=".$AccountName;
            $ConnectionString .= ";AccountKey=".$AccountKey;

            $BlobClient  = BlobRestProxy::createBlobService($ConnectionString);

            $NewFileName = 'category1/'.md5('lutfiganteng3.png'.time()).'.png';
            $result = AzureStorageService::uploadBlobStorage($BlobClient, $Container, $Content, $NewFileName, null);

            if($result) $path = "$Endpoint://$AccountName.blob.core.windows.net/$Container/$NewFileName";

            $Status   = 1;
            $HttpCode = 200;
            $Data     = $path ? $path : '';  
            $Message = 'Berhasil';

        }catch(\Exception $e){
            $Status   = 0;
            $HttpCode = 400;
            $Data     = null;
            $Message = $e->getMessage();
        } 

        return response()->json(Api::format($Status, $Data, $Message), $HttpCode); 

    }

     public function promo(){
        try{
            $where = 'id_content_type';
            $value = 'CONT001';
            $status   = 1;
            $httpcode = 200;
            $data     = $this->ContentRepo->ByConditions($where , $value);
            $errorMsg = null;
        }catch(\Exception $e){
            $status   = 0;
            $httpcode = 400;
            $data     = null;
            $errorMsg = $e->getMessage();
        }
        return response()->json(Api::format($status, $data, $errorMsg), $httpcode);
    } 



     public function GetByCategory(Request $request,$ContentType){

         try { 
            $status   = 1;
            $httpcode = 200;
            $data     = null;  
            $errorMsg = 'Berhasil';

        }catch(\Exception $e){
            $status   = 0;
            $httpcode = 400;
            $data     = null;
            $errorMsg = $e->getMessage();
        } 

        return response()->json(Api::format($status, $data, $errorMsg), $httpcode); 

    }

}
