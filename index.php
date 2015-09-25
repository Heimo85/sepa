<?php
include_once("class.sepa.php");


if(isset($_GET['act']))
{
	if($_GET['act'] == "start")
	{
		$request = array_merge($_POST);
		var_dump($request);
		$obj = new Sepa();
		$obj->setFileArray("class.sepa.csv");
		$obj->setMessageID();
		$obj->setFileCreationDate();
		$obj->setCreditorName("Max Mustermann");
		$obj->setBatchBooking("true");
		$obj->setSequence("RCUR");
		$obj->setDueDate("2015-09-23");
		$obj->setCreditorNmb("DE46ZZZ00000000001");
		$obj->setCreditorBic("BYLADEM1FRG");
		$obj->setFirstRow("1");
		$obj->setXMLHeader();
		print_r($obj->getXMLHeader());
	}
}
?>

<!DOCTYPE html>
<head>
<title>SEPA-Datei ausgeben</title>
<link href="style.css" type="text/css" rel="stylesheet">
</head>
<body>
<form action="index.php?act=start" method="post" name="xml_create">
Auftraggeber: <input type="text" name="Creditor" size="40" maxlength="70" value="Test"><br><br>
Auftraggeber-IBAN: <input type="text" name="CreditorIban" size="40" maxlength="34" value="DE50740012300000000001"><br><br>
Gl&auml;ubiger-ID: <input type="text" name="CredId" size="30" maxlength="35" value="DE46ZZZ00000000001"><br><br>
Ausf&uuml;hrungsdatum:<input name="exDate" type="date"><br><br>
Sequenz:<select name="seq">
  <option value="FRST">Erstmalig</option>
  <option value="RCUR">Wiederkehrend</option>
  <option value="FNAL">Letztmalig</option>
  <option value="OOFF">Einmalig</option>
</select><br><br>
Verwendungszweck:<input type="text" name="vwz" size="50" maxlength="140"><br><br>
<input type="submit" value="SEPA-LS Datei erstellen" name="xml_erzeugen">
</form>

<div id="box2">1</div>

</body>
</html>