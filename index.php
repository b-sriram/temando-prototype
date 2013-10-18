<?php
	
	include 'RequestApp.php';

	$requestManager=new RequestApp();

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		
		$requestManager->setFieldValues($_POST);
		$requestManager->validate();
		
		if($requestManager->isValid)
		{			
			/*
			 * setting dimensionFieldValues with basic values and fieldValues
			 */
            $dimensionFieldValues=$requestManager->dimensionFields;
			$dimensionFieldValues["distanceMeasurementType"] = "Centimetres";
			$dimensionFieldValues["weightMeasurementType"] = "Kilograms";
			$dimensionFieldValues["class"] = "Freight";
			$dimensionFieldValues["quantity"] = "1";
			$dimensionFieldValues["mode"] = "Less than load";
			/*
			 * setting regionFieldValues with basic values and fieldValues
			 */
			$regionFields=$requestManager->regionFields;
			$regionFields["originCountry"] = "AU";
			$regionFields["destinationCountry"] = "AU";
			$regionFields["itemNature"] = "Domestic";
			$regionFields["itemMethod"] = "Door to Door";
            // Request quoteDetails using process manager 
			
			include 'TemandoProcessApp.php';
			$processManager = new TemandoProcessApp();
			$quoteDetails = $processManager-> getQuoteDetails($dimensionFieldValues, $regionFields);
		}

	}

	include 'QuickQuotes.html.php';

?>