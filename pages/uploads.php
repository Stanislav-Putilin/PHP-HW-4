<h1>Uploads</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['file'];
    dump($file);
    extract($file);

    if ($error === 4) {
        $_SESSION['infoMessage'] = ['Error required', 'danger'];
        redirect('uploads');
    }

    if ($error !== 0) {
        $_SESSION['infoMessage'] = ['Try agein', 'danger'];
        redirect('uploads');
    }

    $allowsTypes = ['image/gif','image/png','image/jpeg','image/avif'];

    if(!in_array($type, $allowsTypes))
    {
        $_SESSION['infoMessage'] = ['Not allows types file', 'danger'];
        redirect('uploads');
    }

    $fName = uniqid() . '_' . $name;

    $dir = 'uploads-img';

    if(!file_exists($dir))
    {
        mkdir($dir);
    }

    move_uploaded_file($tmp_name, $dir . '/' . $fName);
    
    resizeImage($dir . '/' . $fName, 100, true);
    resizeImage($dir . '/' . $fName, 300, false);

    $_SESSION['infoMessage'] = ['Ok', 'success'];
    redirect('uploads');
}

function resizeImage(string $path, int $size, bool $crop)
{
    $watermarkImagePath = 'images/watermark.png';
    $src = imagecreatefrompng($path);
    list($src_width, $src_height) = getimagesize($path);

    if($crop)
    {
        $dest = imagecreatetruecolor($size, $size);

        if($src_width > $src_height)
        {
            imagecopyresampled($dest, $src, 0, 0, round($src_width / 2 - $src_height / 2), 0, $size, $size, $src_height, $src_height);        
        }
        else
        {
            imagecopyresampled($dest, $src, 0, 0, 0, round($src_height / 2 - $src_width / 2), $size, $size, $src_width, $src_width);
        }
    }
    else
    {
        $dest_width = $size;
        $dest_heigst = round($size * $src_height / $src_width);
        $dest = imagecreatetruecolor($dest_width, $dest_heigst);
        imagecopyresampled($dest, $src, 0, 0, 0, 0, $dest_width, $dest_heigst, $src_width, $src_height);
    }

    $watermarkImage = imagecreatefrompng($watermarkImagePath);
    $watermarkImageInfo = getimagesize($watermarkImagePath);   

    $watermarkWidth = $watermarkImageInfo[0];
    $watermarkHeight = $watermarkImageInfo[1];

    $destWidth = imagesx($dest);
    $destHeight = imagesy($dest);

    $x = $destWidth - $watermarkWidth - 10;
    $y = $destHeight - $watermarkHeight - 10;    

    imagecopy($dest, $watermarkImage, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);

    imagepng($dest, 'uploads-img/1.png');
}
?>

<?php
if (isset($_SESSION['infoMessage'])) {
    list($text, $type) = $_SESSION['infoMessage'];
    echo "<div class='text-$type'>$text</div>";
    unset($_SESSION['infoMessage']);
}
?>

<form action="/uploads" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <button>Submit</button>
</form>