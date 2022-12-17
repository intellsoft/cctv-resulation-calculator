<?php
/*
 *
 *
 */

// Generate form token
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(64));
}
$token = $_SESSION['token'];


if (isset($_POST['submit']))
{
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$text="";
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "فایل بارگزاری شده عکس نیست.";
        $uploadOk = 0;
    }

    if(isset($_GET['token'])) {
        if ($_GET['token'] != $_SESSION['token']) {
            echo "خطا در کد اعتبار سنجی فرم اتفاق افتاده است.";
            $uploadOk = 0;
        }
    }

// Check if file already exists
if (file_exists($target_file)) {
    $text = "فایلی به این نام قبلا بارگزاری شده است.". "</br>";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 10485760) {
    $text .= "حجم فایل خیلی زیاد است.". "</br>";
    $uploadOk = 0;
}

$imageFileType = strtolower($imageFileType);

// Allow certain file formats
if( $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "bmp" ) {
    $text .= "فقط فایل عکس مورد قبول است.". "</br>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $text .= "فایل آپلود شده مورد قبول نیست.". "</br>";
    unlink($_FILES["fileToUpload"]["tmp_name"]);

// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // get uploaded image propertises
        list($width, $height, $type, $attr) = getimagesize($target_file);
        // calculate camera resulation
        $pixels = $width * $height;
        $megapixel = $pixels / 1000000;

        if($pixels < 1000000) // if pixel count is < 1000000, camera is analog
        {
            $tvl = ($width/4) *3;
            $text = "کیفیت تصویر دوربین شما : " . $tvl . " TVL است." . "</br>";
            $text .= "ابعاد تصویر دوربین برحسب پیکسل : " . $width . " * " . $height . " پیکسل" . "</br>";
            $text .= "تعداد پیکسل در کل تصویر : " . $pixels . "</br></br>";

        } else { // if pixel count is > 1000000, camera is megapixel
            $text = "کیفیت تصویر دوربین شما : " . $megapixel . " MP" . "</br>";
           $text .= "ابعاد تصویر دوربین برحسب پیکسل : " . $width . " * " . $height . " پیکسل" . "</br>";
           $text .= "تعداد پیکسل در کل تصویر : " . $pixels . "</br></br>";
        }


        // remove user uploaded file from server
        unlink($target_file);
    } else {
        echo "در آپلود تصویر مشکلی وجود دارد لطفا دوباره تلاش کنید.";
    }
}
}
?>
<!DOCTYPE html>
<html>
<body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
#outPopUp {
    position: absolute;
    width: 650px;
    height: 300px;
    z-index: 15;
    top: 20%;
    left: 20%;
    text-align: center;
    direction: rtl;
    font-family: vazir;
    color: black;
	
}

#tittle {
	background-color: #EF394E;
	font-size: 18px;
	color: white;
	border-radius: 8px;
	padding-top: 10px;
	padding-bottom: 10px;
}

#fileToUpload {
    border: 50px;
    margin: 10px;
    padding: 10px;
    font-size: 16px;
    background-color: #08C;
    border: none;
    border-radius: 8px;
    color: white;
}

#fileToUpload:hover {
	background-color: #0b7dda;
	-webkit-box-shadow:0px 1px 1px black;
 	-moz-box-shadow:0px 1px 1px black;
 	box-shadow:0px 1px 1px black;
	}

#Resolustion {
    padding: 12px;
    font-size: 16px;
    background-color: #08C;
    border: none;
    border-radius: 8px;
    color: white;
}

#Resolustion:hover {
	background-color: #0b7dda;
	-webkit-box-shadow:0px 1px 1px black;
 	-moz-box-shadow:0px 1px 1px black;
 	box-shadow:0px 1px 1px black;
	}


#txtresolustion {
    background-color: #EF394E;
    color: white;
    padding-top: 26px;
    padding-right: 25px;
    margin-bottom: 20px;
    border: none;
    border-radius: 8px;
    font-size: 20px;
    font-family: vazir;
	-webkit-box-shadow:0px 1px 1px black;
 	-moz-box-shadow:0px 1px 1px black;
 	box-shadow:0px 1px 1px black;
	font-weight: bold;
}

</style>
<div id="outPopUp">
<div id="tittle">
نرم افزار محاسبه کیفیت تصویر دوربین مداربسته
</div>
</br>
</br>
توضیح:</br>
یک اسکرین شات از تصویر دوربین مداربسته خود بگیرید و در نرم افزار آپلود کنید</br></br></br>

<form action="" method="post" enctype="multipart/form-data" id="Resolustionform">
    تصویر اسکرین شات دوربین را انتخاب کنید : (تصویر بعد از پردازش از سرور ما حذف میشود)
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="hidden" name="token" value="<?php echo $token; ?>" />
    <input id="Resolustion" type="submit" value="کیفیت تصویر دوربین من چه قدر است؟" name="submit">
	</br>
    <!--<label id="wait">تصویر در حال بارگزاری و محاسبه است. لطفا شکیبا باشید.</label>-->
</form>
</br></br>
<div id="txtresolustion">
<?php
if(isset($text))
echo $text;
?>
</div>
<?php echo ""; ?>
</div>
<!--
<script>
$(document).ready(function(){
    $("#wait").hide();
    $('#Resolustionform').on('submit', function(e){
        //e.preventDefault();      
        $("#Resolustion").prop("disabled",true);
        $("#wait").show();
		$('#Resolustionform').submit();
    });
});
</script>
-->
</body>
</html>