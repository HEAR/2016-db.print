<?php

date_default_timezone_set('Europe/Paris');

$liste = array("88-8215-631-1", "2-85088-086-8", "978-88-366-2652-6");


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

	//$requete = "88-8215-631-1";

	// $urlGoogle = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".$requete."&as_filetype=jpg";


	//$url = "https://www.google.fr/search?q=".$requete."&biw=1315&bih=920&source=lnms&tbm=isch&sa=X&ved=0ahUKEwjF05OIl-vLAhWDDBoKHQaYC2MQ_AUICSgE#tbm=isch&q=".$requete."";

	$requete = Isbn::to10($requete);

	$requete = str_replace('-','',$requete);

	echo $requete."\n";

	$url = "http://www.amazon.fr/gp/aw/d/$requete/ref=mw_dp_img_1?in=1&is=l";

	$result = file_get_contents($url);

	$pattern = "/src\s*=\s*(\"|')(([^\"';]*))(\"|')/";

	//echo $result;

	preg_match($pattern, $result, $matches, PREG_OFFSET_CAPTURE, 3);

	//print_r($matches[2][0]);

	$image = file_get_contents($matches[2][0]);

	file_put_contents('images/'.$requete.".jpg", $image);

	echo date('l jS \of F Y h:i:s A')."\n";
	echo "sleep 3\n";
	sleep(3);
}