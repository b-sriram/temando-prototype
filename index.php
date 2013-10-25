<?php
    include 'RequestApp.php';
    
    $requestManager = new RequestApp();
    
    // On Request form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Validate form fields using request manager App
        $requestManager->setFieldValues($_POST);
        $requestManager->validate();
        
        // Temando api calling process, After all fields are validated
        if($requestManager->isValid)
        {
            // Set fieldValues and default/static values
            $regionFields = $requestManager->regionFields;
            $regionFields["originCountry"] = "AU";
            $regionFields["destinationCountry"] = "AU";
            $regionFields["itemNature"] = "Domestic";
            $regionFields["itemMethod"] = "Door to Door";
            
            $dimensionFieldValues = $requestManager->dimensionFields;
            $dimensionFieldValues["distanceMeasurementType"] = "Centimetres";
            $dimensionFieldValues["weightMeasurementType"] = "Kilograms";
            $dimensionFieldValues["class"] = "Freight";
            $dimensionFieldValues["quantity"] = "1";
            $dimensionFieldValues["mode"] = "Less than load";

            // Get quotes using Temando Process App
            include 'TemandoProcessApp.php';
            $processManager = new TemandoProcessApp();
            $response = $processManager -> getQuoteDetails($regionFields, $dimensionFieldValues);
        }
    }
    
    include 'QuickQuotes.html.php';
    
?>
