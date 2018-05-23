<?php

    class Image {
        protected $image;
        protected $image_type;
        protected $newWidth;
        protected $newHeight;
        
        public function load($filename) {
            $image_info = getimagesize($filename);
            $this -> image_type = $image_info[2];
            if ($this -> image_type == IMAGETYPE_JPEG) {
                $this -> image = imagecreatefromjpeg($filename);
            } elseif ($this -> image_type == IMAGETYPE_PNG) {
                $this -> image = imagecreatefrompng($filename);
            }
        }

        public function save($location, $compression = 100) {
            if ($this->image_type == IMAGETYPE_JPEG) {
                imagejpeg($this -> image, $location, $compression);
            } elseif ($this->image_type == IMAGETYPE_PNG) {
                imagepng($this -> image, $location);
            }
        }

        public function getWidth() {
            return imagesx($this -> image);
        }
    
        public  function getHeight() {
            return imagesy($this -> image);
        }
    
        public function resizeToHeight($height) {
            $ratio = $height / $this -> getHeight();
            $width = $this -> getWidth() * $ratio;
            $this -> resize($width, $height);
        }
    
        public function resizeToWidth($width) {
            $ratio = $width / $this -> getWidth();
            $height = $this -> getheight() * $ratio;
            $this -> resize($width, $height);
        }
    
        public function resize($width, $height) {
            $new_image = imagecreatetruecolor($width, $height);
            imagecopyresampled($new_image, $this -> image, 0, 0, 0, 0, $width, $height, $this -> getWidth(), $this -> getHeight());
            $this -> image = $new_image;
            $this->newWidth = $this->getWidth($this->image);
            $this->newHeight = $this->getHeight($this->image);
        }

        public function crop($resolution) {
            if($this->newWidth !== 400) {
                $x = ($this->newWidth - 400) / 2;
                $y = 0;
            } else {
                $x = 0;
                $y = ($this->newHeight - 400) / 2;
            }

            $tmpImage = imagecrop($this -> image, ['x' => $x, 'y' => $y, 'width' => $resolution, 'height' => $resolution]);
            if ($tmpImage !== FALSE) {
                $this -> image = $tmpImage;
            }
        }
    } 
?>