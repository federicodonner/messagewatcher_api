<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Add product
$app->post('/api/report', function (Request $request, Response $response) {
    $params = $request->getBody();


    $campaign_id = $request->getParam('campaign');
    $sql = "SELECT * from campaigns WHERE id = $campaign_id";


    try {
        // Get db object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $campaign = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $to = "federico.donner@telefonica.com";
        $subject = "Ejecución de campaña ".$campaign->name." fallada.";
        $message = "La campaña ".$campaign->name." falló en su ejecución.";
        $message .= "El mensaje es '".$campaign->message."'.";

        $newResponse = $response->withStatus(200);
        $body = $response->getBody();

        // Sending email
        if (mail($to, $subject, $message)) {
            $body->write('{"status": "success","message": "email sent"}');
        } else {
            $body->write('{"status": "failure","message": "email sending failed"}');
        }
        $newResponse = $newResponse->withBody($body);
        return $newResponse;
    } catch (PDOException $e) {
        echo '{"error":{"text": '.$e->getMessage().'}}';
    }
});
