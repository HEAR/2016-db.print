<?php 


// CE CODE PROVIENT DE LA CLASSE iso2709_record
// cf http://www.phpclasses.org/package/1813-PHP-Handles-ISO-2709-library-data-exchange-files.html

// ---------------------------------------------------
//  iso2709_record : classe PHP pour la manipulation
//  d'enregistrements au format ISO2709
//	(c) François Lemarchand 2002
//	public release 0.0.6
//  Cette bibliothèque est distribuée sous la Licence 2 GNU GPL       
//
//  Cette bibliothèque est distribuée car potentiellement utile mais  
//  SANS AUCUNE GARANTIE, ni explicite, ni implicite, y compris les   
//  garanties de commercialisation ou d'adaptation dans un but        
//  spécifique. Reportez vous à la Licence Publique Générale GNU pour 
//  plus de détails.                                                  
// 
// 
// 
//  Tous les fichiers sont sous ce copyright sans exception.
//  Voir le fichier GPL.txt
// 
// ---------------------------------------------------


function iso2709_decode($chaine) {
	$result = '';

	if(!preg_match("/[\xC1-\xFF]./misU", $chaine)){
		return $chaine;
	} else {
		for($i = 0 ; $i < strlen($chaine) ; $i++) {
			if(ord($chaine[$i]) >= 0xC1) {
				$result .=  isodecode(ord($chaine[$i]), ord($chaine[$i+1]));
				$i++;
			} else {
				$result .= $chaine[$i];
			}
		}
	}
	return $result;
}


function isodecode($char1, $char2) {

	switch($char1) {

		case 0xc1:
			switch($char2) {
				case 0x41: $result = 'À'; break ;
				case 0x45: $result = 'È'; break ;
				case 0x49: $result = 'Ì'; break ;
				case 0x4f: $result = 'Ò'; break ;
				case 0x55: $result = 'Ù'; break ;
				case 0x61: $result = 'à'; break ;
				case 0x65: $result = 'è'; break ;
				case 0x69: $result = 'ì'; break ;
				case 0x6f: $result = 'ò'; break ;
				case 0x75: $result = 'ù'; break ;
				default: $result = '?'; break;
			}
		break;
		case 0xc2:
			switch($char2) {
				case 0x41: $result = 'Á'; break ;
				case 0x45: $result = 'É'; break ;
				case 0x49: $result = 'Í'; break ;
				case 0x4f: $result = 'Ó'; break ;
				case 0x55: $result = 'Ú'; break ;
				case 0x59: $result = 'Ý'; break ;
				case 0x61: $result = 'á'; break ;
				case 0x65: $result = 'é'; break ;
				case 0x69: $result = 'í'; break ;
				case 0x6f: $result = 'ó'; break ;
				case 0x75: $result = 'ú'; break ;
				case 0x79: $result = 'ý'; break ;
				default: $result = '?'; break;
			}
		break;
		case 0xc3:
			switch($char2) {
				case 0x41: $result = 'Â'; break ;
				case 0x45: $result = 'Ê'; break ;
				case 0x49: $result = 'Î'; break ;
				case 0x4f: $result = 'Ô'; break ;
				case 0x55: $result = 'Û'; break ;
				case 0x61: $result = 'â'; break ;
				case 0x65: $result = 'ê'; break ;
				case 0x69: $result = 'î'; break ;
				case 0x6f: $result = 'ô'; break ;
				case 0x75: $result = 'û'; break ;
				default: $result = '?'; break;
			}
		break;
		case 0xc4:
			switch($char2) {
				case 0x41: $result = 'Ã'; break ;
				case 0x4e: $result = 'Ñ'; break ;
				case 0x4f: $result = 'Õ'; break ;
				case 0x61: $result = 'ã'; break ;
				case 0x6e: $result = 'ñ'; break ;
				case 0x6f: $result = 'õ'; break ;
				default: $result = '?'; break;
			}
		break;
		case 0xc8:
			switch($char2) {
				case 0x41: $result = 'Ä'; break ;
				case 0x45: $result = 'Ë'; break ;
				case 0x49: $result = 'Ï'; break ;
				case 0x4f: $result = 'Ö'; break ;
				case 0x61: $result = 'ä'; break ;
				case 0x65: $result = 'ë'; break ;
				case 0x69: $result = 'ï'; break ;
				case 0x6f: $result = 'ö'; break ;
				case 0x79: $result = 'ÿ'; break ;
				case 0x75: $result = 'ü'; break ;
				default: $result = '?'; break;
			}
		break;
		case 0xc9:
			switch($char2) {
				case 0x41: $result = 'Ä'; break ;
				case 0x4f: $result = 'Ö'; break ;
				case 0x55: $result = 'Ü'; break ;
				case 0x61: $result = 'ä'; break ;
				case 0x6f: $result = 'ö'; break ;
				case 0x75: $result = 'ü'; break ;
				default: $result = '?'; break;
			}
		break;
		case 0xca:
			switch($char2) {
				case 0x41: $result = 'Å'; break ;
				case 0x61: $result = 'å'; break ;
				default: $result = '?'; break;
			}
		break;
		case 0xd0:
			switch($char2) {
				case 0x43: $result = 'Ç'; break ;
				case 0x63: $result = 'ç'; break ;
				default: $result = '?'; break;
			}
		break;

		// char sur un caractère

		case 0xe1: $result = 'Æ'; break ;
		case 0xe2: $result = 'Ð'; break ;
		case 0xe9: $result = 'Ø'; break ;
		case 0xec: $result = 'þ'; break ;
		case 0xf1: $result = 'æ'; break ;
		case 0xf3: $result = 'ð'; break ;
		case 0xf9: $result = 'ø'; break ;
		case 0xfb: $result = 'ß'; break ;

		default: $result = chr($char1).chr($char2); break;

	}

	return $result;
}