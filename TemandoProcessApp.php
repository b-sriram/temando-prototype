<?php
    ini_set('display_errors', '1');
    ini_set("soap.wsdl_cache_enabled", "1");
    /*
     *This class process request quotes using soapclient
    */
    class TemandoProcessApp
    {
        // Initialize class property
        public $resultQuotes = array();
        /*
        * Parsing Response quotes into resultQuotes
        */
        public function setResultQuotes( $reponseQuotes )
        {
            $eachQuoteDetails = array();
            $eachQuoteDetails['deliveryMethod'] = $reponseQuotes -> deliveryMethod;
            $eachQuoteDetails['$etaFrom'] = $reponseQuotes -> etaFrom;
            $eachQuoteDetails['$etaTo'] = $reponseQuotes -> etaTo;
            $eachQuoteDetails['$totalPrice'] = $reponseQuotes -> totalPrice;
            $carrierObj = $reponseQuotes->carrier;
            $eachQuoteDetails['companyName'] = $carrierObj -> companyName;
            array_push( $this -> resultQuotes,  $eachQuoteDetails );
        }
        /*
        * Return response with status and result quotes
        */
        public function getQuoteDetails ( $regionFields = array(), $dimensionFieldValue = array() )
        {
            // Initialize default Values
            $response = array( 'flag' => False , "quotelist" => array(), 'exceptionMessage' => '' );
            $temandoWsdlUrl = "https://training-api.temando.com/schema/2009_06/server.wsdl";
            $requestHeaderUrl = "wsse:http://schemas.xmlsoap.org/ws/2002/04/secext";
            // Create a new SoapHeader containing all your login details.
            $username = "temandotest2";
            $password = "password";
            $headerSecurityStr = "<Security><UsernameToken><Username>".$username."</Username><Password>".$password."</Password></UsernameToken></Security>";
            $headerSecurityVar = new SoapVar($headerSecurityStr, XSD_ANYXML);
            $soapHeader = new SoapHeader($requestHeaderUrl,'soapenv:Header', $headerSecurityVar);
            // Create a new SoapClient referencing the Temando WSDL file.

            try{
                $client = new SoapClient($temandoWsdlUrl,array( 'soap_version' => SOAP_1_2) );
                // Add the SoapHeader to your SoapClient.
                $client -> __setSoapHeaders( array($soapHeader) );
                // Get response using get quotes request  using SoapClient
                $quotesByRequest = array();
                $quotesByRequest["anywhere"] =  $regionFields;
                $quotesByRequest["anythings"] = array( $dimensionFieldValue );
                $getQuotesByRequestResponse = $client -> getQuotesByRequest($quotesByRequest);

                //Check response have quote details
                if ( property_exists($getQuotesByRequestResponse,'quote') )
                {
                    // Get response quotes
                    $reponseQuotes = $getQuotesByRequestResponse -> quote;
                    // Set response quotes into result quotes
                    if ( count($reponseQuotes) == 1 )
                    {
                        $this -> setResultQuotes($reponseQuotes);
                    }
                    else
                    {
                        foreach( $reponseQuotes as $quoteKey => $quoteDetails )
                        {
                            $this -> setResultQuotes($quoteDetails);
                        }
                    }
                    $response['flag'] = TRUE;
                    $response['quotelist'] = $this -> resultQuotes;
                }
            }
            catch(SoapFault $e){ // Soap client exception handling
                $response['exceptionMessage'] = $e -> getMessage();
            }
            catch(Exception $e){ // Exception handling
                $response['exceptionMessage'] = $e -> getMessage();
            }
            return $response;
        }

    }
?>
