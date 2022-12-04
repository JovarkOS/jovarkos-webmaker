<?php

// Unix time and last three digits of client's IP, plus the last period of the IP addr

$name = time() . substr($_SERVER['REMOTE_ADDR'], -4);

session_name($name);
session_start();


// Required variables
if($_GET['project_name']) {
	$_SESSION['project_name'] = $_GET['project_name'];
	htmlspecialchars($project_name, ENT_QUOTES, 'UTF-8');
	$project_name = $_GET['project_name'];
} else {
	errorMissing("Project Name");
}

if($_GET['default_shell']) {
	$_SESSION['default_shell'] = $_GET['default_shell'];
	htmlspecialchars($default_de, ENT_QUOTES, 'UTF-8');
	$default_de = $_GET['default_de'];
} else {
	errorMissing("Default Desktop Environment");
}


// Not required variables but will be customized regardless
if($_GET['default_hostname']) {
	$_SESSION['default_hostname'] = $_GET['default_hostname'];
	htmlspecialchars($default_hostname, ENT_QUOTES, 'UTF-8');
	$default_hostname = $_GET['default_hostname'];
} else {
	$default_hostname = "jovarkos-maker";
}


// Optional variables
if($_GET['username']) {
	$_SESSION['username'] = $_GET['username'];
	htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
	$username = $_GET['username'];
}

if($_GET['dns_servers']) {
	$_SESSION['dns_servers'] = $_GET['dns_servers'];
	htmlspecialchars($htmlspecialchars, ENT_QUOTES, 'UTF-8');
	$dns_servers = $_GET['dns_servers'];
}

if($_GET['default_shell']) {
	$_SESSION['default_shell'] = $_GET['default_shell'];
	htmlspecialchars($default_shell, ENT_QUOTES, 'UTF-8');
	$default_shell = $_GET['default_shell'];
}

if($_GET['install_packages']) {
	
	$install_packages = $_GET['install_packages'];

	htmlspecialchars($install_packages, ENT_QUOTES, 'UTF-8');

	// Convert semicolons into spaces for explosion
	str_replace(";"," ",$install_packages);
	// Convert commas into spaces for explosion
	str_replace(","," ",$install_packages);
	// Convert newlines into spaces for explosion
	str_replace("\n"," ",$install_packages);
	
	// Make into array using the space delimiters
	explode($install_packages," ");

	$_SESSION['install_packages'] = $install_packages;

	foreach ($install_packages as $package) {
		$dir = session_name();
		
		// Make folder if not already made
		if(is_dir($dir) === false) {
			mkdir($dir);
		}

		$package_file_path = $dir . "/packages.x86_64";
		// Add newline character 
		$package += "\n";
		// Append data to file and prevent others from writing to file at the same time
		file_put_contents($package_file_path, $package, FILE_APPEND | LOCK_EX);

	}
}

// Helper functions for variables 
function errorMissing($missingVariable) {
	// Stop page from loading and show errors
	echo "\"" . $missingVariable . "\" has not been received by the server.";
	stop();
}

