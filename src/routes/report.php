<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Add product
$app->post('/api/report', function (Request $request, Response $response) {
      $params = $request->getBody();

    $text = $request->getParam('text');
    $timestamp = time()-10800;

      $sql = "INSERT INTO reports (message_date,message_text) VALUES (:message_date,:message_text)";

    try {
        // Get db object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':message_date', $timestamp);
        $stmt->bindParam(':message_text', $text);

        $stmt->execute();

        $newResponse = $response->withStatus(200);
        $body = $response->getBody();
        $body->write('{"status": "success","message": "report added"}');
        $newResponse = $newResponse->withBody($body);
        return $newResponse;
    } catch (PDOException $e) {
        echo '{"error":{"text": '.$e->getMessage().'}}';
    }
});
