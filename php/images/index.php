<?php

date_default_timezone_set('Europe/Paris');


set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$liste = array("88-8215-631-1", "2-85088-086-8", "978-88-366-2652-6", "84-393-7103-9", "2-7013-0440-7");


include_once("php-isbn-master/isbn.php");

// AMAZON
// http://www.amazon.fr/gp/aw/d/8882156311/ref=mw_dp_img_z?is=l&qid=1459438191&sr=8-1
//8882156311
//2850880868
// http://www.amazon.fr/gp/aw/d/2850880868/ref=mw_dp_img_1?in=1&is=l

// GOOGLE
// https://www.google.fr/search?q=8882156311&biw=1315&bih=920&source=lnms&tbm=isch&sa=X&ved=0ahUKEwjF05OIl-vLAhWDDBoKHQaYC2MQ_AUICSgE#tbm=isch&q=8882156311

// GOOGLE AJAX
// https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=8882156311&as_filetype=jpg

// https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=978-2-84056-322-8&as_filetype=jpg



foreach($liste as $requete){

	downloadISBN( $requete, 0 );
	
}


function downloadISBN($isbn = false, $delais = 3){

	if(!is_dir('images')){
		mkdir('images');
	}

	if($isbn){
		//$requete = "88-8215-631-1";

		// $urlGoogle = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".$requete."&as_filetype=jpg";

		"https://www.google.fr/search?q=88-8215-631-1&biw=1315&bih=920&source=lnms&tbm=isch&sa=X&ved=0ahUKEwjF05OIl-vLAhWDDBoKHQaYC2MQ_AUICSgE#tbm=isch&q=88-8215-631-1"

		// https://www.google.fr/search?q=Les+champs+de+r%C3%A9sonances&source=lnms&tbm=isch

		//$url = "https://www.google.fr/search?q=".$requete."&biw=1315&bih=920&source=lnms&tbm=isch&sa=X&ved=0ahUKEwjF05OIl-vLAhWDDBoKHQaYC2MQ_AUICSgE#tbm=isch&q=".$requete."";

		$isbn = Isbn::to10($isbn);

		$isbn = str_replace('-','',$isbn);

		echo $isbn."\n";

		$url = "http://www.amazon.fr/gp/aw/d/$isbn/ref=mw_dp_img_1?in=1&is=l";

		$result = file_get_contents($url);

		$pattern = "/src\s*=\s*(\"|')(([^\"';]*))(\"|')/";

		//echo $result;

		preg_match($pattern, $result, $matches, PREG_OFFSET_CAPTURE, 3);

		//print_r($matches[2][0]);

		try {
			$image = file_get_contents($matches[2][0]) ;

			if($image){
				file_put_contents('images/'.$isbn.".jpg", $image);

				echo date('l jS \of F Y h:i:s A')."\n";
				echo "sleep $delais\n";
				sleep( $delais );
			}
		} catch (Exception $e) {
			// echo 'Caught exception: ',  $e->getMessage(), "\n";
			echo "l'image n'existe pas pour l'ISBN $isbn\n";
		}
	}

}


function downloadGoogle($requete = false, $delais = 3){

	if(!is_dir('images')){
		mkdir('images');
	}

	if($requete){

		// https://www.google.fr/search?q=Les+champs+de+r%C3%A9sonances&source=lnms&tbm=isch

	

		$requete = str_replace(' ','+',$requete);

		echo $isbn."\n";

		$url = "https://www.google.fr/search?q=$requete&source=lnms&tbm=isch";

		$result = file_get_contents($url);

		$pattern = "/src\s*=\s*(\"|')(([^\"';]*))(\"|')/";

		//echo $result;

		preg_match($pattern, $result, $matches, PREG_OFFSET_CAPTURE, 3);

		//print_r($matches[2][0]);

		try {
			$image = file_get_contents($matches[2][0]) ;

			if($image){
				file_put_contents('images/'.$isbn.".jpg", $image);

				echo date('l jS \of F Y h:i:s A')."\n";
				echo "sleep $delais\n";
				sleep( $delais );
			}
		} catch (Exception $e) {
			// echo 'Caught exception: ',  $e->getMessage(), "\n";
			echo "l'image n'existe pas pour l'ISBN $isbn\n";
		}
	}

}