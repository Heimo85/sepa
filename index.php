<?php
include_once("class.sepa.php");

$obj = new Sepa();
//$obj->setFileArray("class.sepa.csv");
//var_dump($obj->getFileArray());

if($obj->getControlSum() == FALSE)
{
	echo "keine Datei gesetzt";
}
else
{

}

?>

<!DOCTYPE html>
<head>
<title>SEPA-Datei ausgeben</title>
</head>
<body>
<form action="sepa-ls-create-rsc.php?act=start" method="post" name="xml_create">
Auftraggeber: <input type="text" name="Creditor" size="40" maxlength="70" value="Test"><br><br>
Auftraggeber-IBAN: <input type="text" name="CreditorIban" size="40" maxlength="34" value="DE50740012300000000001"><br><br>
Gl&auml;ubiger-ID: <input type="text" name="CredId" size="30" maxlength="35" value="DE46ZZZ00000000001"><br><br>
Ausf&uuml;hrungsdatum:<input name="exDate" type="date" value="2015-01-27"/><br><br>
Sequenz:<select name="seq">
  <option value="FRST">Erstmalig</option>
  <option value="RCUR">Wiederkehrend</option>
  <option value="FNAL">Letztmalig</option>
  <option value="OOFF">Einmalig</option>
</select><br><br>
Verwendungszweck:<input type="text" name="vwz" size="50" maxlength="140"><br><br>
<input type="submit" value="SEPA-LS Datei erstellen" name="xml_erzeugen">
</form>
</body>
</html>