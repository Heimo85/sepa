<?php

class Sepa
{
	private $file_array = array();
	private $firstRow;
	private $controlSum;
	private $creditorID;
	private $messageID;
	private $fileCreationDate;

	function __construct()
	{
		$this->firstRow = "0";
	}

	//set "0" for NO Column Heading in the first Row
	//set "1" for Column Heading in the first Row in the *.csv File.
	function setFirstRow($row)
	{
		$this->firstRow = $row;
	}

	//return the Value if Column Heading or not.
	function getFirstRow()
	{
		return $this->firstRow;
	}

	//open the *.cvs File and put it into an 2 dimensional array.
	function setFileArray($file)
	{
		$data = file($file);
		for( $i=0; $i < count( $data ); $i++ )
		{
			$this->file_array[$i] = explode( ";", $data[$i] );
		}
	}

	function getFileArray()
	{
		return $this->file_array;
	}

	//Returns the Control Summary of all Transactions, which are in the File
	//if no FileArray set, returns False
	function getControlSum()	
	{
		//CSV-Datei öffnen die sämtliche Transaktionen enthält
		if(count($this->getFileArray()) >= 1)
		{
			$data = $this->getFileArray();
			$this->controlSum="0.00";
			foreach ($data as $line)
			{
				$temp = str_replace(",","",$line[4]);
				$this->controlSum = $this->controlSum + $temp;
			}

			$beforeDecimal = substr($this->controlSum, 0, -2);//the Digits before the decimal point
			$afterDecimal = substr($this->controlSum, -2);//the Digits after the decimal point
			$this->controlSum = $beforeDecimal.".".$afterDecimal;//nachkommastelle setzen

			return $this->controlSum;
		}
		else
		{
			return FALSE;
		}
	}

	function setCreditorID($cred_id)
	{
		$this->$creditorID=substr($cred_id,0,34); //Creditor-ID, for Germany only at the Moment
	}

	function getCreditorID()
	{
		return $this->creditorID;
	}

	function setMessageID($time = time())
	{
		$this->messageID = $time;
	}

	function getMessageID()
	{
		return $this->messageID;
	}

	function setFileCreationDate()
	{
		//Zeitstempel wann die Datei erzeugt wurde um Doppeleinreichungen bei der Bank zu vermeiden
		$this->fileCreationDate=date("Y-m-d",time())."T".date("G:i:s",time())."Z"; 
	}

	function getFileCreationDate()
	{
		return $this->fileCreationDate;
	}


	
		
		$AnzTrans = count ($data); //Anzahl der Transaktionen die in der CSV Datei vorhanden sind.
				
		$VorKomma = substr($ControlSum, 0, -2);//nachkommastelle setzen
		$NachKomma = substr($ControlSum, -2);//nachkommastelle setzen
		$ControlSum = $VorKomma.".".$NachKomma;//nachkommastelle setzen
		//$ControlSum = number_format($ControlSum, 2);
		//$ControlSum = str_replace(",",".",$ControlSum); //SEPA Datei erwartet . anstatt ein , für die Nachkommastellen
		$Sequenz = $_POST['seq']; //Sequenz der SEPA-Lastschriften, möglich sind FRST = Erstmalig, RCUR = Wiederkehrend, OOFF = Einmalig, FNAL = letztmalig
		$CreditorName = substr($_POST['Creditor'],0,70); //Auftraggeber Name, Kontoinhaber auf welchen Namen das Geld eingezogen wird
		$BatchBooking = "true"; //Bei Wert "true" wird eine Sammelbuchung erstellt, bei Wert "false" werden Einzelbuchung am Kontoauszug ausgewiesen
		$DueDate=$_POST['exDate']; //Ausführungsdatum wann die Lastschriften auf dem Konto gutgeschrieben werden sollen. WICHTIG: Muss 2 Tage später als das Tagesdatum sein
		$CreditorNmb = substr($_POST['CreditorIban'],0,33); //Auftraggeber-Kontonummer auf welches Konto das Geld gutgeschrieben wird
		$CreditorBic = "BYLADEM0000"; //Auftraggeber-BIC auf welche BIC/BLZ das Geld gutgeschrieben wird, muss mit der IBAN übereinstimmen



}

?>