<?php
// headers requis (necessaire pour les controls et autorisations)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); // Contenu de la réponse
header("Access-Control-Allow-Methods: POST"); // Methodes utilistées pour la requête (en Rest on utilise GET pour lire)
header("Access-Control-Max-Age: 3600"); // Durée de vie de la requête
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Les headers autorisés vis à vis du POST client

// On vérifie la méthode
if($_SERVER['REQUEST_METHOD'] == 'POST'){

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

  if(!empty($donnees->nom) && !empty($donnees->description) && !empty($donnees->prix) && !empty($donnees->categories_id)){
    // Ici on a reçu les données
    // On hydrate notre objet
    $produit->nom = $donnees->nom;
    $produit->description = $donnees->description;
    $produit->prix = $donnees->prix;
    $produit->categories_id = $donnees->categories_id;

    if($produit->creer()){
      // Ici la création a fonctionné
      // On envoie un code 201 (methode POST -> ajout)
      http_response_code(201);
      echo json_encode(["message" => "L'ajout a été effectué"]);


      // require our config file
      require_once '../sendgrid/config.php';

      // require composer autoload so we can use sendgrid
      require '../sendgrid/vendor/autoload.php';

      // create new sendgrid mail
      $email = new \SendGrid\Mail\Mail();

      // specify the email/name of where the email is coming from
      $email->setFrom( FROM_EMAIL, FROM_NAME );

      // set the email subject line
      $email->setSubject( "Un nouveau produit vient d'être ajouté" );

      // specify the email/name we are sending the email to
      $email->addTo( TO_EMAIL, TO_NAME );

      // add our email body content
      $email->addContent( "text/plain", "Vous venez d'ajouter un produit" );
      $email->addContent(
          "text/html", "<strong>c'est facile, vous voyez !</strong>"
      );

      // create new sendgrid
      $sendgrid = new \SendGrid( SENDGRID_API_KEY );

      try {
        // try and send the email
          $response = $sendgrid->send( $email );

          // print out response data
          print $response->statusCode() . "\n";
          print_r( $response->headers() );
          print $response->body() . "\n";
      } catch ( Exception $e ) {
        // something went wrong so display the error message
          echo 'Caught exception: '. $e->getMessage() ."\n";
      }

    }else{
      // Ici la création n'a pas fonctionné
      // On envoie un code 503
      http_response_code(503);
      echo json_encode(["message" => "L'ajout n'a pas été effectué"]);
    }
  }

}else{
  // On gère l'erreur
  http_response_code(405);
  echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
