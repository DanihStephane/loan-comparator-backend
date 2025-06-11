<?php
// tests/Unit/Repository/LoanRateRepositoryTest.php
namespace App\Tests\Unit\Repository;

use App\Repository\LoanRateRepository;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class LoanRateRepositoryTest extends TestCase
{
    private LoanRateRepository $repository;
    private $cacheMock;

    protected function setUp(): void
    {
        $parameterBag = new ParameterBag([
            'kernel.project_dir' => __DIR__ . '/../../fixtures'
        ]);

        $this->cacheMock = $this->createMock(CacheItemPoolInterface::class);
        $this->repository = new LoanRateRepository($parameterBag, $this->cacheMock);
    }

    public function testFindByAmountAndDuration(): void
    {
        // Simuler que le cache est vide
        $cacheItem = $this->createMock(\Psr\Cache\CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);
        $this->cacheMock->method('getItem')->willReturn($cacheItem);

        $rates = $this->repository->findByAmountAndDuration(100000, 20);

        $this->assertIsArray($rates);
        // Vérifier que les résultats sont triés par taux croissant
        if (count($rates) > 1) {
            for ($i = 1; $i < count($rates); $i++) {
                $this->assertGreaterThanOrEqual(
                    $rates[$i - 1]->getRate(),
                    $rates[$i]->getRate()
                );
            }
        }
    }
}