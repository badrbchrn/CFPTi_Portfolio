<?php
include_once('dbConn.php');
global $conn;
$error = 0;

$idPost = filter_input(INPUT_GET,'idPost',FILTER_SANITIZE_STRING);
$mediaName = filter_input(INPUT_GET,'mediaName',FILTER_SANITIZE_STRING);
$mediaType = filter_input(INPUT_GET,'mediaType',FILTER_SANITIZE_STRING);

$sqlDeletePost = "DELETE FROM posts WHERE idPost=:idPost";

$sqlPrepared = $conn->prepare($sqlDeletePost);

$sqlPrepared->bindParam(':idPost',$idPost);

//Delete media and post
$sqlPrepared->execute();
//Unlick the medias
if (strpos($mediaType,"image") !== false){
    unlink('../upload/img/' . $mediaName);
    echo "unlick -> img<br>";
}else if (strpos($mediaType,"video") !== false){
    unlink('../upload/video/' . $mediaName);
    echo "unlick -> video<br>";
} else if (strpos($mediaType,"audio") !== false){
    unlink('../upload/sound/' . $mediaName);
    echo "unlick -> sound<br>";
}else{
    $error = 1;
}

echo "IdPost : $idPost<br>";
echo "Media Name : $mediaName<br>";
echo "Media Type : $mediaType<br>";

echo $error;

if($error == 0){
   header("Location: ../index.php");
}


?>