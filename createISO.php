<?php

session_start();

var_export($_SESSION);

$id = $_SESSION['id'];

// copy releng files to id directory
$src = "/usr/share/archiso/configs/releng/";
$dest = "./$id/archlive";

// Don't copy if directory already exists (i.e if user refreshes at the wrong time)
if(is_dir($dest) === false) {
	shell_exec("cp -r $src $dest");
}

// Append $id/packages.x86_64 to $id/archlive/packages.x86_64 (i.e. add user's packages)
$packages = file_get_contents("./$id/packages.x86_64");
file_put_contents("$dest/packages.x86_64", $packages, FILE_APPEND);


// Delete $id/packages.x86_64
unlink("./$id/packages.x86_64");


createProfiledef();
modifyGrubFiles();
modifySyslinuxFiles();
modifyEfibootLoaderEntriesFiles();
modifyDefaultHostname();

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

	file_put_contents("./" . $_SESSION['id'] . "/archlive/profiledef.sh", $contents);
	unset($contents);
}

function modifySyslinuxFiles() {
	// foreach file in syslinux directory
	$dir = "./" . $_SESSION['id'] . "/archlive/syslinux";
	$files = scandir($dir);
	
	foreach($files as $file) {
		if($file == "." || $file == "..") {
			continue;
		}
		$contents = file_get_contents("$dir/$file");
		$contents = str_replace("Arch Linux", $_SESSION['project_name'], $contents, LOCK_EX);
		file_put_contents("$dir/$file", $contents);
	}

	file_put_contents("./" . $_SESSION['id'] . "/archlive/profiledef.sh", $contents, LOCK_EX);
	unset($contents);
	unset($dir);
	unset($files);
}

function modifyGrubFiles() {
	// Replace Arch Linux with project name
	$dir = "./" . $_SESSION['id'] . "/archlive/grub";
	$files = scandir($dir);
	
	foreach($files as $file) {
		if($file == "." || $file == "..") {
			continue;
		}
		$contents = file_get_contents("$dir/$file");
		$contents = str_replace("Arch Linux", $_SESSION['project_name'], $contents, LOCK_EX);
		file_put_contents("$dir/$file", $contents);
	}
	
	unset($contents);
	unset($dir);
	unset($files);

	// Replace archlinux with iso name
	$dir = "./" . $_SESSION['id'] . "/archlive/grub";
	$files = scandir($dir);
	
	foreach($files as $file) {
		if($file == "." || $file == "..") {
			continue;
		}
		$contents = file_get_contents("$dir/$file");
		$contents = str_replace("archlinux", $_SESSION['iso_name'], $contents, LOCK_EX);
		file_put_contents("$dir/$file", $contents);
	}

}

function modifyEfibootLoaderEntriesFiles() {
	// Replace Arch Linux with project name
	$dir = "./" . $_SESSION['id'] . "/archlive/efiboot/loader/entries";
	$files = scandir($dir);
	
	foreach($files as $file) {
		if($file == "." || $file == "..") {
			continue;
		}
		$contents = file_get_contents("$dir/$file");
		$contents = str_replace("Arch Linux", $_SESSION['project_name'], $contents, LOCK_EX);
		file_put_contents("$dir/$file", $contents);
	}
	
	unset($contents);
	unset($dir);
	unset($files);

}

function modifyDefaultHostname() {
	// Replace hostname at $id/archlive/airootfs/etc/hostname with $_SESSION['default_hostname']
	$contents = $_SESSION['default_hostname'];
	file_put_contents("./" . $_SESSION['id'] . "/archlive/airootfs/etc/hostname", $contents, LOCK_EX);

	unset($contents);
}