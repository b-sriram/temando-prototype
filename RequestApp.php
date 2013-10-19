<?php

	class RequestApp
	{
		//Intilizing Field names and form errors in array format
		public $regionFields = array("originIs"=>"","originCode"=>"","originSuburb"=>"",
							"destinationIs"=>"","destinationCode"=>"","destinationSuburb"=>"");
		public $dimensionFields = array("packaging" => "","length" => "","width" => "","height" => "","weight" => "");
		// Intilizing default values
		public $formErrors = array();
							
		public $isValid=TRUE;
		
		/*
		 * constructing class with form errors by given field  names.
		 * 
		 * 	*/
		public function __construct()
		{
		    $this->formErrors = array_merge($this->regionFields, $this->dimensionFields);
		}
		// validateing given form fields
		public function validate()
		{
			//validating field value whether empty or not.
			foreach($this->regionFields as $key => $value)
			{
				if(empty($value))
				{
					$this->formErrors[$key]=$key." is required";
					$this->isValid=FALSE;
				} // validating numeric field value.
				elseif ($key == 'originCode' || $key == 'destinationCode')
				{
					if($this->isValid && !is_numeric($value))
					{
						$this->formErrors[$key]="Numbers only";
						$this->isValid=FALSE;
					}
				}
			}
			if($this->isValid)
			{
				//validating field value whether empty or not.
				foreach($this->dimensionFields as $key => $value)
				{
					if(empty($value))
					{
						$this->formErrors[$key]=$key." is required";
						$this->isValid=FALSE;
					}// validating numeric field value.
					elseif($key == 'length' || $key == 'width' || $key == 'height' || $key == 'weight' )
					{
						if($this->isValid && !is_numeric($value))
						{
							$this->formErrors[$key]="Numbers only";
							$this->isValid=FALSE;
						}
					}	
				}
			}
		}
		/*
		* constructing arrays using form field values
		*/
		public function setFieldValues($inputValues)
		{
			//Intilizing array with regionFields values using trim operation.
			$this->regionFields=array(															
								"originIs"=>trim($inputValues["originIs"]),
								"originCode"=>trim($inputValues["originCode"]),
								"originSuburb"=>trim($inputValues["originSuburb"]),
								"destinationIs"=>trim($inputValues["destinationIs"]),
								"destinationCode"=>trim($inputValues["destinationCode"]),
								"destinationSuburb"=>trim($inputValues["destinationSuburb"])
							);
			//Intilizing array with dimensionFields values using trim operation.				
			$this->dimensionFields=array(
								"packaging"=>trim($inputValues["packaging"]),
								"length"=>trim($inputValues["length"]),
								"width"=>trim($inputValues["width"]),
								"height"=>trim($inputValues["height"]),
								"weight"=>trim($inputValues["weight"])
							);
		}
		
	}
?>

	
