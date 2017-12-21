<?php
namespace App\Service;

use Slim\Http\UploadedFile;

class ImageUploadService {
  private $UPLOAD_DIR;
  public function __construct($upload_dir) {
    $this->UPLOAD_DIR = $upload_dir;
  }

  public function handleUpload($uploadedFile) {
    $directory = $this->UPLOAD_DIR;

    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
      $filename = $this->moveUploadedFile($directory, $uploadedFile);
      return $filename;
    }

  }
  private function moveUploadedFile($directory, UploadedFile $uploadedFile) {
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
  }
}


