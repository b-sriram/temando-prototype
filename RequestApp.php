<?php 

/**
 * A class to validate and set form values into respective properties
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
    
    class RequestApp
    {
        // Initialize Field names and form errors
        public $regionFields = array( "originIs" => "", "originCode" => "", "originSuburb" => "", "destinationIs" => "" ,
                                    "destinationCode" => "", "destinationSuburb" => "" );
        public $dimensionFields = array ( "packaging" => "", "length" => "", "width" => "", "height" => "", "weight" => "" );
        private $numericFieldsArray = array( "originCode", "destinationCode", "length", "width", "height", "weight" );
        
        // Initialize default values
        public $formErrors = array();
        public $isValid = TRUE;
        
        // Define form errors through class construct
        public function __construct()
        {
            $this->formErrors = array_merge($this->regionFields, $this->dimensionFields);
        }
       
        /**
         * Validate fieldvalue against field property
        */
        public function fieldsValidation($fieldName, $fieldValue)
        {
            // Check fieldValue is empty or not.
            $errorMessage = "";
            if( empty($fieldValue) )
            {
                $errorMessage = $fieldName." Need values";
                $this -> isValid = FALSE;
            } // Check if the filed belongs to numeric field set.
            elseif ( in_array($fieldName, $this -> numericFieldsArray) )
            {
                // Check fieldValue is numeric or not.
                if( !is_numeric($fieldValue) )
                {
                    $errorMessage = $fieldName. "Need  Numbers only";
                    $this -> isValid = FALSE;
                }
            }
            return $errorMessage;
        }
        /**
         * Set form fileds into respective properties 
         */
        public function ExtractAndValidate($inputValues)
        {
            // Requesting validation against on regionFields name and value
            foreach($this -> regionFields as $key => $value){
                $fieldValue = trim($inputValues[$key]);
                $this -> regionFields[$key] = $fieldValue;
                $this -> formErrors[$key] = $this -> fieldsValidation( $key, $fieldValue ); 
            }          
            // Requesting validation against on dimensionFields name and value
            foreach($this -> dimensionFields as $key => $value){
                $fieldValue = trim($inputValues[$key]);
                $this -> dimensionFields[$key] = $fieldValue;
                $this -> formErrors[$key] = $this -> fieldsValidation( $key, $fieldValue );
            }
        }
    }
?>
