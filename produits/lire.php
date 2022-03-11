<?php

// headers requis (necessaire pour les controls et autorisations)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); // Contenu de la réponse
header("Access-Control-Allow-Methods: GET"); // Methodes utilistées pour la requête (en Rest on utilise GET pour lire)
header("Access-Control-Max-Age: 3600"); // Durée de vie de la requête
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Les headers autorisés vis à vis du POST client


// On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD'] == 'GET'){
  // On inclut les fichiers de configuration et d'accès aux données
  include_once '../config/Database.php';
  include_once '../models/Produits.php';

  // On instancie la base de données
  $database = new Database();
  $db = $database->getConnection();

  //On instancie les produits
  $produit = new Produits($db);

  // On récupère les données
  $stmt = $produit->lire();

  // On vérifie si on a au moins 1 produit
  if($stmt->rowCount() > 0){
    // On initialise un tableau associatif
    $tableauProduits = [];
    $tableauProduits['produits'] = [];

    // On parcourt les produits
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      extract($row); // Récupère sous forme de variables chaques colonnes de données

      $prod = [
        "id" => $id,
        "nom" => $nom,
        "description" => $description,
        "prix" => $prix,
        "categories_id" => $categories_id,
        "categories_nom" => $categories_nom
      ];

      $tableauProduits['produits'][] = $prod;
    }

    // On envoie le code réponse 200 OK
    http_response_code(200);
    // On encode en json et on envoie
    echo json_encode($tableauProduits);
  }

}else{
  // On gère l'erreur
  http_reponse_code(405);
  echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
