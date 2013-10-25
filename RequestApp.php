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
         * Set form fileds into respective properties 
         */
        public function setFieldValues($inputValues)
        {
            $this -> regionFields["originIs"] = trim( $inputValues["originIs"] );
            $this -> regionFields["originCode"] = trim( $inputValues["originCode"] );
            $this -> regionFields["originSuburb"] = trim( $inputValues["originSuburb"] );
            $this -> regionFields["destinationIs"] = trim( $inputValues["destinationIs"] );
            $this -> regionFields["destinationCode"] = trim( $inputValues["destinationCode"] );
            $this -> regionFields["destinationSuburb"] = trim( $inputValues["destinationSuburb"] );
            
            $this -> dimensionFields ["packaging"] = trim( $inputValues["packaging"] );
            $this -> dimensionFields ["length"] = trim( $inputValues["length"] );
            $this -> dimensionFields ["width"] = trim( $inputValues["width"] );
            $this -> dimensionFields ["height"] = trim( $inputValues["height"] );
            $this -> dimensionFields ["weight"] = trim( $inputValues["weight"] );
            
        }
        /**
         * Validate fieldvalue against field property
        */
        public function fieldsValidation($fieldname, $fieldValue)
        {
            // Check fieldValue is empty or not.
            $errorMessage = "";
            if( empty($fieldValue) )
            {
                $errorMessage = $fieldname." is required";
                $this -> isValid = FALSE;
            } // Check if the filed belongs to numeric field set.
            elseif ( in_array($fieldname, $this -> numericFieldsArray) )
            {
                // Check fieldValue is numeric or not.
                if( !is_numeric($fieldValue) )
                {
                    $errorMessage = $fieldname. " Numbers only";
                    $this -> isValid = FALSE;
                }
            }
            return $errorMessage;
        }
        /**
         * Validate form fields 
        */
        public function validate()
        {
            // Requesting validation against on regionFields name and value
            foreach($this -> regionFields as $key => $value){
                $this -> formErrors[$key] = $this -> fieldsValidation( $key, $value ); 
            }
            
            // Requesting validation against on dimensionFields name and value
            foreach($this -> dimensionFields as $key => $value){
                $this -> formErrors[$key] = $this -> fieldsValidation( $key, $value );
            }
        }
    }
?>
