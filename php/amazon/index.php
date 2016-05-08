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


// se créer un compte gratuit
// https://aws.amazon.com/fr/api-gateway/pricing/
// https://affiliate-program.amazon.com/gp/advertising/api/detail/main.html

namespace Acme\Demo;

require __DIR__ . '/vendor/autoload.php';

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\ApaiIO;

include_once('param.php');


$conf = new GenericConfiguration();
$conf
    ->setCountry('fr')
    ->setAccessKey(AMAZON_API_KEY)
    ->setSecretKey(AMAZON_API_SECRET_KEY)
    ->setAssociateTag(AMAZON_ASSOCIATE_TAG)

    ->setRequest('\ApaiIO\Request\Soap\Request')
    ->setResponseTransformer('\ApaiIO\ResponseTransformer\ObjectToArray');
    // ->setResponseTransformer('\ApaiIO\ResponseTransformer\ObjectToArray');

$apaiIO = new ApaiIO($conf);

// http://exeu.github.io/apai-io/
// http://exeu.github.io/apai-io/basic-usage.html

$search = new Search();
$search->setCategory('Books'); // DVD
// $search->setActor('Bruce Willis');
// http://docs.aws.amazon.com/AWSECommerceService/latest/DG/ItemLookup.html
$search->setKeywords('Le seigneur des anneaux');
$search->setResponsegroup(array('Images', 'EditorialReview'));

$formattedResponse = $apaiIO->runOperation($search);

file_put_contents('amazon.json',json_encode($formattedResponse));
file_put_contents('image.jpg', file_get_contents( $formattedResponse['Items']['Item'][0]["LargeImage"]['URL'] ));


var_dump($formattedResponse['Items']['Item'][0]["EditorialReviews"]["EditorialReview"]["Content"]);

// echo $formattedResponse['Items']['Item'][0]["LargeImage"]['URL']

// use ApaiIO\Operations\BrowseNodeLookup;

// $browseNodeLookup = new BrowseNodeLookup();
// $browseNodeLookup->setNodeId(163357);

// $response = $apaiIO->runOperation($browseNodeLookup);

// var_dump($response);
