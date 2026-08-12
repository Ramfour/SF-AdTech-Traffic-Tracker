<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

class ClickModel extends BaseModel
{
    public function record(
        int $subscriptionId,
        int $offerId,
        int $webmasterId,
        int $advertiserId,
        float $costPerClick,
        float $commission,
        string $ip,
        string $userAgent,
        bool $refused = false
    ): void {
        $stmt = $this->db->prepare(
            "INSERT INTO clicks
                (subscription_id, offer_id, webmaster_id, advertiser_id,
                 cost_per_click, commission, ip, user_agent, refused)
             VALUES
                (:sid, :oid, :wid, :aid, :cpc, :comm, :ip, :ua, :refused)"
        );
        $stmt->execute([
            'sid'     => $subscriptionId,
            'oid'     => $offerId,
            'wid'     => $webmasterId,
            'aid'     => $advertiserId,
            'cpc'     => $costPerClick,
            'comm'    => $commission,
            'ip'      => $ip,
            'ua'      => $userAgent,
            'refused' => $refused ? 'true' : 'false',
        ]);
    }

    // Статистика рекламодателя
    public function statsByAdvertiser(int $advertiserId, string $period): array
    {
        $trunc = $this->periodTrunc($period);
        $stmt = $this->db->prepare(
            "SELECT DATE_TRUNC(:trunc, clicked_at)::date AS period,
                    COUNT(*) AS clicks,
                    SUM(cost_per_click) AS total_cost
             FROM clicks
             WHERE advertiser_id = :aid AND refused = false
             GROUP BY 1
             ORDER BY 1 DESC
             LIMIT 90"
        );
        $stmt->execute(['trunc' => $trunc, 'aid' => $advertiserId]);
        return $stmt->fetchAll();
    }

    public function statsByAdvertiserAndOffer(int $advertiserId, int $offerId, string $period): array
    {
        $trunc = $this->periodTrunc($period);
        $stmt = $this->db->prepare(
            "SELECT DATE_TRUNC(:trunc, clicked_at)::date AS period,
                    COUNT(*) AS clicks,
                    SUM(cost_per_click) AS total_cost
             FROM clicks
             WHERE advertiser_id = :aid AND offer_id = :oid AND refused = false
             GROUP BY 1
             ORDER BY 1 DESC
             LIMIT 90"
        );
        $stmt->execute(['trunc' => $trunc, 'aid' => $advertiserId, 'oid' => $offerId]);
        return $stmt->fetchAll();
    }

    // Статистика веб-мастера
    public function statsByWebmaster(int $webmasterId, string $period): array
    {
        $trunc = $this->periodTrunc($period);
        $stmt = $this->db->prepare(
            "SELECT DATE_TRUNC(:trunc, clicked_at)::date AS period,
                    COUNT(*) AS clicks,
                    SUM(cost_per_click * (1 - commission)) AS earnings
             FROM clicks
             WHERE webmaster_id = :wid AND refused = false
             GROUP BY 1
             ORDER BY 1 DESC
             LIMIT 90"
        );
        $stmt->execute(['trunc' => $trunc, 'wid' => $webmasterId]);
        return $stmt->fetchAll();
    }

    public function statsByWebmasterAndOffer(int $webmasterId, int $offerId, string $period): array
    {
        $trunc = $this->periodTrunc($period);
        $stmt = $this->db->prepare(
            "SELECT DATE_TRUNC(:trunc, clicked_at)::date AS period,
                    COUNT(*) AS clicks,
                    SUM(cost_per_click * (1 - commission)) AS earnings
             FROM clicks
             WHERE webmaster_id = :wid AND offer_id = :oid AND refused = false
             GROUP BY 1
             ORDER BY 1 DESC
             LIMIT 90"
        );
        $stmt->execute(['trunc' => $trunc, 'wid' => $webmasterId, 'oid' => $offerId]);
        return $stmt->fetchAll();
    }

    // Статистика администратора
    public function systemStats(): array
    {
        return $this->db->query(
            "SELECT
                COUNT(*) AS total_clicks,
                COUNT(*) FILTER (WHERE refused = false) AS valid_clicks,
                COUNT(*) FILTER (WHERE refused = true)  AS refused_clicks,
                COALESCE(SUM(cost_per_click) FILTER (WHERE refused = false), 0) AS total_revenue,
                COALESCE(SUM(cost_per_click * commission) FILTER (WHERE refused = false), 0) AS system_income
             FROM clicks"
        )->fetch();
    }

    public function allClicks(int $limit = 200): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, o.name AS offer_name,
                    uw.email AS webmaster_email,
                    ua.email AS advertiser_email
             FROM clicks c
             JOIN offers o ON o.id = c.offer_id
             JOIN users uw ON uw.id = c.webmaster_id
             JOIN users ua ON ua.id = c.advertiser_id
             ORDER BY c.clicked_at DESC
             LIMIT :lim"
        );
        $stmt->execute(['lim' => $limit]);
        return $stmt->fetchAll();
    }

    private function periodTrunc(string $period): string
    {
        return match($period) {
            'month' => 'month',
            'year'  => 'year',
            default => 'day',
        };
    }
}
