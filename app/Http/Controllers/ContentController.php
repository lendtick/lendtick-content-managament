<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PaymentTransactionRepo;
use App\Repositories\InvoiceHeaderRepo;
use App\Helpers\RestCurl;
use App\Helpers\Api;
use App\Helpers\PutImage;
use Illuminate\Support\Facades\Mail;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\ServiceException;
use App\Helpers\AzureStorageService;



class ContentController  extends Controller
{ 

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

        $status   = 1;
        $httpcode = 200;
        $data     = $path ? $path : '';  
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
