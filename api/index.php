<?php

require 'Slim/Slim.php';


$app = new Slim();

$app->get('/users', 'getUsers');
$app->get('/users/:id', 'getUser');
$app->post('/users/:username/:password', 'getLoginUser');
$app->get('/issues', 'getIssues');
$app->get('/issues/:issueID', 'getOneIssues');
$app->post('/issues', 'addIssue');
$app->put('/issues/:id', 'updateIssue');
$app->delete('/issues/:id', 'deleteIssue');

$app->run();

function getUsers() {
	$sql = "select * FROM users ORDER BY lastName";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$users = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($users);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getLoginUser($uname, $pass) {
    $sql = "SELECT users.userID,
            	users.username,
            	users.email,
            	users.firstName,
            	users.lastName,
            	users.authLevel,
            	users.`password`,
            	users.phone,
            	users.sendText,
            	users.sendEmail
            FROM users
            WHERE users.username='$uname' and users.password ='$pass'";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("username", $uname);
        $stmt->bindParam("password", $pass);
        $stmt->execute();
        $user = $stmt->fetchObject();
        $db = null;
        echo json_encode($user);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getUser($id) {
    $sql = "SELECT * FROM users
   WHERE users.userID=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $issue = $stmt->fetchObject();
        $db = null;
        echo json_encode($issue);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getIssues() {
    $sql = "SELECT users.firstName,users.lastName,issue.dateCreated,issue.dateRevised,issue.dateClosed,issue.description,issue.issueID
  FROM issue
  INNER JOIN users ON issue.userID = users.userID";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $issues = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($issues);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getOneIssues($id) {
    $sql = "SELECT users.firstName,users.lastName,issue.dateCreated,issue.dateRevised,issue.dateClosed,issue.description,issue.issueID
  FROM issue
  INNER JOIN users ON issue.userID = users.userID
   WHERE issue.issueID=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $issue = $stmt->fetchAll(PDO::FETCH_OBJ);//fetchObject();
        $db = null;
        echo json_encode($issue);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


function updateIssue($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $issue = json_decode($body);
    $sql = "UPDATE issue SET  userID=:userID, dateCreated=:dateCreated, dateRevised=:dateRevised, description=:description, dateClosed=:dateClosed, resolvedDescrip=:resolvedDescrip, resolvedBy=:resolvedBy WHERE issueID=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("userID", $issue->userID);
        $stmt->bindParam("dateCreated", $issue->dateCreated);
        $stmt->bindParam("dateRevised", $issue->dateRevised);
        $stmt->bindParam("description", $issue->description);
        $stmt->bindParam("dateClosed", $issue->dateClosed);
        $stmt->bindParam("resolvedDescrip", $issue->resolvedDescrip);
        $stmt->bindParam("resolvedBy", $issue->resolvedBy);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo json_encode($issue);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function deleteIssue($id) {
    $sql = "DELETE FROM issue WHERE issueID=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}



function getConnection() {
	$dbhost="127.0.0.1";
	$dbuser="";
	$dbpass="";
	$dbname="";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}
