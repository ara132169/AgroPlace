<?php
if (!class_exists('ImageOptimizer')) {
class ImageOptimizer {
    private $uploadDir;
    private $maxWidth = 1200;
    private $maxHeight = 800;
    private $quality = 85;
    
    public function __construct($uploadDir = 'uploads/') {
        $this->uploadDir = $uploadDir;
    }
    
    public function optimizeImage($sourceFile, $targetFile = null) {
        if (!$targetFile) {
            $targetFile = $sourceFile;
        }
        
        $imageInfo = getimagesize($sourceFile);
        if (!$imageInfo) return false;
        
        $sourceImage = $this->createImageFromFile($sourceFile, $imageInfo[2]);
        if (!$sourceImage) return false;
        
        $optimized = $this->resizeAndOptimize($sourceImage, $imageInfo[0], $imageInfo[1]);
        
        return $this->saveOptimizedImage($optimized, $targetFile, $imageInfo[2]);
    }
    
    private function createImageFromFile($file, $type) {
        switch ($type) {
            case IMAGETYPE_JPEG: return imagecreatefromjpeg($file);
            case IMAGETYPE_PNG: return imagecreatefrompng($file);
            case IMAGETYPE_WEBP: return imagecreatefromwebp($file);
            default: return false;
        }
    }
    
    private function resizeAndOptimize($source, $width, $height) {
        if ($width <= $this->maxWidth && $height <= $this->maxHeight) {
            return $source;
        }
        
        $ratio = min($this->maxWidth / $width, $this->maxHeight / $height);
        $newWidth = intval($width * $ratio);
        $newHeight = intval($height * $ratio);
        
        $optimized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($optimized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        return $optimized;
    }

    private function saveOptimizedImage($image, $file, $type) {
        switch ($type) {
            case IMAGETYPE_JPEG: return imagejpeg($image, $file, $this->quality);
            case IMAGETYPE_PNG: return imagepng($image, $file, 8);
            case IMAGETYPE_WEBP: return imagewebp($image, $file, $this->quality);
            default: return false;
        }
    }
}
}

if (!class_exists('CacheManager')) {
class CacheManager {
    private $cacheDir = 'cache/';
    private $defaultExpiration = 3600; // 1 hora
    
    public function get($key) {
        $file = $this->cacheDir . md5($key) . '.cache';
        if (file_exists($file) && (time() - filemtime($file)) < $this->defaultExpiration) {
            return unserialize(file_get_contents($file));
        }
        return false;
    }
    
    public function set($key, $data, $expiration = null) {
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
        
        $file = $this->cacheDir . md5($key) . '.cache';
        return file_put_contents($file, serialize($data));
    }
}
}
?>
