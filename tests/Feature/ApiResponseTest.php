<?php

namespace Tests\Feature;

use App\Http\Responses\ApiResponse;
use Tests\TestCase;

class ApiResponseTest extends TestCase
{
    public function test_success_response_structure()
    {
        $response = ApiResponse::success(['foo' => 'bar'], 'OK', 200);
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);
        $this->assertTrue($content['success']);
        $this->assertEquals('OK', $content['message']);
        $this->assertArrayHasKey('data', $content);
    }

    public function test_error_response_structure()
    {
        $response = ApiResponse::error('Bad', 400, ['field' => 'required'], 'REF123');
        $this->assertEquals(400, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);
        $this->assertFalse($content['success']);
        $this->assertEquals('Bad', $content['message']);
        $this->assertEquals('REF123', $content['reference']);
        $this->assertArrayHasKey('errors', $content);
    }
}
