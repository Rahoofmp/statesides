<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->config =& get_config();

if (isset($_SERVER['SERVER_ADDR']))
{
	if (strpos($_SERVER['SERVER_ADDR'], ':') !== FALSE)
	{
		$server_addr = '['.$_SERVER['SERVER_ADDR'].']';
	}
	else
	{
		$server_addr = $_SERVER['SERVER_NAME'];
	}

	$base_url = (is_https() ? 'https' : 'http').'://'.$server_addr
	.substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
}
else
{
	$base_url = 'http://localhost/';
} 
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head> 
	<title>Page Not Found</title>
	<link rel="apple-touch-icon" href="<?= $base_url ?>assets/images/logo/default_logo.png"> 
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet"> 
	<link rel="stylesheet" type="text/css" href="<?= $base_url ?>assets/css/vendors.min.css"> 
	<link rel="stylesheet" type="text/css" href="<?= $base_url ?>assets/css/app.min.css"> 
	<link rel="stylesheet" type="text/css" href="<?= $base_url ?>assets/css/pages/error.min.css"> 
</head>
<body class="vertical-layout vertical-menu-modern 1-column   menu-expanded blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column"> 
	<div class="app-content content">
		<div class="content-wrapper">
			<div class="content-header row">
			</div>
			<div class="content-body"><section class="flexbox-container">
				<div class="col-12 d-flex align-items-center justify-content-center">
					<div class="col-md-6 col-10 p-0">
						<div class="card-header bg-transparent border-0">
							<h2 class="error-code text-center mb-2">500</h2>
							<h3 class=" text-center"><?= $message; ?></h3>
						</div>
						<div class="card-content"> 
							<div class="row py-2">
								<div class="col-12 col-sm-12 ">
									<a href="<?= $base_url ?>" class="btn btn-primary btn-block"><i class="ft-home"></i> Back to Home</a>
								</div> 
							</div>
						</div>
						<div class="card-footer bg-transparent">
							<div class="row">
								<p class="text-muted text-center col-12 py-1">© <?=date('Y') ?>  </p>
							</div>
						</div>
					</div>
				</div>
			</section>

		</div>
	</div>
</div>


</body>
</html>