<?php

namespace Auctio;

require_once("API.php");
require_once("Entity/Lot.php");

// Initialize API
$hostname = "https://api-acc.bva-auctions.com/api/rest/";
$username = "";
$password = "";
$api = new \Auctio\API($hostname, $username, $password);

// Set variables (in consultation with BVA)
$auctionId = 32053;
$auctionFeePercentage = 16;
$lotTypeId = 532;
$lotNumber = 610;

// Get auction-data by ID
$auction = $api->getAuction($auctionId);

// Get auction-categories
$auctionCategories = $api->getAuctionCategories($auction->id);

// Get auction-locations
$auctionLocations = $api->getAuctionLocations($auction->id);

// Set lot-object
$lot = new \Auctio\Entity\Lot();
$lot->auctionId = $auction->id;
$lot->bidMethodType = 'BidMethod';
$lot->number = $lotNumber;
$lot->startDate = $auction->startDate;
$lot->endDate = $auction->endDate;
$lot->name = array("nl"=>"Test object");
$lot->description = array("nl"=>"This is a test object");
$lot->startAmount = 10;
$lot->lotTypeId = $lotTypeId;
$lot->auctionFeePercentage = $auctionFeePercentage;
$lot->assignedExplicitly = false;
// Set (sub)category of lot, in this example we select a random subcategory. Please select correct category for your lot.
$lot->categoryId = $auctionCategories[0]->subCategories[0]->id;
// Set location of lot, in this example we select a random location. If your location is not available, create location before creating lot.
$lot->locationId = $auctionLocations[0]->id;

// Create lot
$result = $api->createLot($lot);
if ($result === false) {
    var_dump($api->getErrorMessages());
    var_dump($api->getErrorData());
    exit;
}

// Get lot by number
$lot = $api->getLotByNumber($auction->id, $lot->number);
var_dump($lot);