<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function createUser()
    {
        $this->user = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
        ];

        return $this->json('POST', 'api/register', $this->user)
            ->assertStatus(Response::HTTP_CREATED);
    }

    public function testRequiredRegistrationFields()
    {
        $this->json('POST', 'api/register')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }

    public function testUserRegisteration()
    {
        $this->createUser()->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function testRequiredLoginCredentials()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.']
                ]
            ]);
    }

    public function testUserLogin()
    {
        $this->json('POST', 'api/login', $this->user)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testUserLogout()
    {
        $headers = $this->getLoggedInUserHeaders();

        $this->json('POST', "api/logout", [], $headers)
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testUserProfile()
    {
        $headers = $this->getLoggedInUserHeaders();

        $this->json('GET', 'api/profile', [], $headers)
            ->assertStatus(Response::HTTP_OK);
    }

    public function testExchangeRate()
    {
        $headers = $this->getLoggedInUserHeaders();

        $this->json('GET', 'api/exchange-rate', [], $headers)
            ->assertStatus(Response::HTTP_OK);
    }

    public function getLoggedInUserHeaders()
    {
        $loggedInUser = $this->json('POST', 'api/login', $this->user)
            ->assertStatus(Response::HTTP_OK);

        $userDataArray = $loggedInUser->decodeResponseJson();
        $token = $userDataArray['token'];

        $headers = ['Authorization' => "Bearer {$token}"];
        return $headers;
    }
}
