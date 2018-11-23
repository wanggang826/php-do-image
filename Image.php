<?php
class Image {

	//生成二维码图片
	public function makeCodeImg($url,$product_sn='2018**2019'){
			$url = $url.'/'.$product_sn.'?code_sn='.$product_sn.'&code_type=product';
			$path = 'upload/product_qr_code';
			if (! is_dir($path)) {
				mkdir($path,0777,true);
			}
			include YUNLIAN_DIR_PATH .'lib/phpqrcode/phpqrcode.php';
			$value = $url;                  //二维码内容
			$errorCorrectionLevel = 'L';    //容错级别
			$matrixPointSize = 5;           //生成图片大小

			$filename = $path.'/'.$product_sn.'.jpg';
			QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);
			$QR = $filename;                //已经生成的原始二维码图片文件
			$QR = imagecreatefromstring(file_get_contents($QR));
			imagejpeg($QR,$product_sn.'jpg');
		}
	//文字生成图片
	public function makeImgWithStr($filename, $text, $font = 'static/font/Arial/ariblk.ttf'){
		//图片尺寸
		$im = imagecreatetruecolor(225, 45); 
		//背景色
		$white = imagecolorallocate($im, 255, 255, 255);  
		//字体颜色
		$black = imagecolorallocate($im, 0, 0, 0); 

		imagefilledrectangle($im, 0, 0, 399, 300, $white);
		//文字写入 
		imagettftext($im, 15, 0, 50, 30, $black, $font, $text);

		//图片保存
		ob_start();  
	  	imagejpeg($im); 
		$img = ob_get_contents();  
		ob_end_clean();  
		$size = strlen($img);  
		$fp2=@fopen($filename, "a");  
		fwrite($fp2,$img);  
		fclose($fp2);  
	}
	//图片加文字书印
	public function addTxetForImg($path,$text = '加棉',$size = '20'){
		//字体类型
	    $font = "static/font/Arial/simsun.ttc";

	    $img = imagecreatefromjpeg($path);// 加载已有图像
	    //给图片分配颜色
	    // imagecolorallocate($img, 0xff, 0xcc, 0xcc);
	    //设置字体颜色
		$black = imagecolorallocate($img, 255, 0, 0);
		//将ttf文字写到图片中
		imagettftext($img, $size, 0, 180, 255, $black, $font, html_entity_decode($text));
		// ImagePNG($img, "upload/documents/new".time().".jpg");
		imagejpeg($img, "upload/documents/new".time().".jpg");
	}	
	//合并图片,融合合并
	public function merageImg($file_1,$file_2,$re_file){
		// $path_1 = "upload/product_qr_code/cb05-000002.jpg";
		// $path_2 = "upload/product_qr_code/cb05-1311.jpg";
		//将两张图片分别取到两个画布中
		$image_1 = imagecreatefrompng($path_1);
		$image_2 = imagecreatefromjpeg($path_2);
		//创建一个和大图一样大小的真彩色画布（ps：只有这样才能保证后面copy装备图片的时候不会失真）
		$image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
		//为真彩色画布创建白色背景，再设置为透明
		$color = imagecolorallocate($image_3, 255, 255, 255);
		imagefill($image_3, 0, 0, $color);
		imageColorTransparent($image_3, $color);
		//首先将大图画布采样copy到真彩色画布中，不会失真
		imagecopyresampled($image_3,$image_1,0,0,0,0,imagesx($image_1),imagesy($image_1),imagesx($image_1),imagesy($image_1));
		//再将小图图片copy到已经具有人物图像的真彩色画布中，同样也不会失真
		imagecopymerge($image_3,$image_2, 150,150,0,0,imagesx($image_2),imagesy($image_2), 100);
		//将画布保存到指定的gif文件
		// imagegif($image_3);
		imagejpeg($image_3, $re_file.time().".jpg");
	}

	//合并图片,拼接合并
	public function splitImg(){

	}

	//生成压缩文件
	// 生成压缩zip文件 $file_name 最终生成的文件名,包含路径   $file_list,用来生成file_name的文件数组
	// makeZip('upload/product_qr_code/product_qr_code.zip',['upload/product_qr_code/cb01-000001-.jpg','upload/product_qr_code/cb01-000002-.jpg']);
	public function makeZip($file_name,$file_list){
		if(file_exists($file_name)){
			unlink($file_name);
		}
		//重新生成文件
		$zip=new ZipArchive();
		if($zip->open($file_name,ZIPARCHIVE::CREATE)!==TRUE){
			exit('无法打开文件，或者文件创建失败');
		}
		foreach($file_list as $val){
			if(file_exists($val)){
				$zip->addFile($val);
			}
		}
		$zip->close();//关闭
		if(!file_exists($file_name)){
			exit('无法找到文件'); //即使创建，仍有可能失败
		}
	}


}