<?php
/**
 * API-information: https://api-acc.bva-auctions.com/api/docs/
 */

namespace Auctio;

use \Auctio\Entity\Lot;

class Api
{

    private $clientHeaders;
    private $errorData;
    private $errorMessages;
    private $hostname;

    /**
     * Constructor
     *
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @param string $userAgent
     */
    public function __construct($hostname, $username = null, $password = null, $userAgent = null)
    {
        // Set hostname
        $this->hostname = $hostname;

        // Set default header for client-requests
        $this->clientHeaders = array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => $userAgent,
        );

        // Set error-messages
        $this->errorMessages = array();
        $this->errorData = array();

        if (!empty($username)) {
            $this->login($username, $password);
        }
    }

    /**
     * Set error-data
     *
     * @param array $data
     */
    public function setErrorData($data)
    {
        $this->errorData = $data;
    }

    /**
     * Get error-data
     *
     * @return array
     */
    public function getErrorData()
    {
        return $this->errorData;
    }

    /**
     * Set error-messages
     *
     * @param array $errorMessages
     */
    public function setErrorMessages($errorMessages)
    {
        if (!is_array($errorMessages)) $errorMessages = array($errorMessages);
        $this->errorMessages = $errorMessages;
    }

    /**
     * Get error-messages
     *
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * Get access/refresh tokens by login
     *
     * @param string $username
     * @param string $password
     * @return boolean
     */
    public function login($username, $password)
    {
        // Set data
        $data = array(
            'username'=>$username,
            'password'=>$password
        );

        // Execute request
        $result = $this->execute('POST', 'tokenlogin', $data);

        // Check result
        if ($result === false) {
            // Return
            return false;
        } else {
            // Decode response (JSON) to object
            $result = json_decode($result);

            // Set tokens in headers
            $this->clientHeaders['accessToken'] = $result->accessToken;
            $this->clientHeaders['refreshToken'] = $result->refreshToken;
            $this->clientHeaders['X-CSRF-Token'] = $result->csrfToken;

            // Return
            return true;
        }
    }

    /**
     * Logout (token)
     *
     * @return array|bool
     */
    public function logout()
    {
        // Execute request
        $result = $this->execute('POST', 'logout');

        // Return
        return $result;
    }

    /**
     * Create lot in auction
     *
     * @param \Auctio\Entity\Lot $lot
     * @return bool|mixed
     */
    public function createLot(\Auctio\Entity\Lot $lot)
    {
        // Execute request
        $result = $this->execute('PUT', 'ext123/lot', $lot);

        // Return
        return ($result === false) ? $result : json_decode($result);
    }

    /**
     * Get auction-details by ID
     *
     * @param integer $auctionId
     * @return boolean|object
     */
    public function getAuction($auctionId)
    {
        // Execute request
        $result = $this->execute('GET', 'ext123/auction/' . $auctionId);

        // Return
        return ($result === false) ? $result : json_decode($result);
    }

    /**
     * Get main/subcategories of auction by ID
     *
     * @param integer $auctionId
     * @return bool|object
     */
    public function getAuctionCategories($auctionId)
    {
        // Execute request
        $result = $this->execute('GET', 'ext123/auction/' . $auctionId . "/nl/lotcategories/true/true");

        // Return
        return ($result === false) ? $result : json_decode($result);
    }

    /**
     * Get locations of auction by ID
     *
     * @param integer $auctionId
     * @return bool|object
     */
    public function getAuctionLocations($auctionId)
    {
        // Execute request
        $result = $this->execute('GET', 'ext123/auction/' . $auctionId . "/locations");

        // Return
        return ($result === false) ? $result : json_decode($result);
    }

    /**
     * Get lot-details by ID
     *
     * @param integer $lotId
     * @return bool|object
     */
    public function getLot($lotId)
    {
        // Execute request
        $result = $this->execute('GET', 'ext123/lot/' . $lotId);

        // Return
        return ($result === false) ? $result : json_decode($result);
    }

    /**
     * Get lot-details by number
     *
     * @param integer $auctionId
     * @param integer $lotNumber
     * @param string $lotNumberAddition
     * @return bool|object
     */
    public function getLotByNumber($auctionId, $lotNumber, $lotNumberAddition = null)
    {
        // Execute request
        $result = $this->execute('GET', 'ext123/lot/' . $auctionId . '/' . $lotNumber . $lotNumberAddition . '/lotbynumber');

        // Return
        return ($result === false) ? $result : json_decode($result);
    }

    /**
     * Execute request
     *
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return boolean|string
     */
    private function execute($method, $uri, $data = null, $headers = null)
    {
        // Initialize CURL
        $ch = curl_init($this->hostname . $uri);

        // Set (request) headers
        $requestHeaders = array();
        if (!empty($headers)) $headers = array_merge($this->clientHeaders, $headers);
        else $headers = $this->clientHeaders;
        foreach ($headers AS $headerName => $headerValue) $requestHeaders[] = $headerName . ":" . $headerValue;

        // Set options based on the request method
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if ($headers['Content-Type'] != 'multipart/form-data') {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($headers['Content-Type'] != 'multipart/form-data') {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'GET':
                if (!empty($data)) {
                    $data = http_build_query($data, '', '&');
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
                break;
        }

        // Set options
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Get the response
        $response = curl_exec($ch);
        $requestInfo = curl_getinfo($ch);
        curl_close($ch);

        // Check status-code request
        if (in_array($requestInfo['http_code'], array(200, 201))) {
            // Return
            return $response;
        } else {
            // Decode response (JSON) to object
            $response = json_decode($response);

            // Set error-message and error-data
            $this->setErrorMessages($response->message);
            $this->setErrorData($response->extraData);

            // Return
            return false;
        }
    }

}