<?php

// src/Repository/LoanRateRepository.php

namespace App\Repository;

use App\Model\LoanRate;
use App\Model\Partner;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LoanRateRepository
{
    private const CACHE_KEY = 'loan_rates_data';
    private const CACHE_TTL = 3600; // 1 heure

    public function __construct(
        private readonly ParameterBagInterface $params,
        private readonly CacheItemPoolInterface $cache,
    ) {
    }

    /**
     * Charge toutes les données depuis les fichiers JSON
     */
    private function loadData(): array
    {
        $cacheItem = $this->cache->getItem(self::CACHE_KEY);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $dataPath = $this->params->get('kernel.project_dir') . '/data/';

        $allRates = [];

        // Configuration des partenaires
        $partners = [
            'CARREFOUR' => [
                'name'    => 'Carrefour Bank',
                'file'    => 'CARREFOURBANK.json',
                'mapping' => ['montant_pret' => 'amount', 'duree_pret' => 'duration', 'taux_pret' => 'rate'],
            ],
            'SG' => [
                'name'    => 'Société Générale',
                'file'    => 'SG.json',
                'mapping' => ['amount' => 'amount', 'duration' => 'duration', 'rate' => 'rate'],
            ],
            'BNP' => [
                'name'    => 'BNP Paribas',
                'file'    => 'BNP.json',
                'mapping' => ['montant' => 'amount', 'duree' => 'duration', 'taux' => 'rate'],
            ],
        ];

        foreach ($partners as $code => $config) {
            $partner  = new Partner($code, $config['name'], $code);
            $filePath = $dataPath . $config['file'];

            if (file_exists($filePath)) {
                $json = file_get_contents($filePath);
                // Nettoyage du JSON mal formé :
                // 1. Supprimer les commentaires sur une ligne
                $json = preg_replace('!//.*!', '', $json);
                // 2. Supprimer les commentaires multi-lignes
                $json = preg_replace('!/\*.*?\*/!s', '', $json);
                // 3. Supprimer les virgules en trop avant ] ou }
                $json = preg_replace('/,\s*([\]}])/', '$1', $json);
                // 4. Trim
                $json     = trim($json);
                $jsonData = json_decode($json, true);

                // dd($jsonData);

                foreach ($jsonData as $index => $item) {
                    $rateId = $code . '_' . $index;

                    // Mapping des champs selon le format du fichier
                    $amount   = $item[array_search('amount', $config['mapping'])] ?? 0;
                    $duration = $item[array_search('duration', $config['mapping'])] ?? 0;
                    $rate     = $item[array_search('rate', $config['mapping'])] ?? 0.0;

                    $loanRate = new LoanRate(
                        $rateId,
                        (int) $amount,
                        (int) $duration,
                        (float) $rate,
                        $partner
                    );

                    $allRates[] = $loanRate;
                }
            }
        }

        // Mise en cache
        $cacheItem->set($allRates);
        $cacheItem->expiresAfter(self::CACHE_TTL);
        $this->cache->save($cacheItem);

        return $allRates;
    }

    /**
     * Trouve les taux par montant et durée
     */
    public function findByAmountAndDuration(int $amount, int $duration): array
    {
        $allRates = $this->loadData();

        $filtered = array_filter($allRates, function (LoanRate $rate) use ($amount, $duration) {
            return $rate->getAmount() === $amount && $rate->getDuration() === $duration;
        });

        // Tri par taux croissant
        usort($filtered, function (LoanRate $a, LoanRate $b) {
            return $a->getRate() <=> $b->getRate();
        });

        return array_values($filtered);
    }

    /**
     * Trouve tous les taux avec pagination
     */
    public function findAll(int $page = 1, int $itemsPerPage = 10, ?int $amount = null, ?int $duration = null): array
    {
        $allRates = $this->loadData();

        // Filtrage optionnel
        if (null !== $amount || null !== $duration) {
            $allRates = array_filter($allRates, function (LoanRate $rate) use ($amount, $duration) {
                $matchAmount   = null === $amount || $rate->getAmount() === $amount;
                $matchDuration = null === $duration || $rate->getDuration() === $duration;

                return $matchAmount && $matchDuration;
            });
        }

        // Tri par taux
        usort($allRates, fn ($a, $b) => $a->getRate() <=> $b->getRate());

        // Pagination
        $total  = count($allRates);
        $offset = ($page - 1) * $itemsPerPage;
        $items  = array_slice($allRates, $offset, $itemsPerPage);

        return [
            'items'        => $items,
            'total'        => $total,
            'page'         => $page,
            'itemsPerPage' => $itemsPerPage,
            'totalPages'   => ceil($total / $itemsPerPage),
        ];
    }

    /**
     * Invalide le cache (utile pour les tests ou mise à jour des données)
     */
    public function clearCache(): void
    {
        $this->cache->deleteItem(self::CACHE_KEY);
    }
}
