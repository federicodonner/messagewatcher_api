<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Add product
$app->post('/api/keepalive', function (Request $request, Response $response) {

    $timestamp = time()-10800;

      $sql = "INSERT INTO keepalive (keepalive_date) VALUES (:keepalive_date)";

    try {
        // Get db object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':keepalive_date', $timestamp);


        $stmt->execute();

        $newResponse = $response->withStatus(200);
        $body = $response->getBody();
        $body->write('{"status": "success","message": "keepalive registered"}');
        $newResponse = $newResponse->withBody($body);
        return $newResponse;
    } catch (PDOException $e) {
        echo '{"error":{"text": '.$e->getMessage().'}}';
    }
});
