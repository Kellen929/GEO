<?php
include "../config/security.php";
include "../incl/lib/connection.php";
require_once "../incl/lib/exploitPatch.php";

if(!isset($preactivateAccounts)){
	$preactivateAccounts = true;
}

if($_POST["userName"] != ""){
	//here im getting all the data
	$userName = ExploitPatch::remove($_POST["userName"]);
	$password = ExploitPatch::remove($_POST["password"]);
	$email = ExploitPatch::remove($_POST["email"]);
	$secret = "";
	//checking if name is taken
	$query2 = $db->prepare("SELECT count(*) FROM accounts WHERE userName LIKE :userName");
	$query2->execute([':userName' => $userName]);
	$regusrs = $query2->fetchColumn();
	if ($regusrs > 0) {
		echo "-2";
	}else{
		$hashpass = password_hash($password, PASSWORD_DEFAULT);
		$query = $db->prepare("INSERT INTO accounts (userName, password, email, registerDate, isActive)
		VALUES (:userName, :password, :email, :time, :isActive)");
		$query->execute([':userName' => $userName, ':password' => $hashpass, ':email' => $email, ':time' => time(), ':isActive' => $preactivateAccounts ? 1 : 0]);
		echo "1";
	}
}
?>