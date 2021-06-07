<?php require_once "connection_database.php"; ?>
<?php

function base64_to_jpeg($base64_string, $output_file) {
  // open the output file for writing
  $ifp = fopen( $output_file, 'wb' ); 

  // split the string on commas
  // $data[ 0 ] == "data:image/png;base64"
  // $data[ 1 ] == <actual base64 string>
  $data = explode( ',', $base64_string );

  // we could add validation here with ensuring count( $data ) > 1
  fwrite( $ifp, base64_decode( $data[ 1 ] ) );

  // clean up the file resource
  fclose( $ifp ); 

  return $output_file; 
}


$name = "";
$image = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  

  $name = $_POST['name'];
  //$name = $_POST['lastname'];
  $image = $_POST['image'];
  $image_name = uniqid() . ".png";
  //printf("uniqid(): %s\r\n", uniqid());
  

    //$image_url = $_POST['image'];//$_POST['image'];

    $errors = [];
    if (empty($name)) {
        $_POST['image'] = $image;
        $errors["name"] = "Name is required";
    } else if (empty($image)) {
        $errors["image"] = "Image is required";
    }else{
        //base64_to_jpeg($image, "img/slavik.png");
        base64_to_jpeg($image, "img/" . $image_name);

        $stmt = $dbh->prepare("INSERT INTO animals (id, name, image) VALUES (NULL, :name, :image);");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':image', $image_name);
        $stmt->execute();

        //$sql = "INSERT INTO animals (name, image) VALUES (?,?)";
        //$stmt->prepare($sql)->execute([$name, $image_url]);
       header("Location: index.php");
       //header('Location: /php_animal/');
        exit;
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


<div class="container">
    <div class="p-3">
        <h2>Add new animal</h2>
        <form validate method="post" class="d-flex">
        <div class="d-flex flex-column form-group " >
  <!-- <img src="img/no-image.png" alt="Choose photo" class="" style="cursor: pointer;" width="250" id="imgSelect" /> -->
  <?php if(!empty($image)){
          echo "<img src='$image' alt='Choose photo' class='img-thumbnail' style='cursor: pointer;' width='250' id='imgSelect' />
          <input type='hidden' id='image' name='image' value='$image' />"; }
          else{
            echo "<img src='img/no-image.png' alt='Choose photo' class='' style='cursor: pointer;' width='250' id='imgSelect' />
            <input type='hidden' id='image' name='image' />";
          } ?>
  <!-- <input type="hidden" id="image" name="image" /> -->
  <label class="form-check-label" style="color: blue" for="exampleRadios1">
    Click on image and choose Image 
  </label>
  <?php if(isset($errors['image']))
          echo "<small class='text-danger'>{$errors['image']}</small>" ?>
  </div>
            <div class="form-group" style="width:100%;margin-left: 30px;">
                <label for="exampleInputEmail1">Animal name: </label>
                <?php
                echo "<input type='text' name='name' class='form-control' style='width:100%;' id='exampleInputEmail1'
                           placeholder='Enter animal name' value={$name}>"
                ?>

                <?php
                if(isset($errors['name']))
                    echo "<small class='text-danger'>{$errors['name']}</small>"
                ?>
            </div>
            <!-- <div class="form-group">
                <label for="exampleInputPassword1">Image url: </label>
                <?php
                echo "<input type='text' name='image' class='form-control' id='exampleInputEmail1'
                           placeholder='Enter animal name' value={$image_url}>"
                ?>
                <?php
                if(isset($errors['image']))
                    echo "<small class='text-danger'>{$errors['image']}</small>"
                ?>
            </div> -->
            
<!-- <div class="d-flex mt-2 mb-2">
            <div class="form-check col-md-6">
  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" checked value="option1" onClick="ImageLogic('validationURLImage', 'validationFileImage')">
  <label class="form-check-label" for="exampleRadios1">
    Image URL
  </label>

</div>
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
    <img id="imagePreview" src="https://lh3.googleusercontent.com/proxy/RvwT6EyQT8IljEpz9v2o0UUL0olcfR2CEcLICZ9xClROw_oFY0ZbkB0aQ12plUJBt2YvVVf0mfSVGInrNvktjoN8PJNKztvr1Lzv1jyU8LkMyhZgFszP8RFYcM5EiAp2" alt="NOT FOUND" />
    <input type="text" class="form-control no-visibile" id="ImageToWriting" name="url_image">

    </p> -->
  </div>
            <button style="width:100%;" type="submit" class="btn btn-warning mt-2">Submit</button>
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