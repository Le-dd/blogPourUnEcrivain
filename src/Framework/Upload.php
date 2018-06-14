<?php
namespace Framework;

use Psr\Http\Message\UploadedFileInterface;
use Intervention\Image\ImageManager;

class Upload {

  protected $path;

  protected $formats = [];


  public function __construct(?string $path = null)
  {
    if($path){
      $this->path = $path;
    }
  }

  public function upload(UploadedFileInterface $file, ?string $oldFile = null ): ?string
  {
    if($file->getError() === UPLOAD_ERR_OK){
    $this->delete($oldFile);
    $filename = $file->getClientFilename();
    $targetPath = $this->addSuffix($this->path . DIRECTORY_SEPARATOR . $filename);
    $dirname = pathinfo($targetPath, PATHINFO_DIRNAME);
    if (!file_exists($dirname)) {
      mkdir($dirname, 777, true);
    }
    $file->moveTo($targetPath);
    $this->generateFormats($targetPath);
    return pathinfo($targetPath)['basename'];
    }
    return null;
  }

  private function addSuffix(string $targetPath):string
  {
      if(file_exists($targetPath))
      {

        return $this->addSuffix($this->getPathWithSuffix($targetPath, 'copy'));
      }
      return $targetPath;
  }

  public function delete(?string $oldFile)
  {
    if($oldFile){
    $oldFile = $this->path . DIRECTORY_SEPARATOR . $oldFile;
      if (file_exists($oldFile)){
        unlink($oldFile);
      }
      foreach($this->formats as $format => $size) {
        $oldFileWithFormat = $this->getPathWithSuffix($oldFile,$format);
        if(file_exists($oldFileWithFormat)){
        unlink($oldFileWithFormat);
        }


      }
    }
  }

  private function getPathWithSuffix(string $path, string $suffix): string
  {
    $info = pathinfo($path);
    return $info['dirname'] . DIRECTORY_SEPARATOR .
    $info['filename'] . '_' . $suffix . '.' . $info['extension'];
  }

  private function generateFormats($targetPath)
  {
    foreach($this->formats as $format => $size) {

      $manager = new ImageManager(['driver' => 'gd']);
      $destination = $this->getPathWithSuffix($targetPath, $format);
      [$width , $height] = $size;
      $manager->make($targetPath)->fit($width, $height)->save($destination);

    }
  }




}
