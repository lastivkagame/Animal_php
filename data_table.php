<?php require_once "connection_database.php"; ?>

<?php include "modal.php"; ?>

<h2> List of Animal</h2>
<div class="container">
   
    <!-- <a class="btn btn-primary" href="addAnimal.php">Add new animal</a> -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th>id</th>
            <th>name</th>
            <th>image</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <?php

        $command = $dbh->prepare("SELECT id, name, image FROM animals");
        $command->execute();
        while ($row = $command->fetch(PDO::FETCH_ASSOC))
        {
            echo"
            <tr>
            <td>{$row["id"]}</td>
            <td>{$row["name"]}</td>
            <td><img style='width: 200px; height=200px;' src='{$row["image"]}' class='img-thumbnail' alt='Animal image'></td>
            <td><a class='btn btn-dark' href='edit.php?id={$row["id"]}'>Edit  <i class='far fa-edit'></i></td>
            <td>
                <button  onclick='loadDeleteModal({$row["id"]}, `{$row["name"]}`)' data-toggle='modal' data-target='#modalDelete' class='btn btn-danger' >Delete  <i class='fas fa-trash-alt'></i></button>
            </td>
            </tr>";
        }

        ?>
    </table>
    </div>
<!-- 
<table class='table' method="post">
    <thead>
      <tr>
        <th scope='col'>ID</th>
        <th scope='col'>Name</th>
        <th scope='col'>Image</th>
        <th scope='col'>Controls</th>
      </tr>
    </thead>
    <tbody>

<?php
    $myPDO = new PDO('mysql:host=localhost;dbname=db_spu926', 'root', '');
    
    $result = $myPDO->query("SELECT id, name, image FROM animals");
    

    // function DeleteAnimal($id){
    //   $myPDO = new PDO('mysql:host=localhost;dbname=db_spu926', 'root', '');
    //   $del_SQL = "DELETE FROM animals WHERE id='$id'";
    // }

    if(array_key_exists('button1', $_POST)) {
      echo "This is Button1 that is selected";
  }
  else if(array_key_exists('button2', $_POST)) {
    $myPDO = new PDO('mysql:host=localhost;dbname=db_spu926', 'root', '');
    $del_SQL = "DELETE FROM animals WHERE id='$id'";
  }


    // echo "<table class='table'>
    // <thead>
    //   <tr>
    //     <th scope='col'>ID</th>
    //     <th scope='col'>Name</th>
    //     <th scope='col'>Image</th>
    //   </tr>
    // </thead>
    // <tbody>";
$counter = 0;

    foreach($result as $row){
      // print $row["id"] . "\t";
      // print $row['name'] . "\t";
      // print $row['image'] . "\t";
      $counter++;

   echo " <tr>
   <th scope='row'>{$row["id"]}</th>
   <th scope='row'>{$row["name"]}</th>";
   if($counter<4){
    echo "<th scope='row'><img style='width: 10%;' src='img/{$row["image"]}' alt='' /></th>";
   }
   else{
    echo "<th scope='row'><img style='width: 10%;' src='{$row["image"]}' alt='' /></th>";
   }
   echo "
   <th scope='row'><button  onclick='loadDeleteModal(${row["id"]})' data-toggle='modal' data-target='#modalDelete' class='btn btn-danger' >Delete  <i class='fas fa-trash-alt'></i></button>         
   <td><a class='btn btn-dark' href='editAnimal.php?id=${row["id"]}'>Edit  <i class='far fa-edit'></i></td>";
   
  }
  //  <th scope='row'><input type='submit' name='button2' class='btn btn-danger' value='DELETE' data-target='' /></th>";
  //  <th scope='row'><input type='submit' name='button1' class='btn btn-warning' value='EDIT' /></th>
  //  <th scope='row'><img style='width: 10%;' src='img/{$row["image"]}' alt='' /></th>
  //  <th scope='row'><button class='btn btn-danger'><a href='del.php?id=<?= $row['id'];'>DELETE</a></button></th>";
  //  <th scope='row'><a class='btn btn-warning' href='edit.php?id=<?=$row['id'];'>EDIT</a></th>

  //   echo " </tr>
  //   </tbody>
  // </table>";
?> 

</tr>
    </tbody>
  </table> -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    function loadDeleteModal(id, name)
    {
      console.log("id: " + id + name);
        $(`#modalDeleteContent`).empty();
        $(`#modalDeleteContent`).append(`<div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Delete animal ${name} (#${id})?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <form action="deleteAnimal.php" method="post">
                <input type='hidden' name='id' value='${id}'>
                <button type="submit" name="delete_submit" class="btn btn-danger">Delete</button>
            </form>
        </div>`);
    }
</script>

