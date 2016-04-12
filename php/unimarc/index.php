<?php


/*

@@@@@@@   @@@@@@@        @@@@@@@   @@@@@@@   @@@  @@@  @@@  @@@@@@@
@@@@@@@@  @@@@@@@@       @@@@@@@@  @@@@@@@@  @@@  @@@@ @@@  @@@@@@@
@@!  @@@  @@!  @@@       @@!  @@@  @@!  @@@  @@!  @@!@!@@@    @@!
!@!  @!@  !@   @!@       !@!  @!@  !@!  @!@  !@!  !@!!@!@!    !@!
@!@  !@!  @!@!@!@        @!@@!@!   @!@!!@!   !!@  @!@ !!@!    @!!
!@!  !!!  !!!@!!!!       !!@!!!    !!@!@!    !!!  !@!  !!!    !!!
!!:  !!!  !!:  !!!       !!:       !!: :!!   !!:  !!:  !!!    !!:
:!:  !:!  :!:  !:!  :!:  :!:       :!:  !:!  :!:  :!:  !:!    :!:
 :::: ::   :: ::::  :::   ::       ::   :::   ::   ::   ::     ::
:: :  :   :: : ::   :::   :         :   : :  :    ::    :      :

*/

// !!!!!!!!!!!!!!!!!!!!!!!
// => NOM DU FICHIER UNIMARC
// si il est dans un dossier il faut mettre le nom du dossier avec un slash
// (toujours par rapport à la position du fichier de script)
// ainsi pour les notices dans le dossier « fichiers » :
// $notice_unimarc = "fichiers/nom_de_la_notice.mrc";

$notice_unimarc = "HEADBAROQUE.mrc";


// on inclue la librairie MARC.php
require_once("MARC.php");
// on inclue les fonctions de décodages du format iso-2709
require_once("iso2709_decode.php");


// on ouvre le fichier de notice dont on précisé le chemin
$f = @fopen($notice_unimarc, 'rb');

// on crée l'analyseur de fichier unimarc (basé sur la librairie MARC.php)
$p = new MarcParser();


// on crée la variable $xml
$xml  = "";
$xml .= "<livre>\n";


// on parcours le fichier de notices
while($buf = fread($f, 8192)){
	$err = $p->parse($buf);

	// on vérifie qu'il n'y a pas d'erreur dans le fichier unimarc
	if(is_a($err, 'MarcParseError')){
		die("Bad MARC record, giving up: ".$err->toStr());
	}

	// on stocke dans le xml le résultat de la fonction print_recs()
	// elle est appelée pour chaque notice trouvée
	$xml .= print_recs( $p->records );
	$p->records = array();
}

$p->eof();


// on ferme la balise xml
$xml .= "</livre>\n";


// on nettoie les esperluettes &, en les remplaçant par &amp;
// (pour éviter d'avoir une erreur indesign à l'importation)
$xml = str_replace('&', '&amp;', $xml);

// on enregistre le contenu de la variable $xml dans le fichier lelivre.xml
file_put_contents("lelivre.xml", $xml);

// on affiche dans la console le fait que le script est terminé
echo "\n!!!! fin\n";





/**
 * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 */

// fonction qui sert à analyser une notice
function print_recs($recs) {
	
	$retour = ""; 

	// pour chaque zone/champ trouvée dans la notice
	foreach($recs as $rec){

		/**
		 * 
		 * iso2709_decode()	-> sert à bien décoder les accents
		 * \n 			-> retour à la ligne
		 * \t 			-> tabulation
		 * 
		 */

		// la variable $retour sert à stocker les différents éléments d'une notice
		$retour .= "\t<notice>\n";


		// un bloc foreach sert ici à analyser les différentes zones et sous zones
		// du format unimarc
		foreach($rec->getValues('200$a') as $val){

			// echo sert à afficher dans le terminal
			echo '200$a : '.iso2709_decode($val)."\n";

			// on met dans $retour tout ce que l'on veut dans son fichier xml
			$retour .= "\t\t<titre>".iso2709_decode($val)."</titre>\n";
		}

		// pour chaque sous-zone souhaitée, on dublique le bloc foreach
		foreach($rec->getValues('200$e') as $val){
			echo '200$e : '.iso2709_decode($val)."\n";

			$retour .= "\t\t<b200e>".iso2709_decode($val)."</b200e>\n";
		}

		foreach($rec->getValues('010$a') as $val){
			// echo '010$a : '.iso2709_decode($val)."\n";

			$retour .= "\t\t<isbn>".iso2709_decode($val)."</isbn>\n";


		}
		foreach($rec->getValues('200$b') as $val){
			// echo '200$b : '.iso2709_decode($val)."\n";
			$retour .= "\t\t<b200b>".iso2709_decode($val)."</b200b>\n";
		}
		foreach($rec->getValues('200$c') as $val){
			// echo '200$c : '.iso2709_decode($val)."\n";

			$retour .= "\t\t<b200c>".iso2709_decode($val)."</b200c>\n";
		}
		foreach($rec->getValues('200$d') as $val){
			// echo '200$d : '.iso2709_decode($val)."\n";

			$retour .= "\t\t<b200d>".iso2709_decode($val)."</b200d>\n";
		}
		foreach($rec->getValues('200$e') as $val){
			// echo '200$e : '.iso2709_decode($val)."\n";

			$retour .= "\t\t<b200e>".iso2709_decode($val)."</b200e>\n";
		}
		foreach($rec->getValues('200$f') as $val){
			// echo '200$f : '.iso2709_decode($val)."\n";


			$retour .= "\t\t<b200f>".iso2709_decode($val)."</b200f>\n";
		}
		foreach($rec->getValues('200$g') as $val){
			// echo '200$g : '.iso2709_decode($val)."\n";

			$retour .= "\t\t<b200g>".iso2709_decode($val)."</b200g>\n";
		}

		foreach($rec->getValues('205$a') as $val){
			// echo '200$g : '.iso2709_decode($val)."\n";

			$retour .= "\t\t<b205a>".iso2709_decode($val)."</b205a>\n";
		}

		foreach($rec->getValues('330$a') as $val){
			// echo '200$g : '.iso2709_decode($val)."\n";

			$retour .= "\t\t<b330a>".iso2709_decode($val)."</b330a>\n";
		}

		$retour .= "\t</notice>\n";


		// pour afficher une séparation dans la console entre chaque notice
		echo "----\n";		

	}

	// on renvoie le contenu de $retour
	return $retour;
}

