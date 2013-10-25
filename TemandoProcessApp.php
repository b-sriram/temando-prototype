
<?php

/**
 * A class to handle request and response of Temando API
 *
 * PHP version 5
 *
 * @category   Shipment Services
 * @author     Reddy <sriram.reddy@bigcommerce.com>
 * @author     Sriram <sriram.bandi@bigcommerce.com>
 * @version    Prototype
 * @link       http://temandoprototype.herokuapp.com
 * 
 */
 
    ini_set('display_errors', '1');
    ini_set("soap.wsdl_cache_enabled", "1");
 
    class TemandoProcessApp
    {
        public $quotesList = array();
        /**
        * Make the quotes List from individual quote
        */
        public function setQuotesList( $aQuote )
        {
            // set required properties among all properties of a quote
            $individualQuote = array();
            $individualQuote['deliveryMethod'] = $aQuote -> deliveryMethod;
            $individualQuote['$etaFrom'] = $aQuote -> etaFrom;
            $individualQuote['$etaTo'] = $aQuote -> etaTo;
            $individualQuote['$totalPrice'] = $aQuote -> totalPrice;
            $carrierObj = $aQuote -> carrier;
            $individualQuote['companyName'] = $carrierObj -> companyName;
            
            // Append individual quote to quotes List 
            array_push( $this -> quotesList,  $individualQuote );
        }
        /**
        *  This function get quote details from API and Return the response with status and quotes List
        */
        public function getQuoteDetails ( $regionFields = array(), $dimensionFieldValue = array() )
        {
            // Initialize default Values
            $response = array( 'flag' => False , "quotesList" => array(), 'exceptionMessage' => '' );
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

                //Check if response have quote details
                if ( property_exists($getQuotesByRequestResponse, 'quote') )
                {
                    $quotes = $getQuotesByRequestResponse -> quote;
                    
                    // Set single quote into quotes List
                    if ( count($quotes) == 1 )
                    {
                        $this -> setQuotesList($quotes);
                    }
                    else 
                    {   // Set multiple quotes into quotes list
                        foreach( $quotes as $quoteKey => $quoteDetails )
                        {
                            $this -> setQuotesList($quoteDetails);
                        }
                    }
                    $response['flag'] = TRUE;
                    $response['quotesList'] = $this -> quotesList;
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
