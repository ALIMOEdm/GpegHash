class JpegHash {

    public static function getImageHash($path){
        $x_size = 200;
        $y_size = 200;

        if(!file_exists($path)){
            throw new Exception('File is not find');
        }

        $img = \imagecreatefromjpeg($path);
        $size = \getimagesize($path);
        if($x_size > $size[0] || $y_size > $size[1]){
            $x_size = $size[0] - (int)$size[0]/2;
            $y_size = $size[1] - (int)$size[1]/2;
        }
        $average = 0;
        $colormap = array();

        $zone = imagecreate($x_size, $y_size);
        imagecopyresized($zone, $img, 0, 0, 0, 0, $x_size, $y_size, $size[0], $size[1]);

        $img_t = $zone;

        for($x=0; $x<$x_size; $x++)
        {
            for($y=0; $y<$y_size; $y++)
            {
                $color = \imagecolorat($img_t, $x, $y);
                $color = \imagecolorsforindex($img_t, $color);
                $colormap[$x][$y]= 0.212671 * $color['red'] + 0.715160 * $color['green'] + 0.072169 * $color['blue'];
                $average += $colormap[$x][$y];
            }
        }
        $average /= $x_size*$y_size;
        $result = array();
        for($x=0; $x<$x_size; $x++)
        {
            for($y=0; $y<$y_size; $y++)
            {
                $result[]=($x<10?$x:chr($x+97)) . ($y<10?$y:chr($y+97)) . round(2*$colormap[$x][$y]/$average);
            }
        }

        $hash = md5(implode('', $result));

        return $hash;
    }
}
