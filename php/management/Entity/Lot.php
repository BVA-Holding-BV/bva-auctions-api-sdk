<?php
namespace Auctio\Entity;

class Lot {

    /** @var int */
    public $id;
    /** @var int */
    public $auctionId;
    /** @var int */
    public $categoryId;
    /** @var string */
    public $categoryCode;
    /** @var string */
    public $dossier;
    /** @var string */
    public $externalDossierNumber;
    /** @var float */
    public $auctionFeePercentage;
    /** @var string */
    public $bidMethodType;
    /** @var int */
    public $number;
    /** @var string */
    public $numberAddition;
    /**
     * @var string
     * @ReadOnly
     */
    public $fullNumber;
    /** @var string */
    public $startDate;
    /** @var string */
    public $endDate;
    /**
     * @var int
     * @ReadOnly
     */
    public $endDateDays;
    /**
     * @var int
     * @ReadOnly
     */
    public $endDateHours;
    /**
     * @var int
     * @ReadOnly
     */
    public $endDateMinutes;
    /**
     * @var int
     * @ReadOnly
     */
    public $endDateSeconds;
    /** @var boolean */
    public $open;
    /** @var array */
    public $name;
    /** @var array */
    public $description;
    /** @var string */
    public $thumbnailUrl;
    /** @var string */
    public $imageUrl;
    /** @var string */
    public $lotPageUrl;
    /** @var int */
    public $startAmount;
    /** @var int */
    public $minimumAmount;
    /** @var float */
    public $latestBidAmount;
    /** @var string */
    public $lastBidTime;
    /** @var int */
    public $bidCount;
    /** @var int */
    public $lotTypeId;
    /** @var int */
    public $locationId;
    /** @var string */
    public $externalId;
    /** @var string */
    public $externalURL;
    /** @var string */
    public $externalSMS;
    /** @var string */
    public $externalEmailBroker;
    /** @var string */
    public $externalEmailOwner;
    /** @var boolean */
    public $approved;
    /** @var boolean */
    public $assignedExplicitly;
    /** @var float */
    public $vehicleTaxAmount;
    /** @var array */
    public $extraDownloadName;
    /** @var string */
    public $extraDownloadURL;
    /** @var array */
    public $extraDownload2Name;
    /** @var string */
    public $extraDownload2URL;
    /** @var int */
    public $combinationLotId;
    /**
     * @var array
     * @ReadOnly
     */
    public $lotIds;
    /**
     * @var string
     * @ReadOnly
     */
    public $reservationState;
    /** @var string */
    public $currencyCode;
    /** @var float */
    public $additionalCosts;
    /** @var boolean */
    public $buyNowEnabled;
    /** @var string */
    public $buyNowPrice;

}