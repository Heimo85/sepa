<?php

class Sepa
{
	private $file_array = array();
	private $firstRow;
	private $controlSum;
	private $creditorID;
	private $messageID;
	private $fileCreationDate;
	private $sequence;
	private $creditorName;
	private $batchBooking;
	private $dueDate;
	private $creditorNmb;
	private $creditorBic;
	private $xmlHeader;

	function __construct()
	{
		$this->firstRow = "0";
		$this->batchBooking = "true";
	}

	//set "0" for NO Column Heading in the first Row
	//set "1" for Column Heading in the first Row in the *.csv File.
	function setFirstRow($row)
	{	
		if($row == "1")
		{
			unset($this->file_array[0]);
		}
		$this->firstRow = $row;
	}

	//return the Value if there is a Column Heading in the File.
	function getFirstRow()
	{
		return $this->firstRow;
	}

	//open the *.cvs File and put it into an 2 dimensional array.
	function setFileArray($file)
	{
		$data = file($file);
		for( $i=0; $i < count($data); $i++ )
		{
			$this->file_array[$i] = explode( ";", $data[$i] );
		}
	}

	function getFileArray()
	{
		return $this->file_array;
	}

	function getSumTransactions()
	{
		if($this->firstRow == 0 && count($this->getFileArray()) >= 1)
		{
			return count($this->getFileArray());
		}
		elseif ($this->firstRow == 1 && count($this->getFileArray()) == 1)
		{
			//return "First Row Parameter is 1, in the File are only 1 Row.<br> It seems that there are only the Column Heading in the File.
			//		<br> Set First Row Parameter to 0 if there are Data at the First Row!";
			return "0";
		}
		elseif ($this->firstRow == 1 && count($this->getfileArray()) > 1)
		{
			return count($this->getfileArray());
		}
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
		$this->$creditorID=substr($cred_id,0,34); //Creditor-ID, only german ID's allowed
	}

	function getCreditorID()
	{
		return $this->creditorID;
	}

	//Zeitstempel für die Nachrichten ID um Doppeleinreichungen bei der Bank zu vermeiden
	function setMessageID()
	{
		$this->messageID = time();
	}

	function getMessageID()
	{
		return $this->messageID;
	}

	//Zeitstempel wann die Datei erzeugt wurde um Doppeleinreichungen bei der Bank zu vermeiden
	function setFileCreationDate()
	{
		$this->fileCreationDate=date("Y-m-d",time())."T".date("G:i:s",time())."Z"; 
	}

	function getFileCreationDate()
	{
		return $this->fileCreationDate;
	}

	//Sequenz der SEPA-Lastschriften, möglich sind FRST = Erstmalig, RCUR = Wiederkehrend, OOFF = Einmalig, FNAL = letztmalig
	function setSequence($seq)
	{
		$this->sequence = $seq;
	}

	function getSequence()
	{
		return $this->sequence;
	}

	//Auftraggeber Name, Kontoinhaber auf welchen Namen das Geld eingezogen wird
	function setCreditorName($name)
	{
		$this->creditorName = substr($name,0,70);
	}

	function getCreditorName()
	{
		return $this->creditorName;
	}

	//Bei Wert "true" wird eine Sammelbuchung erstellt, bei Wert "false" werden Einzelbuchung am Kontoauszug ausgewiesen
	//Standardwert ist "true"
	function setBatchBooking($batch)
	{
		$this->batchBooking = $batch;
	}

	function getBatchBooking()
	{
		return $this->batchBooking;
	}

	//Ausführungsdatum wann die Lastschriften auf dem Konto gutgeschrieben werden sollen. WICHTIG: Muss 2 Tage später als das Tagesdatum sein
	function setDueDate($date)
	{
		$this->dueDate = $date;
	}

	function getDueDate()
	{
		return $this->dueDate;
	}

	//Auftraggeber-Kontonummer auf welches Konto das Geld gutgeschrieben wird
	function setCreditorNmb($nmb)
	{
		$this->creditorNmb = $nmb;
	}

	function getCreditorNmb()
	{
		return $this->creditorNmb;
	}

	//Auftraggeber-BIC auf welche BIC/BLZ das Geld gutgeschrieben wird, muss mit der IBAN übereinstimmen
	function setCreditorBic($bic)
	{
		$this->creditorBic = $bic;
	}

	function getCreditorBic()
	{
		return $this->creditorBic;
	}

	function setXMLHeader()
	{
		$this->xmlHeader =
		'<?xml version="1.0" encoding="UTF-8"?>
		<Document xmlns="urn:iso:std:iso:20022:tech:xsd:pain.008.003.02" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:iso:std:iso:20022:tech:xsd:pain.008.003.02 pain.008.003.02.xsd">
		  <CstmrDrctDbtInitn>
			<GrpHdr>
			  <MsgId>'.$this->getMessageID().'</MsgId>
			  <CreDtTm>'.$this->getFileCreationDate().'</CreDtTm>
			  <NbOfTxs>'.$this->getSumTransactions().'</NbOfTxs>
			  <CtrlSum>'.$this->getControlSum().'</CtrlSum>
			  <InitgPty>
				<Nm>'.$this->getCreditorName().'</Nm>
			  </InitgPty>
			</GrpHdr>
			<PmtInf>
			  <PmtInfId>'.$this->getMessageID().'</PmtInfId>
			  <PmtMtd>DD</PmtMtd>
			  <BtchBookg>'.$this->getBatchBooking().'</BtchBookg>
			  <NbOfTxs>'.$this->getSumTransactions().'</NbOfTxs>
			  <CtrlSum>'.$this->getControlSum().'</CtrlSum>
			  <PmtTpInf>
				<SvcLvl>
				  <Cd>SEPA</Cd>
				</SvcLvl>
				<LclInstrm>
				  <Cd>COR1</Cd>
				</LclInstrm>
				<SeqTp>'.$this->getSequence().'</SeqTp>
			  </PmtTpInf>
			  <ReqdColltnDt>'.$this->getDueDate().'</ReqdColltnDt>
			  <Cdtr>
				<Nm>'.$this->getCreditorName().'</Nm>
			  </Cdtr>
			  <CdtrAcct>
				<Id>
				  <IBAN>'.$this->getCreditorNmb().'</IBAN>
				</Id>
			  </CdtrAcct>
			  <CdtrAgt>
				<FinInstnId>
				  <BIC>'.$this->getCreditorBic().'</BIC>
				</FinInstnId>
			  </CdtrAgt>
			  <ChrgBr>SLEV</ChrgBr>';
	}



		/*		
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
		*/


}

?>