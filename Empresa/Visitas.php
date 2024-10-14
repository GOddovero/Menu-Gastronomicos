<?php
function getVisitas($db, $empresaid): int
{
	$sql = "SELECT visitas FROM visitas WHERE empresaid = :empresaid";
	$stmt = $db->prepare($sql);
	$stmt->bindparam(":empresaid", $empresaid);
	$visitas = 0;
	try {
		$stmt->bindparam(":empresaid", $empresaid);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($result) > 0) {
			$visitas = $result[0]['visitas'] + 1;
			$sql = "UPDATE visitas SET visitas = visitas + 1 WHERE empresaid = :empresaid";
		} else {
			$visitas = 1;
			$sql = "INSERT INTO visitas (empresaid, visitas) VALUES (:empresaid, 1)";
		}
		$stmt = $db->prepare($sql);
		$stmt->bindparam(":empresaid", $empresaid);
		$stmt->execute();
	} catch (PDOException $e) {
		header('Location: error.php');
		exit;
	}
	return	$visitas;
}
