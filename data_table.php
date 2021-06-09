<!-- Include Connection to DB -->
<?php require_once "connection_database.php"; ?> 

<!-- Include Modal for Delete Items-->
<?php include "modal.php"; ?>

<h2> List of Animal</h2>
<div class="container">
     <!--#region Table with all items(with paggination)  -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th>id</th>
            <th>name</th>
            <th>type</th>
            <th>image</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <?php

        //Pagination
        $page_size = 3; // count of element on one page
        $page_number = 1; //current page

        //get and set current page
        if(isset($_GET["page"])){
          $page_number = $_GET["page"];
        }
        
        //select count/(lenght) of all items from table animals in DB
        $command = $dbh->prepare("SELECT COUNT(*) AS animal_count FROM animals");
        $command->execute();

        //get and write count/(lenght) in our value
        $count_items;
        if ($count_animals = $command->fetch(PDO::FETCH_ASSOC)){
          $count_items = $count_animals["animal_count"];
        }

        //calculate how many pages we need 
        $pages = ceil($count_items / $page_size);

        //select items from table animals in DB in right amount(such as page_size = 3 -> first select 0-3 next 3-6 and next)
        $command = $dbh->prepare("SELECT id, name, type, image FROM animals LIMIT " . ($page_number - 1)* $page_size .", " . $page_size);
        $command->execute();

        //show our animals
        while ($row = $command->fetch(PDO::FETCH_ASSOC))
        {
            echo"
            <tr>
            <td>{$row["id"]}</td>
            <td>{$row["name"]}</td>
            <td>{$row["type"]}</td>
            <td><img style='width: 200px; height=200px;' src='img/{$row["image"]}' class='img-thumbnail' alt='Animal image'></td>
            <td><a class='btn btn-dark' href='edit.php?id={$row["id"]}'>Edit  <i class='far fa-edit'></i></td>
            <td>
                <button  onclick='loadDeleteModal({$row["id"]}, `{$row["name"]}`)' data-toggle='modal' data-target='#modalDelete' class='btn btn-danger' >Delete  <i class='fas fa-trash-alt'></i></button>
            </td>
            </tr>";
        }

        ?>
  </table>
</div>
<!--#region Paggination(Controls(buttons))  -->
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <?php 
    
    //button '-' if we click -> current page-1 (we must back on 1 page)
    if(isset($_GET['page']))
    {
      $current_page = $_GET['page']; //get current page
      if($_GET['page'] == 1) //if page == 1 we cant do -1 -> we need back to last
      { 
        $current_page = $pages + 1;
      }
      echo "<li class='page-item'><a class='page-link' href='?page=" . $current_page-1 . "'> - </a></li>";
    }
    else{ 
      //page not exist -> began from last page
      echo "<li class='page-item'><a class='page-link' href='?page=" . $pages . "'> - </a></li>";
    }

    //show all page with clickabed buttons(pages)
    for ($i=1; $i <= $pages; $i++) { 
      echo "<li class='page-item'><a class='page-link' href='?page=$i'>$i</a></li>";
    }

    //button '+' if we click -> current page+1 (we must forward on 1 page)
    if(isset($_GET['page']))
    {
      $current_page = $_GET['page']; //get current page
      if($_GET['page'] == $pages) //if page == last page we cant do +1 -> we need back to first
      {
        $current_page = 0;
      }
      echo "<li class='page-item'><a class='page-link' href='?page=" . $current_page+1 . "'> + </a></li>";
    }
    else{
      echo "<li class='page-item'><a class='page-link' href='?page=1'> + </a></li>";
    }
    ?>
    
  </ul>
</nav>
 <!--#endregion-->
 <!--#endregion-->

 <!-- Include AJAX-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- There script for delete items (it call modal) -->
<script src="js/deleteItem.js" ></script>

