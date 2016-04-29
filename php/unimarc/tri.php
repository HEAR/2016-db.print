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


$notices = array();

// on parcours le fichier de notices
while($buf = fread($f, 8192)){
	$err = $p->parse($buf);

	// on vérifie qu'il n'y a pas d'erreur dans le fichier unimarc
	if(is_a($err, 'MarcParseError')){
		die("Bad MARC record, giving up: ".$err->toStr());
	}

	// on stocke dans le xml le résultat de la fonction print_recs()
	// elle est appelée pour chaque notice trouvée
	//$xml .= print_recs( $p->records );


	$notices = array_merge($notices, print_recs( $p->records ) );

	$p->records = array();
}

$p->eof();

// print_r($notices);
// ksort($notices);
// echo ("TRI TRI +++++++++++++++\n");
// print_r($notices);

function cmp($a, $b)
{
    if ($a->index == $b->index) {
        return 0;
    }
    return ($a->index < $b->index) ? -1 : 1;
}

usort($notices, "cmp");


$i = 0;
foreach ($notices as $key => $xml_notice) {
	echo $i." ".$xml_notice->index."\n";
	// echo $xml_notice->notice."\n";
	echo "---------------------\n";

	$xml .= $xml_notice->notice;
	$i++;
}

// on ferme la balise xml
$xml .= "</livre>\n";


// on nettoie les esperluettes &, en les remplaçant par &amp;
// (pour éviter d'avoir une erreur indesign à l'importation)
$xml = str_replace('&', '&amp;', $xml);

// on enregistre le contenu de la variable $xml dans le fichier lelivre.xml
file_put_contents("lelivre-tri.xml", $xml) ;

// on affiche dans la console le fait que le script est terminé
echo "\n!!!! fin\n";





/**
 * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 */

// fonction qui sert à analyser une notice
function print_recs($recs) {
	
	$tableau = array();

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
		$notice = "\t<notice>\n";


		// un bloc foreach sert ici à analyser les différentes zones et sous zones
		// du format unimarc
		foreach($rec->getValues('200$a') as $val){

			// echo sert à afficher dans le terminal
			// echo '200$a : '.iso2709_decode($val)."\n";

			// on met dans $retour tout ce que l'on veut dans son fichier xml
			$notice .= "\t\t<titre>".iso2709_decode($val)."</titre>\n";
		}

		// pour chaque sous-zone souhaitée, on dublique le bloc foreach
		foreach($rec->getValues('200$e') as $val){
			// echo '200$e : '.iso2709_decode($val)."\n";

			$notice .= "\t\t<b200e>".iso2709_decode($val)."</b200e>\n";
		}

		foreach($rec->getValues('010$a') as $val){
			// echo '010$a : '.iso2709_decode($val)."\n";

			$notice .= "\t\t<isbn>".iso2709_decode($val)."</isbn>\n";


		}
		foreach($rec->getValues('200$b') as $val){
			// echo '200$b : '.iso2709_decode($val)."\n";
			$notice .= "\t\t<b200b>".iso2709_decode($val)."</b200b>\n";
		}
		foreach($rec->getValues('200$c') as $val){
			// echo '200$c : '.iso2709_decode($val)."\n";

			$notice .= "\t\t<b200c>".iso2709_decode($val)."</b200c>\n";
		}
		foreach($rec->getValues('200$d') as $val){
			// echo '200$d : '.iso2709_decode($val)."\n";

			$notice .= "\t\t<b200d>".iso2709_decode($val)."</b200d>\n";
		}
		foreach($rec->getValues('200$e') as $val){
			// echo '200$e : '.iso2709_decode($val)."\n";

			$notice .= "\t\t<b200e>".iso2709_decode($val)."</b200e>\n";
		}
		foreach($rec->getValues('200$f') as $val){
			// echo '200$f : '.iso2709_decode($val)."\n";


			$notice .= "\t\t<b200f>".iso2709_decode($val)."</b200f>\n";
		}
		foreach($rec->getValues('200$g') as $val){
			// echo '200$g : '.iso2709_decode($val)."\n";

			$notice .= "\t\t<b200g>".iso2709_decode($val)."</b200g>\n";
		}

		foreach($rec->getValues('205$a') as $val){
			// echo '200$g : '.iso2709_decode($val)."\n";

			$notice .= "\t\t<b205a>".iso2709_decode($val)."</b205a>\n";
		}

		$index = 0;

		foreach($rec->getValues('210$d') as $val){
			// echo '200$g : '.iso2709_decode($val)."\n";

			$notice .= "\t\t<champdate>".iso2709_decode($val)."</champdate>\n";


			// ICI j'essaye d'extraire uniquement une année 
			// dans un contenu texte avec d'autres éléments
			$information_date = iso2709_decode($val);

			// echo $information_date."\n";

			$pattern = '/[\d]{4}/';
			
			preg_match_all("/[\d]{4}/", $information_date, $matches, PREG_SET_ORDER);

			foreach ($matches as $val) {
			    // echo "matched: " . $val[0] . "\n";
			    // echo "part 1: " . $val[1] . "\n";

			    $notice .= "\t\t<annee>". $val[0] ."</annee>\n";

			    $index = $val[0];
			}
		}

		foreach($rec->getValues('330$a') as $val){
			// echo '200$g : '.iso2709_decode($val)."\n";

			$notice .= "\t\t<b330a>".iso2709_decode($val)."</b330a>\n";
		}

		$notice .= "\t</notice>\n";


		// pour afficher une séparation dans la console entre chaque notice
		// echo "----\n";	

		$data = new stdClass();
		$data->index = $index;
		$data->notice = $notice;

		$tableau[] = $data;//$index;

	}

	return $tableau;
}

