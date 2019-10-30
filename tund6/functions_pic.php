<?php
function makeImage($picFile, $imageFileType){
	if($imageFileType == "jpg" or $imageFileType == "jpeg"){
		$myTempImage = imagecreatefromjpeg($picFile);
	}
	if($imageFileType == "png"){
		$myTempImage = imagecreatefrompng($picFile);
	}
	if($imageFileType == "gif"){
		$myTempImage = imagecreatefromgif($picFile);
	}
	return $myTempImage;
}

function setPicSize($myTempImage, $picSizeRatio){
	$picW = imagesx($myTempImage);
	$picH = imagesy($myTempImage);
	$picNewW = round($picW / $picSizeRatio, 0);
	$picNewH = round($picH / $picSizeRatio, 0);
	$newImage = imagecreatetruecolor($picNewW, $picNewH);
	imagecopyresampled($newImage, $myTempImage, 0, 0, 0, 0, $picNewW, $picNewH, $picW, $picH);
	return $newImage;
}

function saveImage($myNewImage, $targetFile, $imageFileType){
	$notice = null;
	if($imageFileType == "jpg" or $imageFileType == "jpeg"){
		if(imagejpeg($myNewImage, $targetFile, 90)){
			$notice = "Vähendatud pilt edukalt salvestatud!";
		} else {
			$notice = "Vähendatud pildi salvestamine ebaõnnestus!";
		}
	}
	if($imageFileType == "png"){
		if(imagepng($myNewImage, $targetFile, 6)){
			$notice = "Vähendatud pilt edukalt salvestatud!";
		} else {
			$notice = "Vähendatud pildi salvestamine ebaõnnestus!";
		}
	}
	if($imageFileType == "gif"){
		if(imagegif($myNewImage, $targetFile)){
			$notice = "Vähendatud pilt edukalt salvestatud!";
		} else {
			$notice = "Vähendatud pildi salvestamine ebaõnnestus!";
		}
	}
	return $notice;
}





