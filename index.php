<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="Enter your description here"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
<title>Lernpartner-Datenbank</title>
</head>
<body>
    <?php require_once 'process.php'; ?>
    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?=$_SESSION['msg_type']?>">
        <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        ?>
    </div>
    <?php endif; ?>
    <div class="container">
    <?php
        $mysqli = new mysqli('localhost', 'm290admin', 'm290DB!', 'lp_db') or die(mysqli_error($mysqli));
        if (isset($_POST['filter'])){
            $lp_lb_id_filter = $_POST['lb_name_filter'];
            $lp_lg_id_filter = $_POST['lg_name_filter'];
            if (isset ($_POST["lp_filter_lb"]) && isset ($_POST["lp_filter_lg"])){
                $result = $mysqli->query("SELECT * FROM lp WHERE lp_lb_id = $lp_lb_id_filter AND lp_lg_id = $lp_lg_id_filter") or die($mysqli->error);
            }
            elseif (isset ($_POST["lp_filter_lb"])) {
                $result = $mysqli->query("SELECT * FROM lp WHERE lp_lb_id = $lp_lb_id_filter") or die($mysqli->error);
            }
            elseif (isset ($_POST["lp_filter_lg"])){
                $result = $mysqli->query("SELECT * FROM lp WHERE lp_lg_id = $lp_lg_id_filter") or die($mysqli->error);
            }
            else{
                $result = $mysqli->query("SELECT * FROM lp") or die($mysqli->error);
            }
        }
        else{
            $result = $mysqli->query("SELECT * FROM lp") or die($mysqli->error);
        }
        
    ?>

    <div class="row justify-content-center">
        <table class="table">
            <thead>
                <tr>
                    <th>Vorname</th>
                    <th>Nachname</th>
                    <th>Lernbegleiter</th>
                    <th>Lehrgang</th>
                    <th colspan="2">Aktionen</th>
                </tr>
            </thead>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php 
                        $lb_lp_id = $row['lp_lb_id'];
                        $result_lb_lp = $mysqli->query("SELECT lb.lb_name FROM lb WHERE lb.lb_id = $lb_lp_id ") or die($mysqli->error);
                        $lb_name = $result_lb_lp->fetch_assoc();
                    ?>
                    <?php 
                        $lg_lp_id = $row['lp_lg_id'];
                        $result_lg_lp = $mysqli->query("SELECT lg.lg_name FROM lg WHERE lg.lg_id = $lg_lp_id ") or die($mysqli->error);
                        $lg_name = $result_lg_lp->fetch_assoc();
                    ?>
                    <tr>
                        <td><?php echo $row['lp_name'] ?></td>
                        <td><?php echo $row['lp_lastname'] ?></td>
                        <td><?php echo $lb_name['lb_name'] ?></td>
                        <td><?php echo $lg_name['lg_name'] ?></td>
                        <td>
                            <a href="index.php?edit=<?php echo $row['lp_id']; ?>"
                                class="btn btn-info">Bearbeiten</a>
                            <a href="process.php?delete=<?php echo $row['lp_id']; ?>"
                                class="btn btn-danger">Löschen</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
        </table>
    </div>

    <div class="row justify-content-center">
        <form method="POST" action="process.php">
            <input type="hidden" name="lp_id" value="<?php echo $lp_id; ?>">
            <div class="form-group">
                <label>Vorname</label>
                <input class="form-control" type="text" name="lp_name" value="<?php echo $lp_name; ?>" placeholder="Vorname">
            </div>
            <div class="form-group">
                <label>Nachname</label>
                <input class="form-control" type="text" name="lp_lastname" value="<?php echo $lp_lastname; ?>" placeholder="Nachname">
            </div>
            <div class="form-group">
                <label>Lernbegleiter</label>
                <select class="browser-default custom-select" name="lb_name">
                    <?php while($lb_row = $lb_dropdown->fetch_array()): ?>
                        <?php if($lb_row['lb_id'] == $lp_lb_id && $update): ?>
                            <option selected="" value="<?=$lb_row['lb_id']?>"><?=$lb_row['lb_name']?></option>
                        <?php else: ?> 
                            <option value="<?=$lb_row['lb_id']?>"><?=$lb_row['lb_name']?></option>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Lehrgang</label>
                <select class="browser-default custom-select" name="lg_name">
                    <?php while($lg_row = $lg_dropdown->fetch_array()): ?>
                        <?php if($lg_row['lg_id'] == $lp_lg_id && $update): ?>
                            <option selected="" value="<?=$lg_row['lg_id']?>"><?=$lg_row['lg_name']?></option>
                        <?php else: ?> 
                            <option value="<?=$lg_row['lg_id']?>"><?=$lg_row['lg_name']?></option>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <?php if ($update == true): ?>
                    <button class="btn btn-info" type="submit" name="update">Aktualisieren</button>
                <?php else: ?>
                    <button class="btn btn-primary" type="submit" name="save">Speichern</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <form method="POST" action="index.php">
    <div class="row">
    <div class="col">
        <div class="custom-control custom-checkbox" style="margin-bottom: 1em;">
            <input type="checkbox" class="custom-control-input" id="defaultUnchecked" value="true_lb" name="lp_filter_lb">
            <label class="custom-control-label" for="defaultUnchecked">Filter Lernbegleiter</label>
        </div>
    </div>
    <div class="col">
        <div class="custom-control custom-checkbox" style="margin-bottom: 1em;">
            <input type="checkbox" class="custom-control-input" id="defaultUnchecked2" value="true_lg" name="lp_filter_lg" >
            <label class="custom-control-label" for="defaultUnchecked2">Filter Lehrgang</label>
        </div>
    </div>
    <div class="col">
    </div>
    </div>
    <div class="row" style="margin-bottom: 1em;">
    <div class="col">
        <select class="browser-default custom-select" name="lb_name_filter">
        <?php while($lb_row_filter = $lb_dropdown_filter->fetch_array()): ?>
            <option value="<?=$lb_row_filter['lb_id']?>"><?=$lb_row_filter['lb_name']?></option>
        <?php endwhile; ?>
        </select>
    </div>
    <div class="col">
        <select class="browser-default custom-select" name="lg_name_filter">
        <?php while($lg_row_filter = $lg_dropdown_filter->fetch_array()): ?>
            <option value="<?=$lg_row_filter['lg_id']?>"><?=$lg_row_filter['lg_name']?></option>
        <?php endwhile; ?>
        </select>
    </div>
    <div class="col">
        <button class="btn btn-primary" type="submit" name="filter">Filtern</button>
    </div>
    </div>
    </form>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>