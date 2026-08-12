<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

class OfferModel extends BaseModel
{
    public function allActive(): array
    {
        return $this->db->query(
            "SELECT o.*, u.email AS advertiser_email,
                    COUNT(s.id) AS subscribers
             FROM offers o
             JOIN users u ON u.id = o.advertiser_id
             LEFT JOIN subscriptions s ON s.offer_id = o.id
             WHERE o.status = 'active'
             GROUP BY o.id, u.email
             ORDER BY o.created_at DESC"
        )->fetchAll();
    }

    public function byAdvertiser(int $advertiserId): array
    {
        $stmt = $this->db->prepare(
            "SELECT o.*,
                    COUNT(s.id) AS subscribers
             FROM offers o
             LEFT JOIN subscriptions s ON s.offer_id = o.id
             WHERE o.advertiser_id = :aid
             GROUP BY o.id
             ORDER BY o.created_at DESC"
        );
        $stmt->execute(['aid' => $advertiserId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM offers WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(
        int $advertiserId,
        string $name,
        float $costPerClick,
        string $targetUrl,
        string $topics
    ): int {
        $stmt = $this->db->prepare(
            "INSERT INTO offers (advertiser_id, name, cost_per_click, target_url, topics)
             VALUES (:aid, :name, :cpc, :url, :topics)
             RETURNING id"
        );
        $stmt->execute([
            'aid'    => $advertiserId,
            'name'   => $name,
            'cpc'    => $costPerClick,
            'url'    => $targetUrl,
            'topics' => $topics,
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function setStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE offers SET status = :s WHERE id = :id");
        $stmt->execute(['s' => $status, 'id' => $id]);
    }

    public function all(): array
    {
        return $this->db->query(
            "SELECT o.*, u.email AS advertiser_email,
                    COUNT(s.id) AS subscribers
             FROM offers o
             JOIN users u ON u.id = o.advertiser_id
             LEFT JOIN subscriptions s ON s.offer_id = o.id
             GROUP BY o.id, u.email
             ORDER BY o.created_at DESC"
        )->fetchAll();
    }
}
