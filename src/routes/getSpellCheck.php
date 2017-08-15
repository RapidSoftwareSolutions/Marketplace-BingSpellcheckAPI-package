<?php

$app->post('/api/BingSpellcheckAPI/getSpellCheck', function ($request, $response, $args) {
    $settings = $this->settings;

    $data = $request->getBody();
    $post_data = json_decode($data, true);
    if (!isset($post_data['args'])) {
        $data = $request->getParsedBody();
        $post_data = $data;
    }

    $error = [];
    if (empty($post_data['args']['subscriptionKey'])) {
        $error[] = 'subscriptionKey';
    }
    if (empty($post_data['args']['text'])) {
        $error[] = 'text';
    }

    if (!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = "REQUIRED_FIELDS";
        $result['contextWrites']['to']['status_msg'] = "Please, check and fill in required fields.";
        $result['contextWrites']['to']['fields'] = $error;
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }

    $query_str = $settings['api_url'];
    if (!empty($post_data['args']['mode'])) {
        $query_str .= 'mode=' . $post_data['args']['mode'];
    }


    $headers['Ocp-Apim-Subscription-Key'] = $post_data['args']['subscriptionKey'];


    $client = $this->httpClient;

    try {

        $resp = $client->post($query_str,
            [
                'form_params' => [
                    'text' => $post_data['args']['text']
                ],
                'headers' => $headers,
                'verify' => false
            ]);
        $responseBody = $resp->getBody()->getContents();
        if ($resp->getStatusCode() == '200') {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if (empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ServerException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if (empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ConnectException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
        $result['contextWrites']['to']['status_msg'] = 'Something went wrong inside the package.';

    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
