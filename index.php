<?php
include_once("class.sepa.php");


if(isset($_GET['act']))
{
	if($_GET['act'] == "start" && $_FILES['Datei']['name'] != "")
	{
		//var_dump($_FILES);
		if($_FILES['Datei']['size'] <= "1500000")
		{
			$obj = new Sepa();
			$obj->setFileArray($_FILES['Datei']['tmp_name'],$_FILES['Datei']['name']);
			$obj->setMessageID();
			$obj->setFileCreationDate();
			$obj->setCreditorID($_POST["CredId"]);
			$obj->setCreditorName($_POST["Creditor"]);
			if(isset($_POST['batch']))
			{
				$obj->setBatchBooking("false");
			}
			$obj->setUsage($_POST["vwz"]);
			$obj->setSequence($_POST["seq"]);
			$obj->setDueDate($_POST["exDate"]);
			$obj->setCreditorNmb($_POST["CreditorIban"]);
			$obj->setCreditorBic($_POST["CreditorBIC"]);
			$obj->setFirstRow("1");
			$obj->setXMLHeader();
			$obj->setXMLTransactions();
			$obj->setXMLFooter();

			$temp = $obj->getXMLHeader().$obj->getXMLTransactions().$obj->getXMLFooter();

			
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=\"SEPA-Lastschrift-Core1.xml\"");
			header("Content-Length: ". strlen($temp));
			echo $obj->getXMLHeader().$obj->getXMLTransactions().$obj->getXMLFooter();
			exit();
			
		}
		else
		{
			echo "Hochgeladene Datei zu groß! Datei muss kleiner als 1,5 Megabyte sein!";
		}
	}
	else
	{
		echo "Keine Datei &Uuml;bergeben!";
	}
}
?>

<!DOCTYPE html>
<head>
<title>SEPA-Datei ausgeben</title>
<script src="jquery-1.11.3.min.js" type="text/javascript"></script>
<script src="jscript.js" type="text/javascript"></script>
<link href="style.css" type="text/css" rel="stylesheet">
</head>
<body>

<form action="index.php?act=start" method="post" name="xml_create" enctype="multipart/form-data">
<div class="top">
	<ul id="drawers" class="draw">
		<li><h5>1. Auftraggeber</h5>
			<div class="first" style="display: none;">
				<p>Auftraggeber: <input type="text" name="Creditor" size="40" maxlength="70" value="Test"></p>
				<p class="weiter" alt="weiter">&raquo;</p>
			</div>
		</li>
		<li><h5>2. Auftraggeber-IBAN:</h5>
			<div class="first" style="display: none;">
				<p>Auftraggeber: <input type="text" name="CreditorIban" size="40" maxlength="34" value="DE50740012300000000001"></p>
				<p class="weiter">&raquo;</p>
			</div>
		</li>
		<li><h5>3. Auftraggeber-BIC:</h5>
			<div class="first" style="display: none;">
				<p>Auftraggeber: <input type="text" name="CreditorBIC" size="40" maxlength="34" value="BYLADEM1FRG"></p>
				<p class="weiter">&raquo;</p>
			</div>
		</li>
		<li><h5>4. Gl&auml;ubiger-ID:</h5>
			<div class="first" style="display: none;">
				<p>Gl&auml;ubiger-ID: <input type="text" name="CredId" size="30" maxlength="35" value="DE46ZZZ00000000001"></p>
				<p class="weiter">&raquo;</p>
			</div>
		</li>
		<li><h5>5. Ausf&uuml;hrungs-Datum</h5>
			<div class="first" style="display: none;">
				<p>Ausf&uuml;hrungsdatum:<input name="exDate" type="date"></p>
				<p class="weiter">&raquo;</p>
			</div>
		</li>
		<li><h5>6. Ausf&uuml;hrungs-Rhythmus:</h5>
			<div class="first" style="display: none;">
				<p>Sequenz:
				<select name="seq">
				  <option value="FRST">Erstmalig</option>
				  <option value="RCUR">Wiederkehrend</option>
				  <option value="FNAL">Letztmalig</option>
				  <option value="OOFF">Einmalig</option>
				</select>
				</p>
				<p class="weiter">&raquo;</p>
			</div>
		</li>
		<li><h5>7. Verwendungszweck</h5>
			<div class="first" style="display: none;">
				<p>Verwendungszweck:
				<input type="textarea" name="vwz" size="50" maxlength="140">
				</p>
				<p class="weiter">&raquo;</p>
			</div>
		</li>
		<li><h5>8. Sammelaufl&ouml;sung</h5>
			<div class="first" style="display: none;">
				<p>Sollen die Lastschriften einzeln am Konto angezeigt werden?<br>
					<input type="checkbox" name="batch" value="false"> ja
				</p>
				<p class="weiter">&raquo;</p>
			</div>
		</li>
		<li><h5>9. Datei Upload</h5>
			<div class="first" style="display: none;">
				<p>Bitte Quelldatei ausw&auml;hlen (nur ".csv"):<br>
					<input name="Datei" type="file" size="50" accept=".csv"> 
				</p>
				<p class="weiter">&raquo;</p>
			</div>
		</li>
	</ul>
</div>
<input type="submit" value="SEPA-LS Datei erstellen" name="xml_erzeugen">
</form>


</body>
</html>