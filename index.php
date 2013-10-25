<?php
	
	include 'RequestApp.php';
    //creating request manager object
	$requestManager=new RequestApp();
    //After submitting the form 
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		//Validating form fields using request manager App
		$requestManager->setFieldValues($_POST);
		$requestManager->validate();

		//requesting temando api calling process, After all field values are validated 
		if($requestManager->isValid)
		{
			//setting regionFieldValues with basic values and fieldValues
			$regionFields=$requestManager->regionFields;
			$regionFields["originCountry"] = "AU";
			$regionFields["destinationCountry"] = "AU";
			$regionFields["itemNature"] = "Domestic";
			$regionFields["itemMethod"] = "Door to Door";

            //setting dimensionFieldValues with basic values and fieldValues
            $dimensionFieldValues=$requestManager->dimensionFields;
            $dimensionFieldValues["distanceMeasurementType"] = "Centimetres";
            $dimensionFieldValues["weightMeasurementType"] = "Kilograms";
            $dimensionFieldValues["class"] = "Freight";
            $dimensionFieldValues["quantity"] = "1";
            $dimensionFieldValues["mode"] = "Less than load";

            // Request quoteDetails using process manager	
			include 'TemandoProcessApp.php';
            $processManager = new TemandoProcessApp();
            $response = $processManager -> getQuoteDetails($regionFields, $dimensionFieldValues);
		}

	}

	include 'QuickQuotes.html.php';

?>