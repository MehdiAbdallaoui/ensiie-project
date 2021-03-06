<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="fr">

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>AnnoncIIE</title>

	<!-- CSS Bootstrap -->
	<link href="../../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">




	<!-- CSS customisé -->
	<link href="../../../assets/css/annonce.css" rel="stylesheet">
	<link href="../../../assets/css/profil.css" rel="stylesheet">
	<!-- CSS Dropzone -->
	<link href="../../../assets/css/dropzone.css" rel="stylesheet">

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

	<!-- table utilisateurs -->
	<link href="../../../assets/css/icon.css" rel="stylesheet">
	<link href="../../../assets/css/tableUser.css" rel="stylesheet">
	<link href="../../../assets/css/font-awesome.min.css" rel="stylesheet">
	<link href="../../../assets/css/valera.css" rel="stylesheet">
	<!-- JS Dropzone -->
	<script src="../../../assets/js/dropzone.js" type="text/javascript" ></script>

	<!-- JS Dropzone -->
	<script src="../../../assets/jquery/jquery.min.js" type="text/javascript" ></script>

	<script src="../../../assets/js//bootstrap.min.js"></script>
		<!-- Stylesheet -->

	<link href="../../../assets/css/tagsinput.css" rel="stylesheet" type="text/css">
	<script src="../../../assets/js/tagsinput.js" type="text/javascript" ></script>

</head>

<body>
    <?php $this->load->view('elements/menu');?>
	<?php
        if($this->session->flashdata('message')!=null)
            echo '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$this->session->flashdata('message').'</div>';
        if($this->session->flashdata('error')!=null)
            echo '<div class="alert alert-danger alert-dismissible fade-show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$this->session->flashdata('error').'</div>';
    ?>

