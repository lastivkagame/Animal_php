<?php require_once "connection_database.php"; ?>

<?php include "funtions.php"; ?>
<?php
$id = null;
$name = "";
$image = "";
$old_image = "";
$file_loading_error = [];
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = $_GET["id"];
    $command = $dbh->prepare("SELECT id, name, image FROM animals WHERE id = :id");
    $command->bindParam(':id', $id);
    $command->execute();
    $row = $command->fetch(PDO::FETCH_ASSOC);
    $name = $row['name'];
    $image = $row['image']; 
    $old_image = $row['image']; 
    //$image_to_show = $row['image']; //image_to_show
    //echo $old_image;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $image = $_POST['image'];
    $old_image = $_POST['old_image'];
   
    $id = $_POST['id'];
    $errors = [];
    //echo $old_image;
    if (empty($name)) {
        $errors["name"] = "Name is required";
      } else if (empty($image)) {
        $errors["image"] = "Image is required";
      }
    // } else if (empty($image)) {
    //     $stmt = $dbh->prepare("UPDATE animals SET name = :name, image = :image WHERE animals.id = :id;");
    //     $stmt->bindParam(':id', $id);
    //     $stmt->bindParam(':name', $name);
    //     $stmt->bindParam(':image', $image);
    //     $stmt->execute();
    //     header("Location: index.php");
    // } 
    else {
      //echo "some";
      //echo 'Welcome ' . $old_image ." - ". $image .')</script>';
      
      $stmt = $dbh->prepare("UPDATE animals SET name = :name, image = :image WHERE animals.id = :id;");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);

        if($old_image == $image)
        {
          $stmt->bindParam(':image', $image);
        }
        else
        {
          $image_name = uniqid() . ".png";
          base64_to_jpeg($image, "img/" . $image_name);
          $stmt->bindParam(':image', $image_name);
        }

        $stmt->execute();
        
        header("Location: index.php");

        // $target_dir = "uploads/";
        // $ext = pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);
        // $target_file = $target_dir . guidv4() . "." . $ext;
        // $uploadOk = 1;
        // $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
        // if (isset($_POST["submit"])) {
        //     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        //     if ($check !== false) {
        //         $uploadOk = 1;
        //     } else {
        //         array_push($file_loading_error, "File is not an image.");
        //         $uploadOk = 0;
        //     }
        // }

// Check file size
        // if ($_FILES["fileToUpload"]["size"] > 500000) {
        //     array_push($file_loading_error, "Sorry, your file is too large.");
        //     $uploadOk = 0;
        // }

// Allow certain file formats
        // if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        //     && $imageFileType != "gif") {
        //     array_push($file_loading_error, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        //     $uploadOk = 0;
        // }

// Check if $uploadOk is set to 0 by an error
//         if ($uploadOk == 0) {
//             array_push($file_loading_error, "Sorry, your file was not uploaded.");
// // if everything is ok, try to upload file
//         } else {
//             if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//                 $stmt = $dbh->prepare("UPDATE animals SET name = :name, image = :image WHERE animals.id = :id;");
//                 $stmt->bindParam(':id', $id);
//                 $stmt->bindParam(':name', $name);
//                 $stmt->bindParam(':image', $target_file);
//                 $stmt->execute();
//                 header("Location: index.php");
//                 exit;
//             } else {
//                 array_push($file_loading_error, "Sorry, there was an error uploading your file.");
//             }
//         }


    }
}
?>


<?php include "head.php"; ?>


<style>
 .preview{
   overflow: hidden;
   width: 200px !important;
   height: 200px !important;
   border-radius: 50%;
 }
</style>

<script>
    function updateAnimal() {
        $(`#name_error`).attr("hidden", true);
        var name = document.forms[`editAnimal`][`name`];
        if (name.value == '') {
            $(`#name_error`).attr("hidden", false);
            event.preventDefault()
        }
    }
</script>

<div class="container">
    <div class="p-3">
        <h2>Edit animal</h2>
        <form name="editAnimal" onsubmit="return updateAnimal();" method="post" enctype="multipart/form-data" >
            <?php
            if ($id != null)
                echo "<input name='id' value='$id' hidden>"
            ?>
            <section class="d-flex">
            <div class="d-flex flex-column form-group " >
  <!-- <img src="img/no-image.png" alt="Choose photo" class="" style="cursor: pointer;" width="250" id="imgSelect" /> -->
  <?php if(!empty($image)){
          echo "<img src='img/$image' alt='Choose photo' class='img-thumbnail' style='cursor: pointer;' width='350' id='imgSelect' />
          <input type='hidden' id='image' name='image' value='$image' />"; }
          else{
            echo "<img src='img/no-image.png' alt='Choose photo' class='' style='cursor: pointer;' width='350' id='imgSelect' />
            <input type='hidden' id='image' name='image' />
            ";
          }
          echo "<input type='hidden' id='old_image' name='old_image' value='$old_image' />";
           ?>
  
  <label class="form-check-label" style="color: blue" for="exampleRadios1">
    Click on image and choose Image 
  </label>
  <?php if(isset($errors['image']))
          echo "<small class='text-danger'>{$errors['image']}</small>" ?>
  </div>
            <div class="form-group" style="margin-left: 30px; width:100%;">
                <label for="exampleInputEmail1">Animal: </label>
                <?php
                echo "<input type='text' name='name' class='form-control' id='exampleInputEmail1'
                           placeholder='Enter animal name' style='width:100%;' value={$name}>"
                ?>

                <?php
                if (isset($errors['name']))
                    echo "<small class='text-danger'>{$errors['name']}</small>"
                ?>
                <small class='text-danger' id="name_error" hidden>Name is required!</small>
            </div>
            </section>
            <!-- <div class="d-flex mt-2 mb-2">
            <div class="form-check col-md-6">
  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" checked value="option1" onClick="ImageLogic('validationURLImage', 'validationFileImage')">
  <label class="form-check-label" for="exampleRadios1">
    Image URL
  </label>

  <!-- <button type="button" onclick="SetImage()" class="btn btn-warning fl-right">Show Image</button> -->
 <!--</div>
<div class="form-check col-md-6">
  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2" onClick="ImageLogic('validationFileImage', 'validationURLImage')">
  <label class="form-check-label" for="exampleRadios2">
    Image File
  </label>
</div>
</div>
<div class="d-flex mt-2 mb-2">
  <div class="col-md-6">
    <input type="text" class="form-control" id="validationURLImage" name="url_image"  value="https://lh3.googleusercontent.com/proxy/RvwT6EyQT8IljEpz9v2o0UUL0olcfR2CEcLICZ9xClROw_oFY0ZbkB0aQ12plUJBt2YvVVf0mfSVGInrNvktjoN8PJNKztvr1Lzv1jyU8LkMyhZgFszP8RFYcM5EiAp2" onchange="previewUrl()">
      <button id="URLbuttton" type="button" onclick="SetImage()" style="width: 100%" class="btn btn-warning fl-right">Show, and set Image</button>
    <div class="valid-feedback">
      Looks good!
    </div>
    
  </div>
  <div class="col-md-6">
    <input type="file" class="form-control" id="validationFileImage" name="file_image" disabled onchange="previewFile()">
    <div class="valid-feedback">
      Looks good!
    </div>
  </div>
  </div>
  <div class="col-md-12 d-flex flex-column">
  <h4 class="">
    Click Previw and see this your Image
    
  </h4>
  <p class="t-center">
  <?php 
   echo "<img id='imagePreview' name='image_to_show' style='width: 100%;' src='$image' alt='NOT FOUND' />";
    ?>

    <input type="text" class="form-control no-visibile" id="ImageToWriting" name="image">

    </p>
           

                <?php
                foreach ($file_loading_error as &$value) {
                    echo "<small class='text-danger'>$value</small><br>";
                }
                ?>

                <small class='text-danger' id="image_error" hidden>Image is required!</small>
            </div>  -->
            <button type="submit" class="btn btn-warning mt-2" style="width: 100%;" >Save changes</button>
        </form>
    </div>
</div>

<?php include "modal_image.php"; ?>

<!-- <script src="js/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script> -->
<script src="js/cropper.min.js"></script> 

<script>
  $(function() {

    const image = document.getElementById('image-modal');
        const cropper = new Cropper(image, {
            aspectRatio: 1 / 1,
            preview: ".preview"
            // crop(event) {
            //     console.log(event.detail.x);
            //     console.log(event.detail.y);
            //     console.log(event.detail.width);
            //     console.log(event.detail.height);
            //     console.log(event.detail.rotate);
            //     console.log(event.detail.scaleX);
            //     console.log(event.detail.scaleY);
            // },
        });

    let $uploader;
    $("#imgSelect").on("click", function(){
      //alert("Hello select img");
      $uploader=$('<input type="file" name="" accept="image/*" style="display: none;" />');
      $uploader.click();
      $uploader.on("change", function(){

        const [file] = $uploader[0].files

        if(file){
          var reader = new FileReader();
          reader.onload = function(event){
            var data = event.target.result;

            // console.log("--data--", data);
            $("#croppedModal").modal("show");
            cropper.replace(data);
          }

          reader.readAsDataURL($uploader[0].files[0]);
        }
        
      });

    });


    $("#btnCropped").on("click", function(){
      var dataCropper = cropper.getCroppedCanvas().toDataURL(); //take image that user crop
      $("#imgSelect").attr("src", dataCropper); // set image
      $( "#imgSelect" ).addClass( 'img-thumbnail'); //class='img-thumbnail'
      $("#image").attr("value", dataCropper);
      $("#croppedModal").modal("hide"); //close modal
    });
  });
</script>

<?php include "footer.php"; ?>