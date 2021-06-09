<!-- Include connection to DB -->
<?php require_once "connection_database.php"; ?>

<!-- Include some need functions(for exaple base64_to_jpeg - that save image) -->
<?php include "funtions.php"; ?>


<?php

//fields in our table
$name = "";
$type = "";
$image = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
  //get all value 
  $name = $_POST['name'];
  //$name = $_POST['lastname'];
  $image = $_POST['image'];

  //it need becouse image contain image not name (and we need unique name)
  $image_name = uniqid() . ".png";
  
  //always something can went wrong
  $errors = [];

  //check
    if (empty($name)) {
        $_POST['image'] = $image; //if we reload page we need save image 
        $errors["name"] = "Name is required";
    } else if (empty($image)) {
        $errors["image"] = "Image is required";
    }else{
      //save image
        base64_to_jpeg($image, "img/" . $image_name);

        //save it in DB
        $stmt = $dbh->prepare("INSERT INTO animals (id, name, image) VALUES (NULL, :name, :image);");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':image', $image_name);
        $stmt->execute();

      //return on home page
       header("Location: index.php");
        exit;
    }
}
?>

<!-- Include Up Menu(Navbar and links) -->
<?php include "head.php"; ?>

<!-- Some style for Image (when user choose and crop it) -->
<style>
 .preview{
   overflow: hidden;
   width: 200px !important;
   height: 200px !important;
   border-radius: 50%;
 }
</style>

<!-- Add Animal Form -->
<div class="container">
    <div class="p-3">
        <h2>Add new animal</h2>
        <form validate method="post" class="d-flex">
          <div class="d-flex flex-column form-group " >

            <!--#region Image -->
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
            <!--#endregion -->

            <!--#region Name -->
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
            <!--#endregion -->
  </div>
            <button style="width:100%;" type="submit" class="btn btn-warning mt-2">Submit</button>
        </form>
    </div>
</div>


<!-- Image Modal (where image crop (cropersjs)) -->
<?php include "modal_image.php"; ?>

<!-- Scripts -->
<!-- <script src="js/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script> -->
<script src="js/cropper.min.js"></script> 

<!-- Script that show modal for Crop Image -->
<script>
  $(function() {

    //get image whick we must edit
    const image = document.getElementById('image-modal');

    const cropper = new Cropper(image, {
            aspectRatio: 1 / 1,  //its do square
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


    //if we click on image we must create uploader window 
    let $uploader;
    $("#imgSelect").on("click", function(){

      //create uploader window a
      $uploader=$('<input type="file" name="" accept="image/*" style="display: none;" />');
      $uploader.click();

      //when choosen image -> read it and write in our variable
      $uploader.on("change", function(){

        const [file] = $uploader[0].files

        if(file){
          var reader = new FileReader();
          reader.onload = function(event){
            var data = event.target.result;

            // console.log("--data--", data);

            //show modal for crop
            $("#croppedModal").modal("show");
            cropper.replace(data);
          }

          reader.readAsDataURL($uploader[0].files[0]);
        }
        
      });

    });

    //when we click 'crop image'
    $("#btnCropped").on("click", function(){
      var dataCropper = cropper.getCroppedCanvas().toDataURL(); //take image that user crop
      $("#imgSelect").attr("src", dataCropper); // set image
      $( "#imgSelect" ).addClass( 'img-thumbnail'); //class='img-thumbnail'
      $("#image").attr("value", dataCropper); //set value for elem where name='image'
      $("#croppedModal").modal("hide"); //close modal
    });
  });
</script>

<?php include "footer.php"; ?>