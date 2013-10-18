<?php

	ini_set('display_errors', '1');
	ini_set("soap.wsdl_cache_enabled", "1");
	
	class TemandoProcessApp
	{
		// class variables
		public $QuotesByRequest = array();
		public $quoteList = array();
		public $serverUrl = "";
		public $requestHeaderUrl = "";
		
		public function __construct ()  
    	{  
        	//intilizing variable
			$this->serverUrl = "https://training-api.temando.com/schema/2009_06/server.wsdl";
			$this->requestHeaderUrl = "wsse:http://schemas.xmlsoap.org/ws/2002/04/secext";

		}
		
		public function getQuoteDetails ($dimensionFieldValue = array(),  $regionFields = array())
       	{
       		$dimensionFieldValues = array();
			array_push($dimensionFieldValues, $dimensionFieldValue);
       		// Create a new SoapClient referencing the Temando WSDL file.
			$client = new SoapClient($this->serverUrl,array('soap_version' => SOAP_1_2));
			/*
			 * Create a new SoapHeader containing all your login details.
			 */
			$username = "temandotest";
			$password = "password";
			$headerSecurityStr = "<Security><UsernameToken><Username>".$username."</Username>
								<Password>".$password."</Password></UsernameToken></Security>";
			$headerSecurityVar = new SoapVar($headerSecurityStr, XSD_ANYXML);
			$soapHeader = new SoapHeader($this->requestHeaderUrl,'soapenv:Header', $headerSecurityVar);
			// Add the SoapHeader to your SoapClient.
			$client->__setSoapHeaders( array($soapHeader) );
			//preparing quotes array
			$this->QuotesByRequest["anythings"] = $dimensionFieldValues;
			$this->QuotesByRequest["anywhere"] =  $regionFields;
			/*
			 * Call the method using the request details.
			 */
			try{
                //getting response using get quotes request

				$getQuotesByRequestResponse = $client->getQuotesByRequest($this->QuotesByRequest);
				if (property_exists($getQuotesByRequestResponse,'quote'))
				{
					$quotes = $getQuotesByRequestResponse->quote;
					//var_dump($quotes);
					if (count($quotes) == 1)
					{
						$responseDetails = array();
						$responseDetails['deliveryMethod'] = $quotes->deliveryMethod;
						$responseDetails['$etaFrom'] = $quotes->etaFrom;
						$responseDetails['$etaTo'] = $quotes->etaTo;
						$responseDetails['$totalPrice'] = $quotes->totalPrice;
						$carrierObj = $quotes->carrier;
						$responseDetails['companyName'] = $carrierObj->companyName;
						array_push($this->quoteList,  $responseDetails);
					}
					else
					{
						foreach( $quotes as $quoteKey => $quoteDetails )
						{
							$responseDetails = array();
					 		$responseDetails['deliveryMethod'] = $quoteDetails->deliveryMethod;
					 		$responseDetails['$etaFrom'] = $quoteDetails->etaFrom;
					 		$responseDetails['$etaTo'] = $quoteDetails->etaTo;
					 		$responseDetails['$totalPrice'] = $quoteDetails->totalPrice;
					 		$carrierObj = $quoteDetails->carrier;
					 		$responseDetails['companyName'] = $carrierObj->companyName;
					 		array_push($this->quoteList,  $responseDetails);
						}
					}
				}
			}catch(SoapFault $exception){
				echo '<label class="exceptioncolor">Please try again later</label>';
				}
  			catch(Exception $exception){
  				echo '<label class="exceptioncolor">Please try again later</label>';
			} 
			return $this->quoteList;
	   	}
	}
?>
