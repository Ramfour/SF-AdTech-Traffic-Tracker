<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

class SubscriptionModel extends BaseModel
{
    public function findByWebmasterAndOffer(int $webmasterId, int $offerId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM subscriptions WHERE webmaster_id = :wid AND offer_id = :oid LIMIT 1'
        );
        $stmt->execute(['wid' => $webmasterId, 'oid' => $offerId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function byWebmaster(int $webmasterId): array
    {
        $stmt = $this->db->prepare(
            "SELECT s.*, o.name AS offer_name, o.cost_per_click, o.target_url,
                    o.topics, o.status AS offer_status
             FROM subscriptions s
             JOIN offers o ON o.id = s.offer_id
             WHERE s.webmaster_id = :wid
             ORDER BY s.created_at DESC"
        );
        $stmt->execute(['wid' => $webmasterId]);
        return $stmt->fetchAll();
    }

    public function create(int $webmasterId, int $offerId, string $trackLink): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO subscriptions (webmaster_id, offer_id, track_link)
             VALUES (:wid, :oid, :link)
             RETURNING id"
        );
        $stmt->execute([
            'wid'  => $webmasterId,
            'oid'  => $offerId,
            'link' => $trackLink,
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function delete(int $webmasterId, int $offerId): void
    {
        $stmt = $this->db->prepare(
            'DELETE FROM subscriptions WHERE webmaster_id = :wid AND offer_id = :oid'
        );
        $stmt->execute(['wid' => $webmasterId, 'oid' => $offerId]);
    }

    public function findByTrackLink(string $token): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT s.*, o.target_url, o.cost_per_click, o.status AS offer_status,
                    o.advertiser_id
             FROM subscriptions s
             JOIN offers o ON o.id = s.offer_id
             WHERE s.track_link = :token LIMIT 1'
        );
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function all(): array
    {
        return $this->db->query(
            "SELECT s.*, u.email AS webmaster_email, o.name AS offer_name
             FROM subscriptions s
             JOIN users u ON u.id = s.webmaster_id
             JOIN offers o ON o.id = s.offer_id
             ORDER BY s.created_at DESC"
        )->fetchAll();
    }
}
