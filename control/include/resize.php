<?

function resize($source,$resize_width,$resize_height,$target){
// File and new size
//$source = '1.jpg';
//$percent = 1;
$quality=75;
//$target='1_main.jpg';
// Content type
//header('Content-type: image/jpeg');

// Get new sizes
list($width, $height) = getimagesize($source);
//$newwidth = $width * $percent;
//$newheight = $height * $percent;

$newwidth = $resize_width;
$newheight = $resize_height;

// Load
$thumb = imagecreatetruecolor($newwidth, $newheight);
$source = imagecreatefromjpeg($source);

// Resize
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Output
//imagejpeg($thumb);
imagegif($thumb,$target, $quality);
}
	function ShowPictureByRatio($ImageName,$ImagePath,$ImageSize,$ImageBorder=0,$ImgBorderColor="",$BlankImgWithPath="images/NoImage.gif")
	{
		if($ImageName=="")
			$ImageNameWithPath=$BlankImgWithPath;
		else
			$ImageNameWithPath=$ImagePath."/".$ImageName;
		if (file_exists($ImageNameWithPath)) 
		{
			$ImageHeightWidth = GetImageSize($ImageNameWithPath);
			$Height = $ImageHeightWidth[1];
			$Width = $ImageHeightWidth[0];

			if($Height>$ImageSize) 
			{
				$multiplier=$ImageSize/$Height;
				$Width = $Width*$multiplier;
				$Height = $Height*$multiplier;
			}
			return "<img width='$Width' height='$Height' src='$ImageNameWithPath' border='$ImageBorder' style='border-color: $ImgBorderColor'>";
		}
		else
			return false;
	}

?>