<?php

// чет от других классов не получилось расширится, пришлось использовать TestCase, поэтому так же пришлось использовать curl

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;

class StaffsControllerTest extends TestCase
{
    private $url = 'http://127.0.0.1:8000';
    private $userId = 1;

    public function testIndex()
    {
        $ch = curl_init($this->url . '/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        $html = curl_exec($ch);
        $http_code = 0;

        if ($html !== false) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }

        curl_close($ch);

        $this->assertEquals(200, $http_code);
    }

    public function testGetAll()
    {
        $ch = curl_init($this->url . '/api/v1/staffs');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        $response = curl_exec($ch);
        $http_code = 0;
        $contentType = "";

        if ($response !== false) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        }

        curl_close($ch);

        $this->assertEquals(200, $http_code);
        $this->assertEquals("application/json", $contentType);
    }

    public function testCreate()
    {
        $data = [
            "name" => "test_" . time(),
        ];

        $ch = curl_init($this->url . '/api/v1/staffs');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $response = curl_exec($ch);
        $http_code = 0;
        $contentType = "";

        if ($response !== false) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $body = substr($response, $header_size);
            $data = json_decode($body, true);
            $this->userId = (int)$data['id'];
        }

        curl_close($ch);

        $this->assertEquals(200, $http_code);
        $this->assertEquals("application/json", $contentType);
    }

    public function testGetOne()
    {
        if (!$this->userId) {
            $this->assertTrue(false);
        }

        $ch = curl_init($this->url . '/api/v1/staffs/' . $this->userId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        $response = curl_exec($ch);
        $http_code = 0;
        $contentType = "";

        if ($response !== false) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        }

        curl_close($ch);

        $this->assertEquals(200, $http_code);
        $this->assertEquals("application/json", $contentType);
    }

    public function testUpdate()
    {
        if (!$this->userId) {
            $this->assertTrue(false);
        }

        $ch = curl_init($this->url . '/api/v1/staffs/' . $this->userId);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'name=test_' . $this->userId . '_' . time());
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $response = curl_exec($ch);
        $http_code = 0;
        $contentType = "";

        if ($response !== false) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        }

        curl_close($ch);

        $this->assertEquals(200, $http_code);
        $this->assertEquals("application/json", $contentType);
    }

    public function testDelete()
    {
        if (!$this->userId) {
            $this->assertTrue(false);
        }

        $ch = curl_init($this->url . '/api/v1/staffs/' . $this->userId);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        $response = curl_exec($ch);
        $http_code = 0;

        if ($response !== false) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }

        curl_close($ch);

        $this->assertEquals(204, $http_code);
    }
}
