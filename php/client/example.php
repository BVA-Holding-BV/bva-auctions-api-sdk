<?php

namespace Auctio;

require_once("API.php");

// Initialize API
$hostname = "https://api-acc.bva-auctions.com/api/rest/";
$username = "";
$password = "";
$api = new \Auctio\API($hostname, $username, $password);

// Set variables
$auctionId = 32053;
$lotNumber = 1;

// Get auction-data by ID
$auction = $api->getAuction($auctionId);

// Get auction-categories
$auctionCategories = $api->getAuctionCategories($auction->id);

// Get auction-locations
$auctionLocations = $api->getAuctionLocations($auction->id);

// Get lot by number
$lot = $api->getLotByNumber($auction->id, $lotNumber);