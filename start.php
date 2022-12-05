<?php

// $name = time() . $_SERVER['REMOTE_ADDR'];


$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$id = substr(str_shuffle($chars), 0, 16) .  time();

session_ID($id);
session_start();


$dir = session_id();
		
// Make folder if not already made
if(is_dir($dir) === false) {
	mkdir($dir);
}


$settings_file = $dir . '/settings_file_' . $dir;
file_put_contents($settings_file,'|', FILE_APPEND|LOCK_EX);


// write time to settings file
file_put_contents($settings_file, time() . '|', FILE_APPEND|LOCK_EX);


// Required variables
if($_POST['project_name']) {
	$_SESSION['project_name'] = $_POST['project_name'];
	htmlspecialchars($project_name, ENT_QUOTES, 'UTF-8');
	$project_name = $_POST['project_name'];
	// write $project_name to project_settings_$dir 
	file_put_contents($settings_file, $project_name . '|', FILE_APPEND|LOCK_EX);
} else {
	errorMissing("Project Name");
}

if($_POST['default_shell']) {
	$_SESSION['default_shell'] = $_POST['default_shell'];
	htmlspecialchars($default_de, ENT_QUOTES, 'UTF-8');
	$default_de = $_POST['default_de'];
	// write to project_settings_$dir 
	file_put_contents($settings_file, $default_de . '|', FILE_APPEND|LOCK_EX);
} else {
	errorMissing("Default Desktop Environment");
}


// Not required variables but will be customized regardless
if($_POST['default_hostname']) {
	$_SESSION['default_hostname'] = $_POST['default_hostname'];
	htmlspecialchars($default_hostname, ENT_QUOTES, 'UTF-8');
	$default_hostname = $_POST['default_hostname'];
	// write to project_settings_$dir
	file_put_contents($settings_file, $default_hostname . '|', FILE_APPEND|LOCK_EX);
} else {
	$default_hostname = "jovarkos-maker";
	file_put_contents($settings_file, $default_hostname . '|', FILE_APPEND|LOCK_EX);
}


// Optional variables
if($_POST['username']) {
	$_SESSION['username'] = $_POST['username'];
	htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
	$username = $_POST['username'];
	// write to project_settings_$dir
	file_put_contents($settings_file, $username . '|', FILE_APPEND|LOCK_EX);
}

if($_POST['dns_servers']) {
	$_SESSION['dns_servers'] = $_POST['dns_servers'];
	htmlspecialchars($htmlspecialchars, ENT_QUOTES, 'UTF-8');
	$dns_servers = $_POST['dns_servers'];
	// write to project_settings_$dir	
	file_put_contents($settings_file, $dns_servers . '|', FILE_APPEND|LOCK_EX);
}

if($_POST['default_shell']) {
	$_SESSION['default_shell'] = $_POST['default_shell'];
	htmlspecialchars($default_shell, ENT_QUOTES, 'UTF-8');
	$default_shell = $_POST['default_shell'];
	// write to project_settings_$dir
	file_put_contents($settings_file, $default_shell . '|', FILE_APPEND|LOCK_EX);
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
	
	// Make into array using the space delimiters
	explode($install_packages," ");

	$_SESSION['install_packages'] = $install_packages;

	
	// write to project_settings_$dir
	file_put_contents($settings_file, $install_packages . '|', FILE_APPEND|LOCK_EX);

	
	foreach ($install_packages as $package) {

		$package_file_path = $dir . "/packages.x86_64";
		// Add newline character 
		$package += "\n";
		// Append data to file and prevent others from writing to file at the same time
		file_put_contents($package_file_path, $package, FILE_APPEND | LOCK_EX);

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