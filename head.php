
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
   

    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/0.8.1/cropper.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/0.8.1/cropper.css" />
    <link rel="stylesheet" href="style.css" />

    <link rel="stylesheet" href="css/cropper.min.css">


    <title>Animal World</title>
</head>
<!-- <body>
    <h1>Main Page</h1>
<button class="btn btn-primary">Hello</button> -->
   
    <!-- <?php 
    $a=23;
    $b = "23";
    $c=45;

    echo "a = " . $a .  "<br />";
    if($a==$b) //=== - by type
    {
        echo "a == b ";
    }
    ?> -->
    <?php require_once "connection_database.php"; ?>
    <?php 

     // Pagination.
    //  if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    //   $page = htmlspecialchars($_GET['page']);
    // }
    // else{
    //   $page = 1;
    // }
    // $num_results_on_page = 5;
    // $calc_page = ($page - 1) * $num_results_on_page;
  
    // echo "work";
    // if(isset($_POST["btnSearch"])){
    //   echo "work2";
    //     $search_query = preg_replace("#[^a-z 0-9?!]#i", "", $POST["searchbar"]);

    //     $str_command = "SELECT id, name, image FROM animals WHERE name LIKE " . $POST["searchbar"] . "%";
    //     $command = $dbh->prepare($str_command);
    //     $command->execute();
    //      while ($row = $command->fetch(PDO::FETCH_ASSOC))
    //      {
    //          echo"
    //          <tr>
    //          <td>{$row["id"]}</td>
    //          <td>{$row["name"]}</td>
    //          <td>{$row["id"]}</td>
    //          ";
    //      }

       // header('location: search.php?query=.urlencode($search_query).`');
    //}

    ?>
    
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container" >
  <a class="navbar-brand" href="#">Animal World</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="/php_animal/">Home <span class="sr-only"></span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="add.php">ADD Animal</a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="#">Pricing</a>
      </li> -->
     
    </ul>
    <form class="d-flex">
        <!-- <input class="form-control me-2" name="searchbar" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-warning" type="submit" onClick="SearchAnimals()" name="btnSearch" id="btnSearch">Search</button> -->
        <!-- <form class="d-flex col-4">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="search_line">
            <button class="btn btn-outline-success" type="submit" id="btn_search"><i class="bi bi-search"></i></button>
        </form> -->
        <form action="" method="post">  
Search: <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="term" /><br />  
<input type="submit" class="btn btn-outline-warning" value="Search" />  
</form>  
<?php
if (!empty($_REQUEST['term'])) {
echo "s";
$term = mysql_real_escape_string($_REQUEST['term']);     

$sql = "SELECT * FROM animals WHERE name LIKE '%".$term."%'"; 
$r_query = mysql_query($sql); 
echo "something";

while ($row = mysql_fetch_array($r_query)){  
echo 'Primary key: ' .$row['PRIMARYKEY'];  
echo '<br /> Code: ' .$row['name'];   
}  

}
?>
      </form>
    </div>
  </div>
</nav>
    <div class="container mt-5" >

    