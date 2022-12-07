<?php

session_start();

var_export($_SESSION);

$id = $_SESSION['id'];

// copy releng files to id directory
$src = "/usr/share/archiso/configs/releng/";
$dest = __DIR__ . "$id/archlive";

// Don't copy if directory already exists (i.e if user refreshes at the wrong time)
if(is_dir($dest) === false) {
	shell_exec("cp -r $src $dest");
}

// Append $id/packages.x86_64 to $id/archlive/packages.x86_64 (i.e. add user's packages)
$packages = file_get_contents( __DIR__ . "$id/packages.x86_64");
file_put_contents("$dest/packages.x86_64", $packages, FILE_APPEND);


// Delete $id/packages.x86_64 as it is unneeded now
unlink( __DIR__ . "$id/packages.x86_64");


createProfiledef();
modifyDefaultHostname();
changeOSName();
changeBootLabel();
changeMOTD();

function createProfiledef() {
	$contents = <<<TEXT
	#!/usr/bin/env bash
	# shellcheck disable=SC2034

	iso_name="$_SESSION[project_name]"
	iso_label="\${iso_name}(date +%Y%m)"
	iso_publisher="JovarkOS WebMaker <https://jovarkos.org/>"
	iso_application="\${iso_name} Live/Rescue CD"
	iso_version="$(date +%Y.%m.%d)-\${iso_name}"
	install_dir="arch"
	buildmodes=('iso')
	bootmodes=('bios.syslinux.mbr' 'bios.syslinux.eltorito'
			'uefi-ia32.grub.esp' 'uefi-x64.grub.esp'
			'uefi-ia32.grub.eltorito' 'uefi-x64.grub.eltorito')
	arch="x86_64"
	pacman_conf="pacman.conf"
	airootfs_image_type="squashfs"
	airootfs_image_tool_options=('-comp' 'xz' '-Xbcj' 'x86' '-b' '1M' '-Xdict-size' '1M')
	file_permissions=(
	["/etc/shadow"]="0:0:400"
	["/root"]="0:0:750"
	["/root/.automated_script.sh"]="0:0:755"
	["/usr/local/bin/choose-mirror"]="0:0:755"
	["/usr/local/bin/Installation_guide"]="0:0:755"
	["/usr/local/bin/livecd-sound"]="0:0:755"
	)
	TEXT;

	file_put_contents("./" . $_SESSION['id'] . "/archlive/profiledef.sh", $contents, LOCK_EX);
}

function changeOSName() {
	$dir = __DIR__ . "/" . $_SESSION['id'] . "/archlive/";
	$files = listAllFiles($dir);
	
	foreach($files as $file) {
		if($file == "." || $file == "..") {
			continue;
		}
		$contents = file_get_contents("$file");
		$contents = str_replace("Arch Linux", $_SESSION['project_name'], $contents);
		file_put_contents("$file", $contents);
	}
}

function changeBootLabel() {
	$dir = __DIR__ . "/" . $_SESSION['id'] . "/archlive/";
	$files = listAllFiles($dir);
	
	foreach($files as $file) {
		if($file == "." || $file == "..") {
			continue;
		}
		$contents = file_get_contents("$file");
		$contents = str_replace("archlinux", $_SESSION['iso_name'], $contents);
		// Don't replace URL's
		$contents = str_replace("jovark_os.org", "archlinux.org", $contents);
		file_put_contents("$file", $contents);
	}
}

function modifyDefaultHostname() {
	// Replace hostname at $id/archlive/airootfs/etc/hostname with $_SESSION['default_hostname']
	$contents = $_SESSION['default_hostname'] . "\n";
	unlink("./" . $_SESSION['id'] . "/archlive/airootfs/etc/hostname");
	$file = "./" . $_SESSION['id'] . "/archlive/airootfs/etc/hostname";
	file_put_contents($file, $contents, LOCK_EX);
}

// From https://stackoverflow.com/questions/7765067/php-sed-like-functionality
function listAllFiles($dir) {
  $array = array_diff(scandir($dir), array('.', '..'));
 
  foreach ($array as &$item) {
    $item = $dir . $item;
  }
  unset($item);
  foreach ($array as $item) {
    if (is_dir($item)) {
     $array = array_merge($array, listAllFiles($item . DIRECTORY_SEPARATOR));
    }
  }
  return $array;
}

// function modifyDnsServers() {
// 	// Replace DNS servers at $id/archlive/airootfs/etc/resolv.conf with $_SESSION['dns_servers']
	
// 	foreach ($_SESSION['dns_servers'] as $server) {
// 		$contents = "nameserver " . $server . "\n";
// 		unlink("./" . $_SESSION['id'] . "/archlive/airootfs/etc/resolv.conf");
// 		$file = "./" . $_SESSION['id'] . "/archlive/airootfs/etc/resolv.conf";
// 		file_put_contents($file, $contents, FILE_APPEND);
// 	}
// }

function changeMOTD() {
	// Append $_SESSION['motd'] to $id/archlive/airootfs/etc/motd
	$contents = $_SESSION['motd'] . "\n";
	$file = "./" . $_SESSION['id'] . "/archlive/airootfs/etc/motd";
	file_put_contents($file, $contents, FILE_APPEND|LOCK_EX);
}