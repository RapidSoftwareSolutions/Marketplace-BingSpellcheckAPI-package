<?php

namespace Tests\Functional;

class BingSpellcheckAPITest extends BaseTestCase {
    
    public $subscriptionKey = "31781a20eade448abecfc40b6851f751";

    public function testGetSpellCheck() {
        
        $var = '{
                    "args": {
                      "subscriptionKey": "'.$this->subscriptionKey.'",
                      "text": "Text for  gremmar chack errors"
                    }
                }';
        $post_data = json_decode($var, true);

        $response = $this->runApp('POST', '/api/BingSpellcheckAPI/getSpellCheck', $post_data);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertEquals('success', json_decode($response->getBody())->callback);
    }

}
