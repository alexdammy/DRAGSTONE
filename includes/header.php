<?php
// header.php
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dragonstone Store</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link 
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="<?= url('index.php') ?>">🐉 
Dragonstone</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="<?= 
url('catalog.php') ?>">Catalog</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Cart (coming 
soon)</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
