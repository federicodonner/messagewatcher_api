<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get all campaigns.
$app->get('/api/returnreports', function (Request $request, Response $response) {
    $sql = "SELECT * FROM reports ORDER BY  message_date DESC LIMIT 5";

    try {
        // Get db object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $reports = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        // Add the campaigns array into an object for response
        $reportsResponse = array('reports'=>$reports);
        $newResponse = $response->withJson($reportsResponse);
        return $newResponse;
    } catch (PDOException $e) {
        echo '{"error":{"text": '.$e->getMessage().'}}';
    }
});


// Get all campaigns.
$app->get('/api/returnkeepalives', function (Request $request, Response $response) {
    $sql = "SELECT * FROM keepalive ORDER BY  keepalive_date DESC LIMIT 10";

    try {
        // Get db object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $reports = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        // Add the campaigns array into an object for response
        $reportsResponse = array('keepalives'=>$reports);
        $newResponse = $response->withJson($reportsResponse);
        return $newResponse;
    } catch (PDOException $e) {
        echo '{"error":{"text": '.$e->getMessage().'}}';
    }
});
