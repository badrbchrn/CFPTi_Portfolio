<?php
session_start();

// Alert that will be returned on the home page
$alert = "";



function addMediaAndPost(){
    require_once('dbConn.php');

    // Post saving in DB
    $postInput = filter_input(INPUT_POST, 'postInput', FILTER_SANITIZE_STRING);
    $idPost = 0;

    $imageNumber = count(array_filter($_FILES['fileUploaded']['name']));

    //Calls functions that check the size and the type of the files submited
    $isFileSizeOk = checkFileSize($_FILES['fileUploaded'],$imageNumber);
    $isFileTypeOk = checkFileType($_FILES['fileUploaded'],$imageNumber);

    if(isset($_FILES['fileUploaded']))
    { 
        $directory = '../upload/img/';         

            if($isFileSizeOk){             
                
                if ($isFileTypeOk){                    

                    if(isset($postInput) && !empty($postInput)){
                        //Add Post
                        $sqlAddPost = "INSERT INTO posts (commentaire) VALUES (:post)";
                        $sqlGetLastIdPost = "SELECT idPost FROM posts WHERE commentaire='$postInput'";
                        $preparedExecPost = $conn->prepare($sqlAddPost);
                        $preparedExecPost->bindParam(':post',$postInput);
                        $preparedExecPost->execute();       
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
                        $alert = "An error has occured while adding the media or the post !";
                        break;
                    }
                }

            }else{
                $alert = "At least one of the files is too big !";
            }   
        }else{
            $alert = "At least one of the files is not an image !";
        }         
    }
    $_SESSION['alertMsg'] = $alert;
}

function checkFileType($files,$count){
    $isFileTypeOk = true;
    for ($i = 0; $i < $count; $i++){
        $file = mime_content_type($files['tmp_name'][$i]);
        if(strpos($file, 'image/') === false){
            $isFileTypeOk = false;
            break;
        }
    }
    return $isFileTypeOk;   
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



?>