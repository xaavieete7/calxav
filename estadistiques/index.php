<?php session_start(); ?>
<?php require('../static.php'); 
    $obj = new NavBar();
    
    //Redirect if is not logged
    $objSecurity = new Security();
    $objSecurity->Logintime("estadistiques");

    $objDB = new DatabaseConn();
    $conn = $objDB->Connection();

    $rank = $_SESSION['rank'];

?>

<!DOCTYPE html>
<html lang="en" xmlns="">

<head>

    <!-- Header -->
	<?php printf($obj->Head()); ?>

    <link rel="stylesheet" href="../assets/css/stats.css">

</head>

<?php

    if ($rank == 'viewer') {
        $user_id = $_GET['user_id'];
        $args = "SELECT `username` FROM `users` WHERE `id` = '$user_id'";
        $sql = mysqli_query($conn, $args);
        $rows = mysqli_fetch_assoc($sql);
        $username = $rows['username'];
    }

?>

<script>
    $(document).ready(function() {
        $('#table').DataTable( {
            "paging":   false,
            "ordering": true,
            "info":     false,
            "order": [[ 3, "asc" ]]
        } );
    } );
</script>

<body class="">

    <div class="wrapper">
        <!-- Sidebar -->
	    <?php printf($obj->Sidebar("estadistiques")); ?>

        <div class="main-panel">

            <!-- Navbar -->
	        <?php printf($obj->Navbar()); ?>
            
            <div class="content">
                <div class="container-fluid">
                    <div class="row justify-center">
                        <div class="col-md-10 card-container">
                            <div class="card">
                                <div class="card-header card-header-danger">
                                    <h4 class="card-title ">Estadístiques <?php echo ucfirst($username); ?></h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">

                                        <?php

                                            $objTable = new TableArray();
                                            $meses = $objTable->ArrayMonth();
                                            $user_id = $_SESSION["user_id"];

                                            if ($rank == 'viewer') {

                                                if ($_GET['user_id']) {
                                                    $user_id = $_GET['user_id'];
                                                } else {

                                                    $mes = date("n");
                                                    $any = date("o");

                                                    $args = "SELECT * FROM `users` WHERE `rank` LIKE 'user'";
                                                    $sql = mysqli_query($conn, $args);

                                                    $html = '<div class="row justify-center">';

                                                    while ($rows = mysqli_fetch_assoc($sql)) {

                                                        //Hores aquest mes
                                                        $user_idDB = $rows['id'];
                                                        $username = $rows['username'];
                                                        $args = "SELECT SUM(`salary`) FROM `eventos` WHERE `user_id` LIKE '$user_idDB' AND MONTH(`start`) = '$mes' AND YEAR(`start`) = '$any'";
                                                        $sql_html = mysqli_query($conn, $args);

                                                        while ($rows_html = mysqli_fetch_assoc($sql_html)) {

                                                            $salary_mes = $rows_html['SUM(`salary`)'];
                                                            if (empty($salary_mes)){
                                                                $salary_mes = 0;
                                                            }

                                                            $html .= '<div class="col-lg-4 col-md-6 col-sm-6">';
                                                                $html .= '<div class="card card-stats">';
                                                                    $html .= '<div class="card-header card-header-danger card-header-icon">';
                                                                        $html .= '<div class="card-icon">';
                                                                            $html .= '<i class="material-icons">person</i>';
                                                                        $html .= '</div>';
                                                                            $html .= '<p class="card-category">'.ucfirst($username).'</p>';
                                                                            $html .= '<h2 class="card-title">'.$salary_mes.'';
                                                                                $html .= '<small>€</small>';
                                                                            $html .= '</h2>';
                                                                        $html .= '</div>';
                                                                        $html .= '<div class="card-footer">';
                                                                            $html .= '<div class="stats"><a href="/estadistiques/?user_id='.$user_idDB.'" class="text-danger">Veure estadístiques</a></div>';
                                                                    $html .= '</div>';
                                                                $html .= '</div>';
                                                            $html .= '</div>';

                                                        }


                                                    }

                                                    $html .= '</div>';

                                                    print($html);
                                                    exit;
                                                }

                                            }

                                            $html = '<table id="table" class="table text-center stripe hover display ">';

                                                $html .= '<thead>';
                                                    $html .= '<tr>';
                                                        $html .= '<th>Mes</th>';
                                                        $html .= '<th>Hores</th>';
                                                        $html .= '<th>Salari</th>';
                                                        $html .= '<th>Any</th>';
                                                    $html .= '</tr>';
                                                $html .= '</thead>';

                                                $html .= '<tbody>';

                                                    foreach ($meses as $mes => $num) {

                                                        //Horas
                                                        $args = "SELECT SUM(horas) FROM `eventos` WHERE `user_id` = $user_id AND MONTH(`start`) = $num[0] AND YEAR(`start`) = $num[1]";
                                                        $sql = mysqli_query($conn, $args);
                                                        $rows = mysqli_fetch_assoc($sql);
                                                        $horas = $rows['SUM(horas)'];

                                                        //Salario
                                                        $args = "SELECT SUM(salary) FROM `eventos` WHERE `user_id` = $user_id AND MONTH(`start`) = $num[0] AND YEAR(`start`) = $num[1]";
                                                        $sql = mysqli_query($conn, $args);
                                                        $rows = mysqli_fetch_assoc($sql);
                                                        $salari = $rows['SUM(salary)'];
                                                        
                                                        if ($horas) {
                                                            $html .= '<tr>';
                                                                $html .= '<td>' . $mes .'</td>';
                                                                $html .= '<td>' . $horas . 'h</td>';
                                                                $html .= '<td>' . $salari . '€</td>';
                                                                $html .= '<td>' . $num[1] .'</td>';
                                                            $html .= '</tr>';
                                                        }
                                                    }

                                                $html .= '</tbody>';

                                                $args = "SELECT SUM(horas) FROM `eventos` WHERE `user_id` = $user_id";
                                                $sql = mysqli_query($conn, $args);
                                                $rows = mysqli_fetch_assoc($sql);

                                                $horas_totals = $rows['SUM(horas)'];

                                                $args = "SELECT SUM(salary) FROM `eventos` WHERE `user_id` = $user_id";
                                                $sql = mysqli_query($conn, $args);
                                                $rows = mysqli_fetch_assoc($sql);

                                                $salari_total = $rows['SUM(salary)'];

                                                $html .= '<tfoot>';
                                                    $html .= '<tr>';
                                                        $html .= '<th>TOTAL</th>';
                                                        $html .= '<th>' . $horas_totals . 'h</th>';
                                                        $html .= '<th>' . $salari_total . '€</th>';
                                                        $html .= '<th></th>';
                                                    $html .= '</tr>';
                                                $html .= '</tfoot>';

                                            $html .= '<table>';

                                            print($html);
                                        ?>
                                    </div> 
                                <div>
                            <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer src -->
    <?php printf($obj->Footerlinks()); ?>

</body>

</html>