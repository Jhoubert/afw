<?php
	session_start();
	$this->sessionUnset();
	header("location:login.html?logout=success");
?>