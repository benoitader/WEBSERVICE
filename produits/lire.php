<?php

// headers requis (necessaire pour les controls et autorisations)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); // Contenu de la réponse
header("Access-Control-Allow-Methods: GET"); // Methodes utilistées pour la requête (en Rest on utilise GET pour lire)
header("Access-Control-Max-Age: 3600"); // Durée de vie de la requête
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Les headers autorisés vis à vis du POST client


// On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD'] = 'GET'){
  
}else{
  // On gère l'erreur
  http_reponse_code(405);
  echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
