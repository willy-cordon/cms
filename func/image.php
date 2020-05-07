<?php
header('Content-Type: image/jpeg');

function redimensionar_imagen($imagenFUnc, $name, $xmax, $ymax)
{


    $filename = $imagenFUnc;
    $percent = 0.5;


    // Content type


    // Get new sizes
    list($width, $height) = getimagesize($filename);



    //validar si es vertical cuadradao u horizontal

    
    $newwidth = $width; //$width * $percent;
    $newheight = $height; //$height * $percent;
  
    if ($width >= $height) {
        $newheight = $width;
         $mayor = $height;
        // $pruba = 5;
    } else {
        $newwidth = $height;
         $mayor = $width;
        // $pruba = 5;
    }

    $img = imageCreateTrueColor($newwidth, $newheight);

    $ext = pathinfo($name, PATHINFO_EXTENSION);


    //Creo imagen en el directorio-------------------
    if ($ext == "jpg" || $ext == "jpeg")
        $source = imagecreatefromjpeg($imagenFUnc);
    elseif ($ext == "png")
        $source = imagecreatefrompng($imagenFUnc);
    elseif ($ext == "gif")
        $source = imagecreatefromgif($imagenFUnc);
    //----------------------------------------------
   

    $aux = $mayor /2;

    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            // pixel color at (x, y)
            $color = imagecolorat($source, $x, $y);
            imagesetpixel($img, round($x), round($y + $aux), $color);
        }
    }

    function resizeCrop($image, $width, $height, $displ = 'center')
    {
        /* Original dimensions */
        $origw = imagesx($image);
        $origh = imagesy($image);

        $ratiow = $width / $origw;
        $ratioh = $height / $origh;
        $ratio = max($ratioh, $ratiow); /* This time we want the bigger image */

        $neww = $origw * $ratio;
        $newh = $origh * $ratio;

        $cropw = $neww - $width;
        /* if ($cropw) */
        /*   $cropw/=2; */
        $croph = $newh - $height;
        /* if ($croph) */
        /*   $croph/=2; */

        if ($displ == 'center')
            $displ = 0.5;
        elseif ($displ == 'min')
            $displ = 0;
        elseif ($displ == 'max')
            $displ = 1;

        $new = imageCreateTrueColor($width, $height);

        imagecopyresampled($new, $image, -$cropw * $displ, -$croph * $displ, 0, 0, $width + $cropw, $height + $croph, $origw, $origh);
        //Ceo imagen mascara----------------------------------
        $mask = imagecreatetruecolor($width, $height);
        //----------------------------------------------------


        //creo color transparente//---------------------------
        $transparente = imagecolorallocate($mask, 255, 0, 0);
        imagecolortransparent($mask, $transparente);
        //----------------------------------------------------


        //Dibuja circulo--------------------------------------------------------------------------
        imagefilledellipse($mask, $width / 2, $height / 2, $width, $height, $transparente);
        //----------------------------------------------------------------------------------------

        //Creo color------------------------------------------
        $red = imagecolorallocate($mask, 0, 0, 0);
        //----------------------------------------------------

        //fusiona imagen---------------------------------------------------
        imagecopymerge($new, $mask, 0, 0, 0, 0, $width, $height, 100);
        imagecolortransparent($new, $red);
        //-----------------------------------------------------------------


        //rellena la imagen-----------------------------------
        imagefill($new, 0, 0, $red);
        //----------------------------------------------------


        //Liberamos memoria borrando la mascara---------------
        imagedestroy($mask);
        //----------------------------------------------------

        return $new;
    }

    // Output
    return resizeCrop($img, $xmax, $ymax, $displ = 'center');
    return $img;
    
}


$nombreimagen = "1.jpg";

imagejpeg(redimensionar_imagen($nombreimagen,$nombreimagen, 400, 400));
