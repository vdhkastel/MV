<?php

try
{
	//Open database connection
	$con = mysql_connect("mysql.localhost","db47014","58rara4n");
	mysql_select_db("db47014", $con);

	//Getting records (listAction)
	if($_GET["action"] == "list")
	{
		//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM Telefon WHERE PersonID = " . $_GET["PersonID"] . ";");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("SELECT * FROM Telefon WHERE PersonID = " . $_GET["PersonID"] . ";");

		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{
		    $rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}

	//Creating a new record (createAction)
	else if($_GET["action"] == "create")
	{
		//Insert record into database
		$result = mysql_query("INSERT INTO Telefon(PersonID, Typ, Nummer) VALUES('" . $_GET["PersonID"] . "','" . $_POST["Typ"]."','". $_POST["Nummer"]."');");

		//Get last inserted record (to return to jTable)
		$result = mysql_query("SELECT * FROM Telefon WHERE TelefonID = LAST_INSERT_ID();");
		$row = mysql_fetch_array($result);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $row;
		print json_encode($jTableResult);
	}

	//Updating a record (updateAction)
	else if($_GET["action"] == "update")
	{
		//Update record in database
		$result = mysql_query("UPDATE Telefon SET Typ = '" . $_POST["Typ"] . "', Nummer = '" . $_POST["Nummer"] . "' WHERE TelefonID = '" . $_POST["TelefonID"] . "';");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	//Deleting a record (deleteAction)
	else if($_GET["action"] == "delete")
	{
		//Delete from database
		$result = mysql_query("DELETE FROM Telefon WHERE TelefonID = " . $_POST["TelefonID"] . ";");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}


	//Close database connection
	mysql_close($con);

}
catch(Exception $ex)
{
    //Return error message
	$jTableResult = array();
	$jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = $ex->getMessage();
	print json_encode($jTableResult);
}

?>
