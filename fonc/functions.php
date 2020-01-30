<?php
session_start();

// Alert that will be returned on the home page
$alert = "";

function addMediaAndPost(){
    $user = 'root';
    $pass = 'Super2019';
    $conn = new PDO('mysql:host=localhost;dbname=fb_database', $user, $pass);

    // Post saving in DB
    $postInput = filter_input(INPUT_POST, 'postInput', FILTER_SANITIZE_STRING);
    $idPost = 0;      


    if(isset($_FILES['fileUploaded']))
    { 
         $directory = 'uploadImg/';
         $file = basename($_FILES['fileUploaded']['name'][0]);
         if($_FILES['fileUploaded']['size'][0] < 3145728){
             
            if(move_uploaded_file($_FILES['fileUploaded']['tmp_name'][0], $directory . $file))
            {
                if(isset($postInput) && !empty($postInput)){
                    //Add Post
                    $sqlAddPost = "INSERT INTO posts (commentaire) VALUES (:post)";
                    $sqlGetLastIdPost = "SELECT idPost FROM posts WHERE commentaire='$postInput'";
                    $preparedExecPost = $conn->prepare($sqlAddPost);
                    $preparedExecPost->bindParam(':post',$postInput);
                    $preparedExecPost->execute();        
            
                    // Media saving in DB 
                    if (isset($_FILES['fileUploaded']['name'][0])){
                        $sqlAddMedia = "INSERT INTO medias (typeMedia, nomMedia, idPost) VALUES (:type, :name, :idPost)";
                        $fileType = $_FILES['fileUploaded']['type'][0];
                        $fileName = $_FILES['fileUploaded']['name'][0];            
                        //Get last last inserted id with the same msg
                        foreach ($conn->query($sqlGetLastIdPost) as $row){
                            $idPost = $row;
                        }
                        $preparedExecMedia = $conn->prepare($sqlAddMedia);
                        $preparedExecMedia->bindParam(':type', $fileType);
                        $preparedExecMedia->bindParam(':name', $fileName);
                        $preparedExecMedia->bindParam(':idPost',$idPost[0]);
                        $preparedExecMedia->execute();
            
                        $alert = "The media and the most added succesfully !";
                    }        
                } 
            }else{
                $alert = "An error has been found while adding the media or the post !";
            }
         }      
    }else{
        $alert = "The file uploaded is too big !!";
    }   

    $_SESSION['alertMsg'] = $alert;
}
?>