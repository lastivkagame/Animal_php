<?php require_once "connection_database.php"; ?>
<?php
$name = "";
$image_url = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $image_url = $_POST['url_image'];//$_POST['image'];
    $errors = [];
    if (empty($name)) {
        $errors["name"] = "Name is required";
    } else if (empty($image_url)) {
        $errors["image"] = "Image url is required";
    }else{
        $stmt = $dbh->prepare("INSERT INTO animals (id, name, image) VALUES (NULL, :name, :image);");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':image', $image_url);
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


<div class="container">
    <div class="p-3">
        <h2>Add new animal</h2>
        <form validate method="post">
            <div class="form-group">
                <label for="exampleInputEmail1">Animal name: </label>
                <?php
                echo "<input type='text' name='name' class='form-control' id='exampleInputEmail1'
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

<div class="d-flex mt-2 mb-2">
            <div class="form-check col-md-6">
  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" checked value="option1" onClick="ImageLogic('validationURLImage', 'validationFileImage')">
  <label class="form-check-label" for="exampleRadios1">
    Image URL
  </label>

  <!-- <button type="button" onclick="SetImage()" class="btn btn-warning fl-right">Show Image</button> -->
</div>
<div class="form-check col-md-6">
  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2" onClick="ImageLogic('validationFileImage', 'validationURLImage')">
  <label class="form-check-label" for="exampleRadios2">
    Image File
  </label>
</div>
  <!-- <button type="button" onclick="SetImage()" class="btn btn-warning fl-right">Show Image</button> -->
</div>
<div class="d-flex mt-2 mb-2">
  <div class="col-md-6">
    <!-- <label for="validationCustom02" class="form-label">Image URL</label> -->
    <input type="text" class="form-control" id="validationURLImage" name="url_image"  value="https://lh3.googleusercontent.com/proxy/RvwT6EyQT8IljEpz9v2o0UUL0olcfR2CEcLICZ9xClROw_oFY0ZbkB0aQ12plUJBt2YvVVf0mfSVGInrNvktjoN8PJNKztvr1Lzv1jyU8LkMyhZgFszP8RFYcM5EiAp2" onchange="previewUrl()">
      <button id="URLbuttton" type="button" onclick="SetImage()" style="width: 100%" class="btn btn-warning fl-right">Show, and set Image</button>
    <div class="valid-feedback">
      Looks good!
    </div>
    
  </div>
  <div class="col-md-6">
    <!-- <label for="validationCustom02" class="form-label">Image File</label> -->
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

    </p>
  </div>
            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </form>
    </div>
</div>

<?php include "footer.php"; ?>