<?php
// ADRESSE DE LA LIBRAIRIE UTILISÉE POUR ANALYSER LE CSV
// https://github.com/parsecsv/parsecsv-for-php

// on importe la librairie « parsecsv-for-php »
// le fichier php de celle-ci est placé dans le même dossier que le fichier « index.php »
require_once 'parsecsv.lib.php';


// on crée un objet $csv qui sert à instancier la librairie « parsecsv-for-php »
// en paramètre on passe l'adresse du fichier que l'on souhaite analyser (ici « openoffice.csv »)
// le fichier doit être dans le même dossier que vorte script « index.php »
$csv = new parseCSV('openoffice.csv');


// on crée une variable « $xml » qui va servir à stocker en mémoire
// les informations que l'on souhaite exporter
$xml = "<donnee>\n";


// on parcours toutes les lignes du fichier csv
// on peut l'analyser à travers les titres des colonnes
foreach($csv->data as $ligne){
	// pour controler on affiche juste dans le terminal le titre et l'auteur
	echo $ligne['Titre']." ==> ".$ligne['Auteur']."\n";

	// dans la varialbe « $xml » on ajoute les champs qui nous intéressent
	// !!! attention à la différence entre « = » et « .= »
	$xml .= "<livre>";
	$xml .= "<titre>".$ligne['Titre']."</titre>\n";
	$xml .= "<cote>".$ligne['Cote']."</cote>\n";
	$xml .= "<auteur>".$ligne['Auteur']."</auteur>\n";
	$xml .= "</livre>\n";

}


// le xml étant un langage basé sur des balises
// on cloture la variable « $xml » avec une balise fermante
$xml .= "</donnee>\n";


// on place dans le fichier nommé « csv-analyse.xml »
// le contenu texte de la variable « $xml »
file_put_contents("csv-analyse.xml",$xml);