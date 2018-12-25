<?php 
require('vendor/autoload.php');

class BooksTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://localhost/srijan_task'
        ]);
    }

    public function testPost_NewBook_BookObject()
    {
		$text_encrypt = "Test";

		$response = $this->client->post('/form_fields.php', [
			'json' => [
				'word_enc'    => $text_encrypt,
			]
		]);

		$this->assertEquals(200, $response->getStatusCode());

		$data = json_decode($response->getBody(), true);

		$this->assertEquals($text_encrypt, $data['decrypt']);
   }
}
 