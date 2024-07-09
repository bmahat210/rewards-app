<?php

namespace App\Tests\Controller\Api;

use App\Entity\Reward;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RewardApiControllerTest extends WebTestCase
{
    private $entityManager;
    private $client;
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get(EntityManagerInterface::class);
    }

    public function testIndexReturnsAllRewards(): void
    {

        $this->entityManager->persist(Reward::build('Test Reward 1', 'This is a test reward'));
        $this->entityManager->persist(Reward::build('Test Reward 2', 'This is another test reward'));
        $this->entityManager->flush();

        $this->client->request('GET', '/api/rewards');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($this->client->getResponse()->getContent()); // Assert response is JSON

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(2, $data); // Assert number of rewards
        printf(json_encode($data));
        $this->assertEquals('Test Reward 1', $data[0]['name']); // Assert specific value
    }

    public function testIndexReturnsEmptyList(): void
    {
        $this->client->request('GET', '/api/rewards');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($this->client->getResponse()->getContent()); // Assert response is JSON

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEmpty($data); // Assert empty array
    }

    public function testCreateRewardSuccess(): void
    {
        $this->client->request(
            'POST',
            '/api/rewards',
            [],
            [],
            [],
            json_encode([
                'name' => 'Post New Reward',
                'description' => 'This is a post new reward',
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertJson($this->client->getResponse()->getContent()); // Assert response is JSON

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('Post New Reward', $data['name']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->getConnection()->executeStatement('DELETE FROM reward');
    }
}
