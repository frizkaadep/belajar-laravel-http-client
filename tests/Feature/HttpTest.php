<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HttpTest extends TestCase
{
    public function testGet()
    {
        $response = Http::get("https://eovfqbhcsiqqjqn.m.pipedream.net");
        self::assertTrue($response->ok());;
    }

    public function testPost()
    {
        $response = Http::post("https://eovfqbhcsiqqjqn.m.pipedream.net");
        self::assertTrue($response->ok());;
    }

    public function testDelete()
    {
        $response = Http::delete("https://eovfqbhcsiqqjqn.m.pipedream.net");
        self::assertTrue($response->ok());;
    }

    public function testRsponse()
    {
        $response = Http::get("https://eovfqbhcsiqqjqn.m.pipedream.net");
        self::assertEquals(200, $response->status());
        self::assertNotNull($response->headers());
        self::assertNotNull($response->body());

        $json = $response->json();
        self::assertTrue(is_array($json));
    }

    public function testQueryParameters()
    {
        $response = Http::withQueryParameters([
            'page' => 1,
            'limit' => 10,
        ])->get("https://eovfqbhcsiqqjqn.m.pipedream.net");

        self::assertTrue($response->ok());
    }

    public function testHeader()
    {
        $response = Http::withQueryParameters([
            'page' => 1,
            'limit' => 10,
        ])->withHeaders([
            'accept' => 'application/json',
            'X-Request-Id' => '1234567890',
        ])->get("https://eovfqbhcsiqqjqn.m.pipedream.net");

        self::assertTrue($response->ok());
    }

    public function testCookies()
    {
        $response = Http::withQueryParameters([
            'page' => 1,
            'limit' => 10,
        ])->withHeaders([
            'accept' => 'application/json',
            'X-Request-Id' => '1234567890',
        ])->withCookies([
            'session_id' => '1234567890',
            'user_id' => 'ade',
        ], "eovfqbhcsiqqjqn.m.pipedream.net")
            ->get("https://eovfqbhcsiqqjqn.m.pipedream.net");

        self::assertTrue($response->ok());
    }

    public function testFormPost()
    {
        $response = Http::asForm()->post("https://eovfqbhcsiqqjqn.m.pipedream.net", [
            'username' => 'admin',
            'password' => 'password',
        ]);

        self::assertTrue($response->ok());
    }

    public function testMultipart()
    {
        $response = Http::asMultipart()
            ->attach('profile', file_get_contents(__DIR__ . '/../../resources/ad.png'), 'profile.jpg')
            ->post("https://eovfqbhcsiqqjqn.m.pipedream.net", [
            'username' => 'admin',
            'password' => 'password',
        ]);

        self::assertTrue($response->ok());
        // dd($response);
    }

    public function testJSON()
    {
        $response = Http::asJson()
            ->post("https://eovfqbhcsiqqjqn.m.pipedream.net", [
            'username' => 'admin',
            'password' => 'admin',
        ]);

        self::assertTrue($response->ok());
    }

    public function testTimeout()
    {
        $response = Http::timeout(15)->asJson() // default timeout is 30 seconds
            ->post("https://eovfqbhcsiqqjqn.m.pipedream.net", [
            'username' => 'admin',
            'password' => 'admin',
        ]);

        self::assertTrue($response->ok());
    }

    public function testRetry()
    {
        $response = Http::timeout(10)->retry(5, 1000)->asJson() // default timeout is 30 seconds
            ->post("https://eovfqbhcsiqqjqn.m.pipedream.net", [
            'username' => 'admin',
            'password' => 'admin',
        ]);

        self::assertTrue($response->ok());
    }

    public function testThrowError()
    {
        $this->assertThrows(function () {
            $response = Http::get("https://www.programmerzamannow.com/not-found");
            self::assertEquals(404, $response->status());
            $response->throw();
        }, RequestException::class);
    }
}
