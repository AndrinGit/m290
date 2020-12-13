<?php 

session_start();

$mysqli = new mysqli('localhost', 'm290admin', 'm290DB!', 'lp_db') or die(mysqli_error($mysqli));

$update = false;
$lp_id = 0;
$lp_name = '';
$lp_lastname = '';
$lp_lb_id = '';
$lp_lg_id = '';

$lb_sql = "SELECT `lb`.`lb_id`, `lb`.`lb_name`
            FROM `lb`;";
$lb_dropdown = $mysqli->query($lb_sql);

$lb_sql_filter = "SELECT `lb`.`lb_id`, `lb`.`lb_name`
            FROM `lb`;";
$lb_dropdown_filter = $mysqli->query($lb_sql_filter);

$lg_sql = "SELECT `lg`.`lg_id`, `lg`.`lg_name`
            FROM `lg`;";
$lg_dropdown = $mysqli->query($lg_sql);

$lg_sql_filter = "SELECT `lg`.`lg_id`, `lg`.`lg_name`
            FROM `lg`;";
$lg_dropdown_filter = $mysqli->query($lg_sql_filter);

if (isset($_POST['save'])){
    $lp_name = $_POST['lp_name'];
    $lp_lastname = $_POST['lp_lastname'];
    $lp_lb_id = $_POST['lb_name'];
    $lp_lg_id = $_POST['lg_name'];

    $mysqli->query("INSERT INTO lp (lp_name, lp_lastname, lp_lb_id, lp_lg_id) VALUES ('$lp_name', '$lp_lastname', '$lp_lb_id', '$lp_lg_id')") or
        die($mysqli->error);

    $_SESSION['message'] = "Eintrag erstellt!";
    $_SESSION['msg_type'] = "success";

    header("location: index.php");
}

if (isset($_GET['delete'])){
    $lp_id = $_GET['delete'];

    $mysqli->query("DELETE FROM lp WHERE lp_id=$lp_id") or
        die($mysqli->error);

    $_SESSION['message'] = "Eintrag gelöscht!";
    $_SESSION['msg_type'] = "danger";

    header("location: index.php");
}

if (isset($_GET['edit'])){
    $lp_id = $_GET['edit'];
    $update = true;
    $result = $mysqli->query("SELECT * FROM lp WHERE lp_id=$lp_id") or 
        die($mysqli->error);

    if($result->num_rows){
        $row = $result->fetch_array();
        $lp_name = $row['lp_name'];
        $lp_lastname = $row['lp_lastname'];
        $lp_lb_id = $row['lp_lb_id'];
        $lp_lg_id = $row['lp_lg_id'];
    }
}

if (isset($_POST['update'])){
    $lp_id = $_POST['lp_id'];
    $lp_name = $_POST['lp_name'];
    $lp_lastname = $_POST['lp_lastname'];
    $lp_lb_id = $_POST['lb_name'];
    $lp_lg_id = $_POST['lg_name'];

    $mysqli->query("UPDATE lp SET lp_name='$lp_name', lp_lastname='$lp_lastname', lp_lb_id='$lp_lb_id', lp_lg_id='$lp_lg_id' WHERE lp_id=$lp_id") or
        die($mysqli->error);

    $_SESSION['message'] = "Eintrag aktualisiert!";
    $_SESSION['msg_type'] = "warning";

    header("location: index.php");
}

?>