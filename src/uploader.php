<?php

if(isset($_POST["submit_video"]) && isset($_FILES["video"])){
    $video = $_FILES["video"];
    $img = $_FILES["thumbnail"];
    $title = $_POST["title"];

    $accountId = $_SESSION['userId'];

    $videoName = $video["name"];
    $videoType = $video["type"];
    $videoTmpName = $video["tmp_name"];
    $videoError = $video["error"];
    $videoSaveName = uniqid();
    $videoNameSpliter = explode(".", $videoName);
    $videoExtention = strtolower(end($videoNameSpliter));

    $imgName = $img["name"];
    $imgType = $img["type"];
    $imgTmpName = $img["tmp_name"];
    $imgError = $img["error"];
    $imgSaveName = uniqid();
    $imgNameSplitter = explode(".", $imgName);
    $imgExtention = strtolower(end($imgNameSplitter));

    $genre = $_POST["genre"];

    $videoPath = "../public/assets/video/$videoSaveName.$videoExtention";
    $imgPath = "../public/assets/img/thumbnails/$imgSaveName.$imgExtention";

    if(validateUpload($title, $videoType, $videoError, $genre, $imgError, $imgType)){
        uploadVideo($videoTmpName, $videoPath, $accountId, $title, $imgTmpName, $imgPath, $genre);
    }
}

function uploadVideo($videoTmpName, $videoPath, $accountId, $title, $imgTmpName, $imgPath, $genre){
    $conn = dbConnect();
    
    // moves both files
    if(move_uploaded_file($videoTmpName, $videoPath)){
        if(move_uploaded_file($imgTmpName, $imgPath)){
            
            // inserts data into database
            $stmt = "INSERT INTO `film` (accountId, path, thumbnail, genreId, length, name)
            VALUES('$accountId', '$videoPath', '$imgPath', '$genre', '3', '$title')";
            mysqli_query($conn, $stmt);

            header("Location: index.php");
            die();
        }
    }
}

function validateUpload($title, $type, $error, $genre, $imgError, $imgType){
    global $lang;
    
    // checks for errors while uploading video's
    if($error !== 0){
        $fileError = "There was an issue with uploading your video, try again.";
        header("Location: uploadVideo.php?error=$fileError");
        die();
    }

    // checks if it is a video file
    $allowedExtentions = array("video/mp4", "video/webm", "video/avi", "video/flv");
    if(!in_array($type, $allowedExtentions)){
        $incType = "Make sure that you upload a .mp4, .webm, .avi or .flv video file.";
        header("Location: uploadVideo.php?error=$incType");
        die();
    }

    // checks if a tittle was filled in
    if(empty($title)){
        $noTitle = "Make sure to give your video a title.";
        header("Location: uploadVideo.php?error=$noTitle");
        die();
    }

    // checks if a genre was selected
    if(empty($genre)){
        $noGenre = "Make sure to select a genre.";
        header("Location: uploadVideo.php?error=$noGenre");
        die();
    }

    // checks for errors while uploading image's
    if($imgError !== 0){
        $imgError = "There was an issue with uploading you thumbnail, try again.";
        header("Location: uploadVideo.php?error=$imgError");
        die();
    }  

    // checks if it is a image file
    $allowedImgExt = array("image/png", "image/jpeg");
    if(!in_array($imgType, $allowedImgExt)){
        // sends error message's
        $incImgType = "Make sure to give upload a .png, .jpg image file.";
        header("Location: uploadVideo.php?error=$incImgType");
        die();
    }

    return true;
}