<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get all campaigns.
$app->get('/api/campaigns', function (Request $request, Response $response) {
    $sql = "SELECT * FROM campaigns";

    try {
        // Get db object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $campaigns = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        // Add the campaigns array into an object for response
        $campaignsResponse = array('campaigns'=>$campaigns);
        $newResponse = $response->withJson($campaignsResponse);
        return $newResponse;
    } catch (PDOException $e) {
        echo '{"error":{"text": '.$e->getMessage().'}}';
    }
});

// Get single book
$app->get('/api/campaigns/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM campaigns WHERE id = $id";

    try {
        // Get db object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $campaigns = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        // Add the campaigns array into an object for response
        $campaignsResponse = array('campaigns'=>$campaigns);
        $newResponse = $response->withJson($campaignsResponse);
        return $newResponse;
    } catch (PDOException $e) {
        echo '{"error":{"text": '.$e->getMessage().'}}';
    }
});



// Add product
$app->post('/api/campaigns', function (Request $request, Response $response) {
    $params = $request->getBody();


    $name = $request->getParam('name');
    $message = $request->getParam('message');
    $expiration_hour = $request->getParam('expiration_hour');
    $expiration_minute = $request->getParam('expiration_minute');
    $runs_monday = $request->getParam('runs_monday');
    $runs_tuesday = $request->getParam('runs_tuesday');
    $runs_wednesday = $request->getParam('runs_wednesday');
    $runs_thursday = $request->getParam('runs_thursday');
    $runs_friday = $request->getParam('runs_friday');
    $runs_saturday = $request->getParam('runs_saturday');
    $runs_sunday = $request->getParam('runs_sunday');

    $active = 1;

    $sql = "INSERT INTO campaigns (name,message,expiration_hour,expiration_minute,runs_monday,runs_tuesday,runs_wednesday,runs_thursday,runs_friday,runs_saturday,runs_sunday,active) VALUES (:name,:message,:expiration_hour,:expiration_minute,:runs_monday,:runs_tuesday,:runs_wednesday,:runs_thursday,:runs_friday,:runs_saturday,:runs_sunday,:active)";

    try {
        // Get db object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':expiration_hour', $expiration_hour);
        $stmt->bindParam(':expiration_minute', $expiration_minute);
        $stmt->bindParam(':runs_monday', $runs_monday);
        $stmt->bindParam(':runs_tuesday', $runs_tuesday);
        $stmt->bindParam(':runs_wednesday', $runs_wednesday);
        $stmt->bindParam(':runs_thursday', $runs_thursday);
        $stmt->bindParam(':runs_friday', $runs_friday);
        $stmt->bindParam(':runs_saturday', $runs_saturday);
        $stmt->bindParam(':runs_sunday', $runs_sunday);
        $stmt->bindParam(':active', $active);

        $stmt->execute();

        $newResponse = $response->withStatus(200);
        $body = $response->getBody();
        $body->write('{"status": "success","message": "campaign added", "Campaign": "'.$name.'"}');
        $newResponse = $newResponse->withBody($body);
        return $newResponse;
    } catch (PDOException $e) {
        echo '{"error":{"text": '.$e->getMessage().'}}';
    }
});

/*
// Update product
$app->put('/api/campaigns/{id}', function (Request $request, Response $response) {
    $params = $request->getBody();
    if ($request->getHeaders()['HTTP_AUTHORIZATION']) {
        $access_token = $request->getHeaders()['HTTP_AUTHORIZATION'][0];
        $access_token = explode(" ", $access_token)[1];
        // Find the access token, if a user is returned, post the products
        if (!empty($access_token)) {
            $user_found = verifyToken($access_token);
            if (!empty($user_found)) {
                $id = $request->getAttribute('id');

                // Get the queryparam to verify if its an enable/disable operation
                $operation = $request->getQueryParam('operation');

                if ($operation) {
                    if ($operation == 'enable') {
                        $activo = 1;
                    } else {
                        $activo = 0;
                    }

                    $sql = "UPDATE libros SET activo = :activo WHERE id = $id";

                    // Get db object
                    $db = new db();
                    // Connect
                    $db = $db->connect();

                    $stmt = $db->prepare($sql);

                    $stmt->bindParam(':activo', $activo);

                    $stmt->execute();

                    $newResponse = $response->withStatus(200);
                    $body = $response->getBody();
                    $body->write('{"status": "success","message": "Libro actualizado"}');
                    $newResponse = $newResponse->withBody($body);
                } else {
                    $titulo = $request->getParam('titulo');
                    $autor = $request->getParam('autor');
                    $ano = $request->getParam('ano');
                    $resumen = $request->getParam('resumen');
                    $idioma = $request->getParam('idioma');
                    $usr_dueno = $request->getParam('usr_dueno');
                    $activo = $request->getParam('activo');
                    //$tapa = $request->getParam('tapa');

                    $sql = "UPDATE libros SET
        titulo = :titulo,
        autor = :autor,
        ano = :ano,
        resumen = :resumen,
        idioma = :idioma,
        usr_dueno = :usr_dueno,
        activo = :activo
        WHERE id = $id";

                    try {
                        // Get db object
                        $db = new db();
                        // Connect
                        $db = $db->connect();

                        $stmt = $db->prepare($sql);

                        $stmt->bindParam(':titulo', $titulo);
                        $stmt->bindParam(':autor', $autor);
                        $stmt->bindParam(':ano', $ano);
                        $stmt->bindParam(':resumen', $resumen);
                        $stmt->bindParam(':idioma', $idioma);
                        $stmt->bindParam(':usr_dueno', $usr_dueno);
                        $stmt->bindParam(':activo', $activo);

                        $stmt->execute();

                        $newResponse = $response->withStatus(200);
                        $body = $response->getBody();
                        $body->write('{"status": "success","message": "Libro actualizado"}');
                        $newResponse = $newResponse->withBody($body);
                    } catch (PDOException $e) {
                        echo '{"error":{"text": '.$e->getMessage().'}}';
                    }
                }
            } else {
                return loginError($response, 'Error de login, usuario no encontrado');
            }
        } else {
            return loginError($response, 'Error de login, falta access token');
        }
    } else {
        return loginError($response, 'Error de encabezado HTTP');
    }
});

*/
// Return a response with a 401 not allowed error.
function loginError(Response $response, $errorText)
{
    $newResponse = $response->withStatus(401);
    $body = $response->getBody();
    $body->write('{"status": "login error","message": "'.$errorText.'"}');
    $newResponse = $newResponse->withBody($body);
    return $newResponse;
}
