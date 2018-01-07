<?php
namespace common\models;
use Yii;
use yii\base\Model;
use frontend\models\ProjectMedia;
use Exception;
 
class Storage extends Model
{
    private $aws;
    private $s3;
 
    function __construct() {
      $this->aws = Yii::$app->awssdk->getAwsSdk();
      $this->s3 = $this->aws->createS3();
    }
  
    public function browse($bucket='',$prefix='') {
        $result = $this->s3->listObjects(['Bucket' => $bucket,"Prefix" => $prefix])->toArray();
        foreach ($result as $r) {
            if (is_array($r)) {
              if (array_key_exists('statusCode',$r)) {
                  echo 'Effective URL: '.$r['effectiveUri'].'<br />';
              } else {
                foreach ($r as $item) {
                  echo $item['Key'].'<br />';
                }
              }
            } else {
                echo $r.'<br />';
              }
        }
    } 

  
    public function upload($bucket,$keyname,$filepath) {
          $result = $this->s3->putObject(array(
          'Bucket'       => $bucket,
          'Key'          => $keyname,
          'SourceFile'   => $filepath,
          'ContentType'  => 'text/plain',
          'ACL'          => 'public-read',
          'StorageClass' => 'REDUCED_REDUNDANCY',
          'Metadata'     => array(
              'param1' => 'value 1',
              'param2' => 'value 2'
          )
      ));
      return $result;
    }    
       
    public function download($bucket='',$key ='') {
        try{
           $file = $this->s3->getObject([
                'Bucket' => $bucket,
                'Key' => $key,
            ]);  
        }
        catch (Exception $e) {
           /* echo $e; 
            exit; */
            return false;
        } 
        return $file;
      // save it to disk
    }
    
    public function delete($bucket='',$key =''){
        $result = $this->s3->deleteObject(array(
            'Bucket' => $bucket,
            'Key'    => $key
        ));    
        return $result;
    }
    
    public static function imageUpload($projectImages, $lastInsertId) {

        $bucket = Yii::getAlias('@bucket');
        foreach ($projectImages as $image => $pic) {
            
            $ext = pathinfo($pic->name, PATHINFO_EXTENSION);
            $name = pathinfo($pic->name, PATHINFO_FILENAME);
            $projectImageFileName = $name . '_' . date("Ymdis") . '.' . $ext;
            
            $keyname = 'uploads/project_images/'.$lastInsertId.'/'.$projectImageFileName;
            $filepath = $pic->tempName; 
            
            $s3=new Storage();
            $s3->upload($bucket,$keyname,$filepath);
               
            // Content type
            header('Content-Type: image/jpeg');
            
            // Get new sizes        
            $newwidth = 200;
            $newheight = 200;

            // Load
            $thumb = imagecreatetruecolor($newwidth, $newheight);            
            $source = imagecreatefromstring(file_get_contents($filepath));
            
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, imagesx($source), imagesy($source));
            
            $newFileName = tempnam("/tmp", "tmp0"); 
            imagepng($thumb, $newFileName, 9);
           // chmod($newFileName, 0777);
                        
            $uploadPath = 'uploads/project_images/'.$lastInsertId.'/thumb/'.$projectImageFileName;
           
            if($s3->upload($bucket,$uploadPath,$newFileName)){
                $projectMedia = new ProjectMedia();
                $projectMedia->project_ref_id = $lastInsertId;
                $projectMedia->document_name = $projectImageFileName;
                $projectMedia->document_type = 'projectImage';
                $projectMedia->save();
            } else {
                die('Image upload failed');
            } 
           // unlink($newFileName);
        }
        return true;
    }
    
    public static function mediaUpload($projectMediaFiles, $lastInsertId) {

        $bucket = Yii::getAlias('@bucket');;
        foreach ($projectMediaFiles as $image => $val) {
            
            $ext = pathinfo($val->name, PATHINFO_EXTENSION);
            $name = pathinfo($val->name, PATHINFO_FILENAME);
            $projectMediaFileName = $name . '_' . date("Ymdis") . '.' . $ext;
            
            $keyname = 'uploads/project_images/'.$lastInsertId.'/'.$projectMediaFileName;
            $filepath = $val->tempName; 
            
            $s3=new Storage();
           
            if($s3->upload($bucket,$keyname,$filepath)){
                $projectMedia = new ProjectMedia();
                $projectMedia->project_ref_id = $lastInsertId;
                $projectMedia->document_name = $projectMediaFileName;
                $projectMedia->document_type = 'projectDocument';
                $projectMedia->save();
            } else {
                die('Document upload failed');
            }            
        }
        return true;
    }
    
    

}