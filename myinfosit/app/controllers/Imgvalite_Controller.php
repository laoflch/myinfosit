<?php                                                

//APP::import("Controller","NoModel");
App::import('Controller', "NoModelController",false);


class ImgvaliteController extends AppController implements NoModelController
{

	function index() {
		$this->autoRender=false;
		Header("Content-type: image/jpeg");
			
		$im = imagecreate(70,25);
		$black = ImageColorAllocate($im, 0,0,0);
			
		$white = ImageColorAllocate($im, 255,255,255);
			
		$red = imagecolorallocate($im,0xf3,0x61,0x61);
		$blue = imagecolorallocate($im,0x53,0x68,0xbd);
		$green = imagecolorallocate($im,0x6b,0xc1,0x46);
		$colors = array($red, $blue, $green);
		$gray2 = imagecolorallocate($im,0xf5,0xf5,0xf5);
		$gray = ImageColorAllocate($im, 180,180,180);
		//imagefill($im,0,0,$gray);
		imagefill($im,0,0,$gray2);
			
		imageline($im,rand(0,5),rand(6,18),rand(65,70),rand(6,18),$colors[rand(0,2)]);
		for($b=0;$b<4;$b++){
			$sed=rand(48,122);
			while(($sed>57&&$sed<65)||($sed>90&&$sed<97)){
				$sed=rand(48,122);
			};
			$sed=chr($sed);
			imagettftext($im, rand(12,14), (rand(0,60)+330)%360, 5+15*$b+rand(0,4), 15+rand(0,2), $colors[rand(0,2)], ROOT . DS . "app" . DS . "controllers" .  DS . "ariblk.ttf", $sed);
			$authnum .=$sed;
		}
			
			
			
		//imagestring($im, 10, 10, 2, $authnum, $white);
		for($i=0;$i<80;$i++)
		{
			$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
			imagesetpixel($im, rand()%70 , rand()%25 , $randcolor);
		}
			
		imagejpeg($im);
		ImageDestroy($im);
			
		$this->Session->write("valite",(string)$authnum);

	}

}
