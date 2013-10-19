<?php

	class RequestApp
	{
		public $regionFields = array("originIs"=>"","originCode"=>"","originSuburb"=>"","destinationIs"=>"","destinationCode"=>"","destinationSuburb"=>"");
							
		public $dimensionFields = array("packaging" => "","length" => "","width" => "","height" => "","weight" => "");
							
		public $formErrors = array();
							
		public $isValid=TRUE;
		
		/*
		 * 
		 * 
		 * 	*/
		public function __construct()
		{
		    $this->formErrors = array_merge($this->regionFields, $this->dimensionFields);
		}
		// validateing given form fields
		public function validate()
		{
			foreach($this->regionFields as $key => $value)
			{
				if(empty($value))
				{
					$this->formErrors[$key]=$key." is required";
					$this->isValid=FALSE;
				}
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
				foreach($this->dimensionFields as $key => $value)
				{
					if(empty($value))
					{
						$this->formErrors[$key]=$key." is required";
						$this->isValid=FALSE;
					}
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

		public function setFieldValues($inputValues)
		{
			
			$this->regionFields=array(															
								"originIs"=>trim($inputValues["originIs"]),
								"originCode"=>trim($inputValues["originCode"]),
								"originSuburb"=>trim($inputValues["originSuburb"]),
								"destinationIs"=>trim($inputValues["destinationIs"]),
								"destinationCode"=>trim($inputValues["destinationCode"]),
								"destinationSuburb"=>trim($inputValues["destinationSuburb"])
							);
							
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

	
