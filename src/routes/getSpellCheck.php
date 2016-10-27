<?php

$app->post('/api/BingSpellcheckAPI/getSpellCheck', function ($request, $response, $args) {
    $settings =  $this->settings;
    
    $data = $request->getBody();
    $post_data = json_decode($data, true);
    if(!isset($post_data['args'])) {
        $data = $request->getParsedBody();
        $post_data = $data;
    }
    
    $error = [];
    if(empty($post_data['args']['subscriptionKey'])) {
        $error[] = 'subscriptionKey cannot be empty';
    }
    if(empty($post_data['args']['text'])) {
        $error[] = 'text cannot be empty';
    }
    
    if(!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = implode(',', $error);
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }
    
    $query =[];
    if(!empty($post_data['args']['mode'])) {
        $query['mode'] = $post_data['args']['mode'];
    }
    
    
    $headers['Ocp-Apim-Subscription-Key'] = $post_data['args']['subscriptionKey'];
    $query_str = $settings['api_url'];
    
    $client = $this->httpClient;

    try {

        $resp = $client->post( $query_str, 
            [
                'form_params' => [
                    'text' => $post_data['args']['text']
                ],
                'query' => $query,
                'headers' => $headers,
                'verify' => false
            ]);
        $responseBody = $resp->getBody()->getContents();
        if($resp->getStatusCode() == '200') {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody();
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = json_decode($responseBody);

    } catch (GuzzleHttp\Exception\ServerException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = json_decode($responseBody);

    } catch (GuzzleHttp\Exception\BadResponseException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = json_decode($responseBody);

    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
