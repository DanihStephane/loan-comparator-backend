<?php
// tests/Functional/Controller/LoanComparisonControllerTest.php
namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoanComparisonControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Copier les fichiers JSON de test
        $testDataPath = __DIR__ . '/../../fixtures/data/';
        $projectDataPath = self::getContainer()->getParameter('kernel.project_dir') . '/data/';

        if (!is_dir($projectDataPath)) {
            mkdir($projectDataPath, 0777, true);
        }

        foreach (['CARREFOURBANK.json', 'SG.json', 'BNP.json'] as $file) {
            if (file_exists($testDataPath . $file)) {
                copy($testDataPath . $file, $projectDataPath . $file);
            }
        }
    }

    public function testCompareWithValidData(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/loans/compare', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'amount' => 100000,
            'duration' => 20,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '0612345678'
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('results', $responseData);
        $this->assertNotEmpty($responseData['results']);
    }

    public function testCompareWithInvalidEmail(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/loans/compare', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'amount' => 100000,
            'duration' => 20,
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'phone' => '0612345678'
        ]));

        $this->assertResponseStatusCodeSame(400);

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('email', $responseData['errors']);
    }

    public function testListWithPagination(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/loan_rates?page=1&itemsPerPage=5');

        $this->assertResponseIsSuccessful();

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('hydra:member', $responseData);
        $this->assertArrayHasKey('hydra:totalItems', $responseData);
        $this->assertArrayHasKey('hydra:view', $responseData);
        $this->assertCount(5, $responseData['hydra:member']);
    }
}