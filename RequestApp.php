<?php
    /*
     * 
     */
	class RequestApp
	{
		//initializing Field names and form errors in array format
		public $regionFields = array("originIs"=>"","originCode"=>"","originSuburb"=>"",
							"destinationIs"=>"","destinationCode"=>"","destinationSuburb"=>"");
		public $dimensionFields = array("packaging" => "","length" => "","width" => "","height" => "","weight" => "");
		//initializing default values
		public $formErrors = array();
		private $numericFieldsArray = array("originCode", "destinationCode", "length", "width", "height", "weight");
		public $isValid = TRUE;
		
		// constructing class with form errors by given field  names.
		public function __construct()
		{
		    $this->formErrors = array_merge($this->regionFields, $this->dimensionFields);
		}
        //
        public function fieldValidation($fieldname, $fieldValue)
        {
            
            if(empty($fieldValue))
            {
                $this->formErrors[$fieldname] = $fieldname." is required";
                $this->isValid=FALSE;
            } // validating numeric field value.
            elseif (in_array($fieldname, $this->numericFieldsArray))
            {
                if(!is_numeric($fieldValue))
                {
                    $this->formErrors[$fieldname] = "Numbers only";
                    $this->isValid = FALSE;
                }
            }
            else 
                $this->formErrors[$fieldname] = "";
        }
		// validate given form fields
		public function validate()
		{
			//validating region fields
			foreach($this->regionFields as $key => $value)
			{
			    //validate form fields
			    $this->fieldValidation($key,$value);
				
			}
			//validating dimension fields
			foreach($this->dimensionFields as $key => $value)
			{
				//validate form fields
                $this->fieldValidation($key,$value);
			}
		}
		/*
		* constructing arrays using form field values
		*/
		public function setFieldValues($inputValues)
		{
			//Intilizing array with regionField values using trim operation.
			$this->regionFields=array(															
						    "originIs"=>trim($inputValues["originIs"]),
						    "originCode"=>trim($inputValues["originCode"]),
						    "originSuburb"=>trim($inputValues["originSuburb"]),
						    "destinationIs"=>trim($inputValues["destinationIs"]),
						    "destinationCode"=>trim($inputValues["destinationCode"]),
						    "destinationSuburb"=>trim($inputValues["destinationSuburb"])
						);
			//Intilizing array with dimension Fields				
			$this->dimensionFields = array(
							"packaging"=>trim($inputValues["packaging"]),
							"length"=>trim($inputValues["length"]),
							"width"=>trim($inputValues["width"]),
							"height"=>trim($inputValues["height"]),
							"weight"=>trim($inputValues["weight"])
						     );
		}
		
	}
?>

	
