<?php

$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$id = 'session' . substr(str_shuffle($chars), 0, 16) .  time();

session_ID($id);
session_start();


$dir = session_id();
		
if(is_dir($dir) === false) {
	mkdir($dir);
}


$settings_file = $dir . '/settings_file_' . $dir;
file_put_contents($settings_file,'|',FILE_APPEND|LOCK_EX);


// write time to settings file
file_put_contents($settings_file,time() . '|',FILE_APPEND|LOCK_EX);


// Required variables
if($_POST['project_name']) {
	$_SESSION['project_name'] = $_POST['project_name'];
	htmlspecialchars($project_name, ENT_QUOTES, 'UTF-8');
	$project_name = $_POST['project_name'];
	file_put_contents($settings_file,$project_name . '|',FILE_APPEND|LOCK_EX);
} else {
	errorMissing("Project Name");
}

if($_POST['default_shell']) {
	$_SESSION['default_shell'] = $_POST['default_shell'];
	htmlspecialchars($default_de, ENT_QUOTES, 'UTF-8');
	$default_de = $_POST['default_de'];
	file_put_contents($settings_file,$default_de . '|',FILE_APPEND|LOCK_EX);
} else {
	errorMissing("Default Desktop Environment");
}


// Not required variables but will be customized regardless
if($_POST['default_hostname']) {
	$_SESSION['default_hostname'] = $_POST['default_hostname'];
	htmlspecialchars($default_hostname, ENT_QUOTES, 'UTF-8');
	$default_hostname = $_POST['default_hostname'];
	file_put_contents($settings_file,$default_hostname . '|',FILE_APPEND|LOCK_EX);
} else {
	$default_hostname = "jovarkos-maker";
	file_put_contents($settings_file,$default_hostname . '|',FILE_APPEND|LOCK_EX);
}


// Optional variables
if($_POST['username']) {
	$_SESSION['username'] = $_POST['username'];
	htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
	$username = $_POST['username'];
	file_put_contents($settings_file,$username . '|',FILE_APPEND|LOCK_EX);
}

if($_POST['dns_servers']) {
	$_SESSION['dns_servers'] = $_POST['dns_servers'];
	htmlspecialchars($htmlspecialchars, ENT_QUOTES, 'UTF-8');
	$dns_servers = $_POST['dns_servers'];
	file_put_contents($settings_file,$dns_servers . '|',FILE_APPEND|LOCK_EX);
}

if($_POST['default_shell']) {
	$_SESSION['default_shell'] = $_POST['default_shell'];
	htmlspecialchars($default_shell, ENT_QUOTES, 'UTF-8');
	$default_shell = $_POST['default_shell'];
	file_put_contents($settings_file,$default_shell . '|',FILE_APPEND|LOCK_EX);
}

if($_POST['install_packages']) {
	
	$install_packages = $_POST['install_packages'];

	htmlspecialchars($install_packages, ENT_QUOTES, 'UTF-8');

	// Convert semicolons into spaces for explosion
	str_replace(";"," ",$install_packages);
	// Convert commas into spaces for explosion
	str_replace(","," ",$install_packages);
	// Convert newlines into spaces for explosion
	str_replace("\n"," ",$install_packages);
	
	
	$_SESSION['install_packages'] = $install_packages;

	file_put_contents($settings_file,$install_packages . '|',FILE_APPEND|LOCK_EX);
	
	// Make into array using the space delimiters so that we can loop through it
	$install_packages_array = explode(" ",$install_packages);
	

	foreach($install_packages_array as $package) {

		$package_file_path = $dir . '/packages.x86_64';
		
		file_put_contents($package_file_path,$package,FILE_APPEND|LOCK_EX);
	}
}

// Helper functions for variables 
function errorMissing($missingVariable) {
	echo "\"" . $missingVariable . "\" has not been received by the server.";

	// destroy session
	session_destroy();
	
	// delete $dir
	rmdir(session_id());

	// return to page referrer
	header("Location: " . $_SERVER['HTTP_REFERER']);

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description"
		content="JovarkOS is an Arch-based GNU/Linux distribution offering stability, usability, and speed, in that order. Join us today!" />
	<meta name="author" content="JovarkOS Development Team, Lucas Burlingham" />
	<title>Review Settings | JovarkOS WebMaker</title>
	<!-- Favicon-->
	<link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
	<!-- FontAwesome 5.15.3 CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
		integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
		crossorigin="anonymous" referrerpolicy="no-referrer" defer />

	<!-- (Optional) Use CSS or JS implementation -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"
		integrity="sha512-RXf+QSDCUQs5uwRKaDoXt55jygZZm2V++WUZduaU/Ui/9EGp3f/2KZVahFZBKGH0s774sd3HmrhUy+SgOFQLVQ=="
		crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
	<!-- Bootstrap icons-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
	<!-- Core theme CSS (includes Bootstrap)-->
	<link href="css/styles.css" rel="stylesheet" />
	<!-- <link rel="canonical" href="https://jovarkos.org/index.html" /> -->

</head>

<body class="d-flex flex-column h-100" id="body">
	<main class="flex-shrink-0">
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<div class="container px-5">
				<a class="navbar-brand" href="index.html">JovarkOS <span class="text-muted">WebMaker</span></a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
					data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
					aria-expanded="false" aria-label="Toggle navigation"><span
						class="navbar-toggler-icon"></span></button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
						<li class="nav-item"><a class="nav-link active" href="https://jovarkos.org/">Main Page</a></li>
						<li class="nav-item"><a class="nav-link" href="https://github.com/jovarkos">
								<img src="assets/badge.svg" alt="View on GitHub">
							</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- Call to action-->
		<div class="container">
			<div class="row mb-5 mt-2">
				<h1 class="text-center mb-3">Lets review...</h1>
				<div class="col-1"></div>
				<div class="col-10">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th scope="col">Project Name</th>
								<th scope="col">Default Hostname</th>
								<th scope="col">Non-root Username</th>
								<th scope="col">DNS Servers</th>
								<th scope="col">Default Shell</th>
								<th scope="col">Default Desktop Environment</th>
								<th scope="col">Packages to install</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $project_name; ?></td>
								<td><?php echo $default_hostname; ?></td>
								<td><?php echo $username; ?></td>
								<td><?php echo $dns_servers; ?></td>
								<td><?php echo $default_shell; ?></td>
								<td><?php echo $default_de; ?></td>
								<td><?php echo $install_packages; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-1"></div>
			</div>
			<div class="row">
				<div class="col">
					<form action="createISO.php" method="POST">

					</form>
				</div>
			</div>
		</div>
		</section>
	</main>
	<!-- Footer-->
	<footer class="bg-dark py-4 mt-auto">
		<div class="container px-5">
			<div class="row align-items-center justify-content-between flex-column flex-sm-row">
				<div class="col-auto">
					<div class="small m-0 text-white">Copyright &copy; JovarkOS Development Team 2022 <span
							class="text-muted">Site Licensed under MIT</span> </div>
				</div>
				<div class="col-auto">
					<a class="link-light small" href="#!">Privacy</a>
					<span class="text-white mx-1">&middot;</span>
					<a class="link-light small" href="#!">Terms</a>
					<span class="text-white mx-1">&middot;</span>
					<a class="link-light small" href="#!">Contact</a>
				</div>
			</div>
		</div>
	</footer>
	<!-- Bootstrap core JS-->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>