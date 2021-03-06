<?php
session_start();
require_once('dbConn.php');
// Alert that will be returned on the home page
$alert = "";
global $conn;
global $getFileType;
function addMediaAndPost(){
    global $conn;
    echo 'adding media and post';
    // Post saving in DB
    $postInput = filter_input(INPUT_POST, 'postInput', FILTER_SANITIZE_STRING);
    $idPost = 0;
 

    if(isset($_FILES['fileUploaded']))
    {   
        $imageNumber = count(array_filter($_FILES['fileUploaded']['name']));

        //Calls functions that check the size and the type of the files submited
        $isFileSizeOk = checkFileSize($_FILES['fileUploaded'],$imageNumber);

        $getFileType = getFileType($_FILES['fileUploaded'],$imageNumber);   
        
        var_dump($_FILES['fileUploaded']);            

        if ($getFileType == "image"){
            $directory = '../upload/img/';
            uploadMedia($directory, $postInput, $imageNumber);
        }else if ($getFileType == "video"){
            $directory = '../upload/video/';
            uploadMedia($directory, $postInput, $imageNumber);
        }else if ($getFileType == "audio"){
            $directory = '../upload/sound/';
            uploadMedia($directory, $postInput, $imageNumber);
        }  
        else {
            $alert = "The specified media is not handled !";
        }         
    } 
    
}

function uploadMedia($directory, $postInput, $imageNumber){
    global $conn;
    $conn->beginTransaction();

    if(isset($postInput) && !empty($postInput)){
        //Add Post
        $sqlAddPost = "INSERT INTO posts (commentaire) VALUES (:post)";
        $sqlGetLastIdPost = "SELECT idPost FROM posts WHERE commentaire='$postInput'";
        $preparedExecPost = $conn->prepare($sqlAddPost);
        $preparedExecPost->bindParam(':post',$postInput);
        $preparedExecPost->execute();       
        $conn->commit();
    }

    //Get last last inserted id with the same msg
    foreach ($conn->query($sqlGetLastIdPost) as $row){
        $idPost = $row;
    } 

    for($i=0 ; $i < $imageNumber; $i++){
        $fileName = basename(uniqid('',true) . $_FILES['fileUploaded']['name'][$i]);
        if(move_uploaded_file($_FILES['fileUploaded']['tmp_name'][$i], $directory . $fileName)){        

            // Media saving in DB                     
            $sqlAddMedia = "INSERT INTO medias (typeMedia, nomMedia, idPost) VALUES (:type, :name, :idPost)";
            $fileType = $_FILES['fileUploaded']['type'][$i];

            $preparedExecMedia = $conn->prepare($sqlAddMedia);
            $preparedExecMedia->bindParam(':type', $fileType);
            $preparedExecMedia->bindParam(':name', $fileName);
            $preparedExecMedia->bindParam(':idPost',$idPost[0]);
            $preparedExecMedia->execute();   
            $alert = "The media and the post added succesfully !";                      
             
        }else{
            $alert = "An errm or has occured while adding the media or the post !";
            $conn->rollBack();
            return;
        }
    }
    $_SESSION['alertMsg'] = $alert;
}

function getFileType($files,$count){
    for ($i = 0; $i < $count; $i++){        
        $file = mime_content_type($files['tmp_name'][$i]);
        echo "<br> File :" . $file;

        if(strpos($file, "image/") !== false){
            $getFileType = "image";
        }else if (strpos($file, 'video/') !== false){
            $getFileType = "video";
        }else if (strpos($file, 'audio/') !== false){
            $getFileType = "audio";
        }
    }
    return $getFileType;   
}

function checkFileSize($file,$count){
    $isFileSizeOk = true;
    for ($i = 0; $i < $count; $i++){
        if($file['size'][0] > 3145728){
            $isFileSizeOk = false;
        break;
        }
    }
    return $isFileSizeOk;
}

function showAllImages(){
    global $conn;
	$_SESSION['imgNames'] = array();
    $sqlGetAllImagesName = "SELECT  posts.idPost,commentaire,nomMedia,posts.creationDate,typeMedia FROM posts LEFT JOIN medias ON posts.idPost = medias.idPost";
	$arrayDatas = array();
    $i = 0;
    foreach ($conn->query($sqlGetAllImagesName) as $row){
        $arrayDatas[$i] = $row;
        $i++;                
    }	

	for($i = 0; $i < count($arrayDatas);$i++){               
        echo '<div class="modal-content" style="margin-bottom: 20px ;width:100%;">';
        echo "<table style='margin-left:1%;margin-top:1%;'><tr>";
        echo '<div class="modal-body" >';
        if (strpos($arrayDatas[$i][4],"image") !== false){
            echo '<td><img style="padding:3%;max-width:500px;border-radius:25px;" src="upload/img/'. $arrayDatas[$i][2] .'"/></td>';
        }else if (strpos($arrayDatas[$i][4],"video") !== false){
            echo '<td><video style="padding:3%;border-radius:25px;" width="500" controls autoplay loop>';
            echo '<source src="upload/video/' . $arrayDatas[$i][2] . '">';
            echo '</video></td>';
        } else if (strpos($arrayDatas[$i][4],"audio") !== false){
            echo '<td><video style="border-radius:5px;margin-left:3%;posistion:absolute;" width="400" controls>';
            echo '<source autoplay loop src="upload/sound/' . $arrayDatas[$i][2] . '"">';
            echo '</video></td>';
        }
        
        echo '<td><label>' . $arrayDatas[$i][1] . "</label></td>";        
        echo "<tr><td>";
        echo '<a style="padding-left:3%;padding-top:3%;" href="fonc/deleteUser.php?idPost=' . $arrayDatas[$i][0] . '&mediaType=' . $arrayDatas[$i][4] . '&mediaName=' . $arrayDatas[$i][2] . '">';
        echo '<i class="glyphicon glyphicon-trash" >Delete</i></a>';
        echo "<a href='index.php' style='padding-left:3%;'>";
        echo '<i class="glyphicon glyphicon-pencil" >Modify</i></a></td></tr>';        
        echo "</table>";
        echo '<div class="modal-footer" style="text-align:left;border-radius: 20px;">';
		echo 'Date de création : ' . $arrayDatas[$i][3] . '</div>';
        echo '</div>';  
	}
}
?>
