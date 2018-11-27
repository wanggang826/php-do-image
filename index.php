<?php
//ini_set("display_errors","On");
//error_reporting(E_ALL);
include 'Image.php';
$Img = new Image();
$product_str_start = 'cb05-00000155'; $product_str_end = 'cb05-00000160';
$press = explode('-',$product_str_start)[0];
$product_sn_start = explode('-',$product_str_start)[1];
$product_sn_end = explode('-',$product_str_end)[1];
$count = $product_sn_end - $product_sn_start;
for ($i=0;$i<=$count;$i++){
    $product_sn = $press.'-'.substr($product_sn_start+$i+1000000,1,7);
    $Img->makeMergerImg($product_sn);
    $img_arr[$i] = 'upload/pin_code/'.$product_sn.'.png';
}
//$Img->makeCodeImg('dev2.lystrong.cn',$product_sn);
//$Img->makeImgWithStr('upload/sn_str_img/'.$product_sn.'.jpg',$product_sn);
//$Img->CompositeImage(['upload/product_qr_code/'.$product_sn.'.jpg','upload/sn_str_img/'.$product_sn.'.jpg'],'upload/pin_code/'.$product_sn.'.png');
//unlink('upload/sn_str_img/'.$product_sn.'.jpg');
//unlink('upload/product_qr_code/'.$product_sn.'.jpg');
$Img->makeZip('upload/pin_code-0007.zip',$img_arr);
$Img->download('upload/pin_code-0007.zip');