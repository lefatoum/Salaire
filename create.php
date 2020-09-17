<?php

require_once "config.php";

session_start();


if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


$name = $address = $salary = "";
$name_err = $address_err = $salary_err = "";


if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "S'il vous plaît, entrez un nom.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "S'il vous plaît, entrez un nom valide.";
    } else{
        $name = $input_name;
    }


    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "S'il vous plaît, entrez une adresse.";
    } else{
        $address = $input_address;
    }


    $input_salary = trim($_POST["salary"]);
    if(empty($input_salary)){
        $salary_err = "S'il vous plaît, entrez le montant du salaire.";
    } elseif(!ctype_digit($input_salary)){
        $salary_err = "S'il vous plaît; entrez une valeur positive.";
    } else{
        $salary = $input_salary;
    }


    if(empty($name_err) && empty($address_err) && empty($salary_err)){

        $sql = "INSERT INTO employees (name, address, salary) VALUES (?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){

            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_address, $param_salary);


            $param_name = $name;
            $param_address = $address;
            $param_salary = $salary;


            if(mysqli_stmt_execute($stmt)){

                header("location: index.php");
                exit();
            } else{
                echo "Erreur! S'il vous plaît, essayez encore.";
            }
        }


        mysqli_stmt_close($stmt);
    }


    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Enregistrez</h2>
                    </div>
                    <p>S'il vous plaît, remplissez ce formulaire puis validez.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Nom</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Adresse</label>
                            <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>">
                            <label>Salaire</label>
                            <input type="text" name="salary" class="form-control" value="<?php echo $salary; ?>">
                            <span class="help-block"><?php echo $salary_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Valider">
                        <a href="index.php" class="btn btn-default">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
