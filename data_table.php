<!-- Include Connection to DB -->
<?php require_once "connection_database.php"; ?> 

<!-- Include Modal for Delete Items-->
<?php include "modal.php"; ?>

<!-- Search -->
<!-- <?php
$name_search="";
if(isset($_GET["name_search"]))
{
    $name_search=$_GET["name_search"];
}
?>

<form method="get">
    <div class="mb-3">
        <label for="name" class="form-label">Назва</label>
        <input type="text" class="form-control" id="name_search"
               value="<?php echo $name_search; ?>"
               name="name_search">
    </div>

    <button type="submit" class="btn btn-primary">Search</button>

</form> -->

<!-- Main Page -->
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
        $where = "WHERE name LIKE :name OR type LIKE :name";
        $sql_count = "SELECT COUNT(*) AS animal_count FROM animals ".$where;
        $command = $dbh->prepare($sql_count);
        //$command->execute();
        $command->execute(["name"=> '%'.$name_search.'%']);
        //$row = $command->fetch(PDO::FETCH_ASSOC);

        //get and write count/(lenght) in our value
        $count_items;
        if ($count_animals = $command->fetch(PDO::FETCH_ASSOC)){
          $count_items = $count_animals["animal_count"];
        }

        //calculate how many pages we need 
        $pages = ceil($count_items / $page_size);

        //select items from table animals in DB in right amount(such as page_size = 3 -> first select 0-3 next 3-6 and next)
        $command = $dbh->prepare("SELECT id, name, type, image FROM animals ".$where." LIMIT " . ($page_number - 1)* $page_size .", " . $page_size);
        $command->execute(["name"=> '%'.$name_search.'%']);
        //$command->execute();

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
<nav aria-label="Page navigation example" class="d-flex justify-content-center">
    <ul class="pagination">
      <?php

        //button '-' if we click -> current page-1 (we must back on 1 page)
        if(isset($_GET['page']))
        {
          $current_page = $_GET['page']; //get current page
          if($_GET['page'] == 1) //if page == 1 we cant do -1 -> we need back to last
          { 
            $current_page = $pages + 1;
          }
          echo "<li class='page-item'><a class='page-link' href='?page=" . $current_page-1 . "&name_search={$name_search}'> &laquo; </a></li>";
        }
        else{ 
          //page not exist -> began from last page
          echo "<li class='page-item'><a class='page-link' href='?page=" . $pages . "&name_search={$name_search}'> &laquo; </a></li>";
        }


        //Need to know: page_number = current page;
        $show_begin=9; //amount of page that show first
        $step=3; //step when we change page around
        //Important: show_begin and step must be proportinal suc as 3 and 9, or 4 and 12 and etc.

        //show pages
        for($i=1;$i<=$pages;$i++)
        {
            $active ="active";
            if($i!=$page_number)
                $active = "";

           //FIRST PART(if current page < that show_elem(that page we showing)): when 1,2,3, -> ($show_begin - $step) 
            if($i<=$show_begin && $page_number < ($show_begin - $step))  {
                echo "<li class='page-item {$active}'><a class='page-link' href='?page={$i}&name_search={$name_search}'>{$i}</a></li>";
               // echo "<";
            }

            //SECOND PART: example() 1,2 ... 8,9,10 ... )
            if($page_number>=($show_begin - $step))
            {
              //show to ... 1,2 <-(this, first elements ) 
              //this can be step or simple count of begin elem such as variable as begin_elems = 3 or 2;
                if($i<=($step-1)) {
                    echo "<li class='page-item {$active}'><a class='page-link' href='?page={$i}&name_search={$name_search}'>{$i}</a></li>";
                }
                else if($i==$step) { // show 1,2 -> ... <-(this) 8,9,
                    echo "<li class='page-item'><a class='page-link' href='?page={$i}&name_search={$name_search}'>...</a></li>";
                }
                else if(($page_number-$step)<=$i && $i<=($page_number+$step + 1)) { //show after, example: 1,2 ... -> 8,9 <-(this),
                    echo "<li class='page-item {$active}'><a class='page-link' href='?page={$i}&name_search={$name_search}'>{$i}</a></li>";
                }
            }
        }
        
        // THIRD PART: show()  ... last page): we must show it if $show_begin < current page < $pages
        if(($page_number+$step) < ($pages-1) && $show_begin < $pages && $page_number < ($pages - $step)) {
            $i--;

            //its need for the decision problem, such as 15,16,17 ... 18 (this not need ...)
            if(($page_number+$step) < ($pages-2))
            {
              echo "<li class='page-item'><a class='page-link' href='?page={$i}&name_search={$name_search}'>...</a></li>";
            }

            //show last page
            echo "<li class='page-item'><a class='page-link' href='?page={$i}&name_search={$name_search}'>$i</a></li>";
        }

         //button '+' if we click -> current page+1 (we must forward on 1 page)
         if(isset($_GET['page']))
         {
          $current_page = $_GET['page']; //get current page
          if($_GET['page'] == $pages) //if page == last page we cant do +1 -> we need back to first
          {
            $current_page = 0;
          }
          echo "<li class='page-item'><a class='page-link' href='?page=" . $current_page+1 . "&name_search={$name_search}'> &raquo; </a></li>";
        }
        else{
          echo "<li class='page-item'><a class='page-link' href='?page=1&name_search={$name_search}'> &raquo; </a></li>";
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

