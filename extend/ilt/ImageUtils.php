<?php
/**
 * Created by PhpStorm.
 * User: Zing
 * Date: 2018/9/2
 * Time: 10:09
 */

namespace ilt;


class ImageUtils
{

    /**
     * 获取图片主要颜色
     * @param $image
     * @return array
     */
    public static function mainColor($image)
    {
        $imageInfo = getimagesize($image);
        $imgType   = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
        $imageFun  = 'imagecreatefrom' . ($imgType == 'jpg' ? 'jpeg' : $imgType);
        $i         = $imageFun($image);
        $rColorNum = $gColorNum = $bColorNum = $total = 0;
        for ($x = 50; $x < imagesx($i) - 50; $x++) {
            for ($y = 50; $y < imagesy($i) - 50; $y++) {
                $rgb = imagecolorat($i, $x, $y);
                $r   = ($rgb >> 16) & 0xFF;
                $g   = ($rgb >> 8) & 0xFF;
                $b   = $rgb & 0xFF;
                $rColorNum += $r;
                $gColorNum += $g;
                $bColorNum += $b;
                $total++;
            }
        }
        return [round($rColorNum / $total), round($gColorNum / $total), round($bColorNum / $total)];
    }
}