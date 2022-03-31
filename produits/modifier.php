<?php
// headers requis (necessaire pour les controls et autorisations)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); // Contenu de la réponse
header("Access-Control-Allow-Methods: PUT"); // Methodes utilistées pour la requête (en Rest on utilise GET pour lire)
header("Access-Control-Max-Age: 3600"); // Durée de vie de la requête
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Les headers autorisés vis à vis du POST client

// On vérifie la méthode
if($_SERVER['REQUEST_METHOD'] == 'PUT'){

  // On inclut les fichiers de configuration et d'accès aux données
  include_once '../config/Database.php';
  include_once '../models/Produits.php';

  // On instancie la base de données
  $database = new Database();
  $db = $database->getConnection();

  //On instancie les produits
  $produit = new Produits($db);

  // On récupère les informations envoyées
  $donnees = json_decode(file_get_contents("php://input"));

  if(!empty($donnees->id) && !empty($donnees->nom) && !empty($donnees->description) && !empty($donnees->prix) && !empty($donnees->categories_id)){
    // Ici on a reçu les données
    // On hydrate notre objet
    $produit->id = $donnees->id;
    $produit->nom = $donnees->nom;
    $produit->description = $donnees->description;
    $produit->prix = $donnees->prix;
    $produit->categories_id = $donnees->categories_id;

    if($produit->modifier()){
      // Ici la modification a fonctionné
      // On envoie un code 200 (methode PUT -> modification)
      http_response_code(200);
      echo json_encode(["message" => "La modification a été effectuée"]);
    }else{
      // Ici la modification n'a pas fonctionné
      // On envoie un code 503
      http_response_code(503);
      echo json_encode(["message" => "La modification n'a pas été effectuée"]);
    }
  }

}else{
  // On gère l'erreur
  http_response_code(405);
  echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
