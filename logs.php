<?php

## GR8 FAUCET SCRIPT LITE - ADMIN LOGS ##

## Initiate Script Requirements
include '../script/ini.php';

## Login
if($_POST['password']){ 
    if($_POST['password'] == $password){ 
    	$_SESSION[$faucetID.'-admin'] = 'logged';
    }
    else {
        $error = alert('Invalid Password!', 'danger');
    }
}
elseif($_GET['a'] == 'logout'){
    unset($_SESSION[$faucetID.'-admin'] );
}
?>

<!DOCTYPE html>
<html lang="en"> 
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GR8 Faucet Script (Lite) Admin Panel</title>
        <meta name="description" content="">
    	<meta name="keywords" content="">
    	<meta name="robots" content="noindex,nofollow">
    	<!-- Favicon -->
        <link rel="icon" href="">	
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/v4-shims.css">
    	<!-- Datatables -->
    	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css">
        <!-- Base CSS -->
        <link rel="stylesheet" href="../libs/css/base.css">	
    </head>
    
    <!-- START BODY -->
    <body class="d-flex flex-column">
        
    	<!-- Navbar - -->
    	<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    	    <div class="container">
                <a class="navbar-brand" href="<?= $settings['domain'];?>/admin/">GR8 Faucet Lite Admin</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav ml-auto">
                        <a class="nav-item nav-link" href="settings.php">Settings</a>
                        <a class="nav-item nav-link" href="<?= rtrim($settings['domain'],'/');?>/admin/index.php">Reports</a>
                        <a class="nav-item nav-link" href="logs.php">Logs</a>
                        <a class="nav-item nav-link" href="<?= $settings['domain'];?>" target="_blank">View Faucet</a>
                        <?php if($_SESSION[$faucetID.'-admin'] == 'logged'){ ?><a class="nav-item nav-link" href="?a=logout">Logout</a><?php } ?>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Update available Message -->
        <?php if($settings['update']){ ?>
            <div class="alert alert-success w-100 text-center m-0">
                <b>Update: </b><?= $settings['update']['name'];?> <span class="align-text-bottom"><small>v<?= $settings['update']['version'];?></small></span> is available to <a class="alert-link" href="<?= $settings['update']['download'];?>">download</a>!
            </div>
        <?php } ?>
        
        <!-- If Logged in -->
        <?php if($_SESSION[$faucetID.'-admin'] == 'logged'){ ?>
            <div class="container-fluid bg-light">
        		<div class="container p-5">
                    <h1 class="text-center font-weight-bold text-secondary"><i class="far fa-list-alt"></i> Faucet Logs</h1>
    				<div class="container">
    				    <div class="row">
    						<form method="POST" class="form-inline col justify-content-center" accept-charset="utf-8" action="">
    							<select name="log" class="form-control mb-2 mr-sm-2">
    								<option value="" disabled selected>Select Log File</option>
    								<option value="action" >Actions Log</option>
    								<option value="error" >Error Log</option>
    							</select>
    							<input type="submit" class="form-control btn-primary col-12 col-sm-2 mb-2 mx-0" value="Fetch">
    						</form> 
						</div>
					</div>
                </div>
            </div>
                    
            <!-- START ADMIN CONTAINER -->
            <div class="container flex-grow my-4">
        	    <div class="row p-0 m-0">
        	        
        	        <!-- Main Container -->
            		<div class="col-12 text-left">
        					<div class="card"> 
        						<div class="card-header">
        							<h4 class="m-0"><i class="fas fa-mouse-pointer"></i> <?= ($_POST['log']) ? ucwords(str_replace('-',' ',$_POST['log'])) : 'Action';?> Log</h4>
        						</div>
        						<div class="card-body">
                        	        <table id="logs" class="table table-sm table-striped table-responsive-lg" style="font-size:14px">
                            			<thead>
                            				<th scope="col">Date</th>
                            				<th scope="col">Address</th>
                            				<th scope="col">IP</th>
                            				<th scope="col">Status</th>
                            				<th scope="col" data-sortable="false">Notes</th>
                            			</thead>
                            
                            			<tbody>
                            
                            				<?php $logs = $db->query("SELECT * FROM `logs-".$faucetID."` WHERE `type` = '".(($_POST['log'])?: 'action')."' ORDER BY `id` DESC"); 
                            				
                            				while($row = $logs->fetch_assoc()){
                            				   echo '<tr>';
                            						echo '<td scope="row">'.$row['timestamp'].'</td>'; #date
                            						echo '<td scope="row" class="text-break"><a href="#">'.$row['address'].'</a></td>'; #address
                            						echo '<td scope="row" class="text-break"><a href="http://iphub.info/?ip='.$row['ip'].'" target="_blank">'.$row['ip'].'</a></td>'; #ip
                            						echo '<td scope="row">'.$row['status'].'</td>'; #status
                            						echo '<td scope="row" class="text-break">'.$row['notes'].'</td>'; #notes
                            				   echo '</tr>';
                            				}
                            
                            				?>
                            			</tbody>
                            		</table>
                            	</div>
                            </div>
                        </div>
        	        
        	    </div>      
            </div> <!-- ./ ADMIN CONTAINER -->
        
        <?php } else { ?>
            <!-- START ADMIN LOGIN CONTAINER -->
            <div class="container flex-grow my-4">
        	    <div class="row p-0 m-0">
    		        <!-- Main Container -->
            		<div class="mx-auto mt-5 text-center">
            			<?= $error;?>
            			<div class="card">
            				<div class="card-header text-center">
            					Admin Login
            				</div>
            				<div class="card-body text-center">
            					<form action="" method="post">
            						<div class="input-group">
            							<div class="input-group-prepend">
            							    <span class="input-group-text"><i class="fa fa-lock"></i></span>
            							</div>
            							<input type="password" class="form-control" name="password" placeholder="Password" id="password" pattern=".{8,}" required>
            						</div>
            						<div class="form-group my-2">
            							<button type="submit" class="btn btn-primary btn-block">Login</button>
            						</div>
            							
            					</form>
            				</div>
            			</div>
            		</div>
            	</div>
            </div> <!-- ./ ADMIN LOGIN CONTAINER -->
        <?php } ?>
        
        <!-- Footer -->
        <footer class="py-3">
            <div class=" text-center">
                <div class="col-12">
                    Copyright© 2016-<?= date('Y');?> <b>GR8 Faucet Script Lite</b><br>
                    Purchase the <a href="https://gr8.cc" target="_blank">GR8 Faucet Script</a> for more features and improved security!<br>
                    <small>Server Time: <?= dateNow();?></small>
                </div>
            </div>
        </footer>
        
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <!-- Popper -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <!-- DataTables -->
	    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>

    	<script>
    	    
    	    
    	  $(function() {

        	  $('#logs').DataTable({
        			'pageLength': 10,
        			"order": []
        		});
        		$(' <div id="refresh" style="display: inline-block;margin-left: 6px;"><a href="javascript:void(0);">Reset</a></div>' ).appendTo( "#logs_filter label" );
        		$('#logs tbody').on('click', 'a', function(e) {
        			e.preventDefault();
        			$('#logs_filter input[type="search"]').val($(this).text()).keyup();
        			});
        		$('#refresh').on('click', function(e) {
        			$('#logs_filter input[type="search"]').val('').keyup();
        			});
    			
    	  })
        </script>
    	
    </body>
</html>

<?php 
#print_pre($_SESSION);
$db->close();
