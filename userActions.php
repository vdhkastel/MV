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
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM Personen;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("SELECT * FROM Personen ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . ";");
		
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
	//Getting filtered records (listAction)
	else if($_GET["action"] == "listByFilt")
	{
        // split search string into array elements
        $searchStr=preg_split("/[\s,]+/",$_POST["name"]);
        // concat filter string
        $fstr="";
        foreach($searchStr AS $str){
            $fstr=$fstr." (Name LIKE '%".$str."%' OR Vorname LIKE '%".$str."%') AND ";
            }
		//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM Personen WHERE(".substr($fstr,0,-5).");");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("SELECT * FROM Personen  WHERE (".substr($fstr,0,-5).") ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . ";");

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
		$result = mysql_query("INSERT INTO Personen(Name, Vorname, Geburtstag, Geschlecht, HSVRMNr, Status) VALUES('" . $_POST["Name"] . "', '" . $_POST["Vorname"] . "','" . $_POST["Geburtstag"]."','". $_POST["Geschlecht"]. "','" . $_POST["HSVRMNr"]."','". $_POST["Status"]."');");
		//$result = mysql_query("INSERT INTO Personen(Name, Vorname, Geburtstag, Geschlecht, HSVRMNr, Status) VALUES('Name','Vorname','24.09.1988','m','123','Vollmitglied');");

		//Get last inserted record (to return to jTable)
		$result = mysql_query("SELECT * FROM Personen WHERE PersonID = LAST_INSERT_ID();");
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
		$result = mysql_query("UPDATE Personen SET Name = '" . $_POST["Name"] . "', Vorname = '" . $_POST["Vorname"] . "', Geburtstag = '" . $_POST["Geburtstag"] . "', Geschlecht = '" . $_POST["Geschlecht"] . "', HSVRMNr = '" . $_POST["HSVRMNr"] .  "', Status = '" . $_POST["Status"] . "' WHERE PersonID = '" . $_POST["PersonID"] . "';");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	//Deleting a record (deleteAction)
	else if($_GET["action"] == "delete")
	{
		//Delete from database
		$result = mysql_query("DELETE FROM Personen WHERE PersonID = " . $_POST["PersonID"] . ";");

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
