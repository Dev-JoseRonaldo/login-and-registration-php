<?php

function get_random_string($length) {
  // Define o conjunto de caracteres permitidos (0-9, a-z, A-Z)
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';

  // Gera uma string aleatória
  for ($i = 0; $i < $length; $i++) {
      $randomIndex = random_int(0, $charactersLength - 1);
      $randomString .= $characters[$randomIndex];
  }

  return $randomString;
}


function esc($word) {
  return addslashes($word);
}