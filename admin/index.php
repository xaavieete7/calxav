<?php session_start(); ?>
<?php require_once('../static.php'); 
    $obj = new NavBar();

    //Redirect if is not logged
    $objSecurity = new Security();
    $objSecurity->Logintime("administracio");

    $admin_level = $_SESSION['admin_level'];

    if ($admin_level == 0) {
        header('Location: /404.php');
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <!-- Header -->
	<?php printf($obj->Head()); ?>
    <link href="../assets/css/admin.css" rel="stylesheet" />
    <script src="index.js"></script>

</head>

<script>

    $(document).ready( function(){
        $('#notificacion_table').load('/admin/notifiaciones.php');
        $('#user_table').load('/admin/user_table.php');
    });

    function removeEvent( id ) {
        $.ajax({
            type:"POST",
            url: 'functions.php',
            data: {'id': id, 'action': "remove_notification"},
            success: function(data){

                //Display success message
                Swal.fire({
                    icon: 'swal2-icon-show',
                    title: '<i class="material-icons success-icon mr-2">check_circle_outline</i>',
                    text: data.message,
                    timer: 3000,
                    toast: true,
                    position: 'top-end',
                    showCancelButton: false,
                    showConfirmButton: false
                });
                
                $('#notificacion_table').load('/admin/notifiaciones.php');
            }
        });
    }

    function removeUser(id, name) {
        Swal.fire({
            title: '<strong>Borrar usuari</strong>',
            icon: 'info',
            html:
                'Estas segur de que vols borrar l\'usuari ' + name +
                '? <br> ' +
                'Aquesta acció es permanent i no es podràn recuperar les dades de l\'usuari.',
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonColor: '#F44336',
            cancelButtonColor: '#696969',
            focusConfirm: false,
            confirmButtonText:
                'Borrar usuari',
            cancelButtonText:
                'Cancelar',
        }).then((result) => {
            
            if (result.value) {
                $.ajax({
                    type:"POST",
                    url: 'functions.php',
                    data: {'id': id, 'action': "remove_user"},
                    success: function(data){
                        data = JSON.parse(data);
                        //Display success message
                        if (data.success == 1) {
                            Swal.fire(
                            'Esborrat!',
                            'L\'usuari ' + name + ' s\'ha esborrat satisfactoriament',
                            'success'
                            )
                        } else {
                            Swal.fire(
                            'Error!',
                            'L\'usuari ' + name + ' no s\'ha pogut esborrat, torna a intentar-ho en una estona',
                            'error'
                            )
                        }
                        
                        
                        $('#user_table').load('/admin/user_table.php');
                    }
                });
            }
        })
    }

    function editUser(id, name,) {
        var hour_total = $('.'+id+'_hour_total').val();
        var hour_price = $('.'+id+'_hour_price').val();

        $.ajax({
            type:"POST",
            url: 'functions.php',
            data: {'id': id, 'action': "edit_user", 'hour_total': hour_total, 'hour_price': hour_price },
            success: function(data){
                data = JSON.parse(data);
                if (data.success == 1) {
                    Swal.fire(
                    'Modificat!',
                    'L\'usuari ' + name + ' s\'ha modificat satisfactoriament',
                    'success'
                    )
                } else {
                    Swal.fire(
                    'Modificat!',
                    'L\'usuari ' + name + ' no s\'ha pogut modificar, tora a intentar-ho en una estona',
                    'error'
                )
                }
                
            }
        });    
    }

</script>

<body class="">

    <div class="wrapper">

        <!-- Sidebar -->
	    <?php printf($obj->Sidebar("administracio")); ?>

        <div class="main-panel">

            <!-- Navbar -->
	        <?php printf($obj->Navbar()); ?>

            <div class="content">
                <div class="container-fluid">
                    <div class="row justify-center">
                        <div class="col-md-10 card-container">
                            <div class="card">
                                <div class="card-header card-header-danger">
                                    <h4 class="card-title ">Administració</h4>
                                </div>
                                <div class="card-body">
                                <?php if ($admin_level > 1) { ?> 

                                    <h4 class="mt-2">Creació d'usuaris<hr></h4>
                                        
                                            
                                    <form id="create_user" class="new_user">
                                        <input type="hidden" name="action" value="create_user">
                                            <div class="form-row mt-1">
                                                <div class="col-md-6 mb-4">
                                                    <label>Nom:</label>
                                                    <input type="text" class="form-control" name="name" placeholder="John Smith">
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <label>Tipus d'usuari:</label>
                                                    <select name="rank" class="form-control">
                                                        <option value="user" selected>Usuari</option>
                                                        <option value="viewer">Encarregat</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-row mt-1">
                                                <div class="col-md-6 mb-4">
                                                    <label>Nom d'usuari:</label>
                                                    <input type="text" class="form-control" name="username">
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <label>Contrasenya:</label>
                                                    <div class="input-group" id="show_hide_password">
                                                        <input class="form-control" type="password" name="password">
                                                            <div class="input-group-addon">
                                                                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                    <div><a class="generate_password text-right">Generar contraseña</a></div>
                                                </div>
                                            </div>

                                            <div class="form-row mt-1 is_user">
                                                <div class="col-md-6 mb-4">
                                                    <label>Preu hora:</label>
                                                    <input type="number" class="form-control " name="hour_price" placeholder="5">
                                                    <span class="d-none error">Introdueix el preu hora</span>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <label>Hores del contracte:</label>
                                                    <input type="number" class="form-control " name="hour_total" placeholder="300">
                                                    <span class="d-none error">Introdueix les hores totals</span> 
                                                </div>
                                            </div>
                                                    
                                            <div class="form-group col-md-12 text-right mb-5 mt-3">
                                                <button type="button" id="create_new_user" class="btn btn-danger boton">Crear usuari</button>
                                            </div>  
                                    </form>
                                    <div id="user_table" class="table-responsive"></div>
                                
                                <?php } ?>

                                    <!-- Notifications start -->
                                    <?php if ($admin_level > 0) { ?>

                                        <h4 class="mt-2">Notifiacions:<hr></h4>
                                        <form id="notifiacation_form">
                                            <input type="hidden" name="action" value="notifications">
                                            <div class="form-group mt-5">
                                                <label>Títol:</label>
                                                <input type="text" name="title" class="form-control">
                                            </div>

                                            <div class="form-row mt-1">
                                                <div class="col-md-6 mb-4">
                                                    <label>Tipo:</label>
                                                    <select name="type" class="form-control">
                                                        <option value="">---</option>
                                                        <option value="warning">Warning</option>
                                                        <option value="danger">Danger</option>
                                                        <option value="info">Info</option>
                                                        <option value="success">Success</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <label>Visibilitat:</label>
                                                    <select name="visibility" class="form-control">
                                                        <option value="">---</option>
                                                        <option value="user">Users</option>
                                                        <option value="viewer">Viewers</option>
                                                        <option value="all">All</option>
                                                    </select>
                                                </div>
                                            </div>

                                                <div class="form-group mt-4 mb-4">
                                                    <label>Contingut:</label>
                                                    <textarea class="form-control" name="content" rows="3"></textarea>
                                                </div>

                                                <div class="form-group col-md-12 text-right">
                                                    <button type="button" id="save_notification_form" class="btn btn-danger boton">Guardar</button>
                                                </div>
                                            </div>
                                            </form>
                                            <hr>
                                            <div id="notificacion_table" class="table-responsive"></div>

                                    <?php } ?>
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