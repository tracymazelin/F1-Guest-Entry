<?php
require('FellowshipOne.php');

	$settings = array(
		'key'=>'your key here',
		'secret'=>'your secret here',
		'username'=>'portal username here - must be linked to a person in f1',
		'password'=>'password',
		'baseUrl'=>'https://yourchurchcode.fellowshiponeapi.com',
		'debug'=>true,
	);
	
	//instantiate the f1 class
	
	$f1 = new FellowshipOne($settings);
		if(($r = $f1->login()) === false){
		die("Failed to login");
	}
	
	echo "<pre>";
	
	//Get F1 Household json model
	
	$model = $f1->householdModel;
	
	//Update model with form info
	
	$model['household']['householdName'] = $_POST['firstName'].' '.$_POST['lastName'];
	$model['household']['householdSortName'] = $_POST['lastName'];
	$model['household']['householdFirstName'] = $_POST['firstName'];
	
	//Create Household in F1
	$results = $f1->createHousehold($model);
		
	//Create a person in the household we just created
		
	//First determine household position
			
	$householdPosition = $_POST['householdPosition'];
	
	if($householdPosition == "Head"){
		$householdMemberType = "1";
		} elseif($householdPosition == "Spouse"){
			$householdMemberType = "2";
			} else {
				$householdMemberType = "3";
				}
	
	$today = new DateTime('now');
	
	//Get the F1 Person json model
	$model = $f1->personModel;
	
	//Update the person model with the form info
	
	$model['person']['@householdID'] = $results['household']['@id']; 
	$model['person']['firstName'] = $_POST['firstName'];
	$model['person']['lastName'] = $_POST['lastName'];
	$model['person']['gender'] = $_POST['gender'];
	$model['person']['dateOfBirth'] = date(DATE_ATOM,mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']));	
	$model['person']['maritalStatus'] = $_POST['maritalStatus'];
	$model['person']['householdMemberType']['@id'] = $householdMemberType;
	$model['person']['householdMemberType']['name'] = $householdPosition;
	$model['person']['status']['@id'] = '110';
	$model['person']['status']['date'] = $today->format(DATE_ATOM);
	
	
	//Write the person in the household into F1
	$r = $f1->createPerson($model);
	
	//Store the personID	
	$personID =	$r['person']['@id'];
	
	//get the communications json model from F1
	$model = $f1->getCommunicationModel($personID);
	
	//Update the json model with the email address
	$model['communication']['communicationType']['@id'] = "4";
	$model['communication']['communicationType']['name'] = "email";
	$model['communication']['communicationValue'] = $_POST['email'];
	
	//Write the email communication into F1
	$r = $f1->createCommunication($model, $personID);
	
	//Update the json model with the telephone info
	$model['communication']['communicationType']['@id'] = "1";
	$model['communication']['communicationType']['name'] = "telephone";
	$model['communication']['communicationValue'] = $_POST['primaryPhone'];
	
	//Write the telephone value into F1
	$r = $f1->createCommunication($model, $personID);
		
	//Check if a file was uploaded and move it out of the temp directory then write it to F1

	if ($_FILES['image']['name']){
		define('UPLOAD_DIR', '/path/to/directory/');
		move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR.$_FILES['image']['name']);
		$imageStr = file_get_contents(UPLOAD_DIR.$_FILES['image']['name']);
		$r = $f1->createImage($imageStr, $personID);
	}	
?>