<?php

	ini_set('display_errors', '1');
	ini_set("soap.wsdl_cache_enabled", "1");
	/*
     * This class describes requetsing quotes using soapclient
     */
	class TemandoProcessApp
	{
	    // initializing class property
	    public $quoteList = array();
        /*
        * return quote deails as a response
        */
		public function getQuoteDetails ($regionFields = array(), $dimensionFieldValue = array())
		{         
		    // initializing local variables variables
		    $response = array('flag' => False , "quotelist" => array(), 'exceptionMessage' => '');
		    $temandoWsdlUrl = "https://training-api.temando.com/schema/2009_06/server.wsdl";
            $requestHeaderUrl = "wsse:http://schemas.xmlsoap.org/ws/2002/04/secext";
            
            //Create a new SoapHeader containing all your login details.
            $username = "temandotest2";
            $password = "password";
			$headerSecurityStr = "<Security><UsernameToken><Username>".$username."</Username>
			                     <Password>".$password."</Password></UsernameToken></Security>";
			$headerSecurityVar = new SoapVar($headerSecurityStr, XSD_ANYXML);
			$soapHeader = new SoapHeader($requestHeaderUrl,'soapenv:Header', $headerSecurityVar);
	        // Create a new SoapClient referencing the Temando WSDL file.
			try{
			    
			    $client = new SoapClient($temandoWsdlUrl,array('soap_version' => SOAP_1_2));
                // Add the SoapHeader to your SoapClient.
                $client->__setSoapHeaders( array($soapHeader) );
			    //getting response using get quotes request
                $quotesByRequest = array(); 
                $quotesByRequest["anywhere"] =  $regionFields;
                $quotesByRequest["anythings"] = array($dimensionFieldValue);
				$getQuotesByRequestResponse = $client->getQuotesByRequest($quotesByRequest);
				//checking whther repsonse is existed.
				if (property_exists($getQuotesByRequestResponse,'quote'))
				{
					//get quote values using quote property
					$quotes = $getQuotesByRequestResponse->quote;
                    //Adding carrier details in to quoteList if one result existed in response
					if (count($quotes) == 1)
					{
						$this->requestArraySetup($quotes);
					}
					else
					{//Adding carrier details in to quoteList if more than one result in response 
						foreach( $quotes as $quoteKey => $quoteDetails )
						{
							//setup required array using quote details
							$this->requestArraySetup($quoteDetails);
						}
					}
					$response['flag'] = TRUE;
					$response['quotelist'] = $this->quoteList;
				}
			}catch(SoapFault $e){ //soap client exception handling
				$response['exceptionMessage'] = $e->getMessage();
			}
  			catch(Exception $exception){ //exception handling
				$response['exceptionMessage'] = $e->getMessage();
			} 
			return $response;
		}
    
        /*
        * Parsing input array values into quotelist array
        */
        public function requestArraySetup($inputArray)
        {
            //
            $responseDetails = array();
            $responseDetails['deliveryMethod'] = $inputArray->deliveryMethod;
            $responseDetails['$etaFrom'] = $inputArray->etaFrom;
            $responseDetails['$etaTo'] = $inputArray->etaTo;
            $responseDetails['$totalPrice'] = $inputArray->totalPrice;
            $carrierObj = $inputArray->carrier;
            $responseDetails['companyName'] = $carrierObj->companyName;
            array_push($this->quoteList,  $responseDetails);
        }

    }
?>
