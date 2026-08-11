-- SF-AdTech Traffic Tracker — схема базы данных PostgreSQL
-- Версия: 1.0

-- Расширение для UUID (используем SERIAL вместо UUID для простоты)

-- Пользователи (рекламодатели, веб-мастера, администраторы)
CREATE TABLE IF NOT EXISTS users (
    id            SERIAL PRIMARY KEY,
    email         VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          VARCHAR(20)  NOT NULL CHECK (role IN ('advertiser', 'webmaster', 'admin')),
    is_active     BOOLEAN      NOT NULL DEFAULT TRUE,
    created_at    TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_users_email ON users (email);
CREATE INDEX IF NOT EXISTS idx_users_role  ON users (role);

-- Офферы рекламодателей
CREATE TABLE IF NOT EXISTS offers (
    id              SERIAL PRIMARY KEY,
    advertiser_id   INT          NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    name            VARCHAR(255) NOT NULL,
    cost_per_click  NUMERIC(10,4) NOT NULL CHECK (cost_per_click > 0),
    target_url      TEXT         NOT NULL,
    topics          VARCHAR(500) NOT NULL DEFAULT '',
    status          VARCHAR(20)  NOT NULL DEFAULT 'active'
                    CHECK (status IN ('active', 'inactive')),
    created_at      TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_offers_advertiser ON offers (advertiser_id);
CREATE INDEX IF NOT EXISTS idx_offers_status     ON offers (status);

-- Подписки веб-мастеров на офферы
CREATE TABLE IF NOT EXISTS subscriptions (
    id            SERIAL PRIMARY KEY,
    webmaster_id  INT         NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    offer_id      INT         NOT NULL REFERENCES offers (id) ON DELETE CASCADE,
    track_link    VARCHAR(64) NOT NULL UNIQUE,  -- токен редиректа
    created_at    TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE (webmaster_id, offer_id)
);

CREATE INDEX IF NOT EXISTS idx_sub_webmaster  ON subscriptions (webmaster_id);
CREATE INDEX IF NOT EXISTS idx_sub_offer      ON subscriptions (offer_id);
CREATE INDEX IF NOT EXISTS idx_sub_track_link ON subscriptions (track_link);

-- Клики / переходы
CREATE TABLE IF NOT EXISTS clicks (
    id               SERIAL PRIMARY KEY,
    subscription_id  INT           NOT NULL REFERENCES subscriptions (id) ON DELETE CASCADE,
    offer_id         INT           NOT NULL REFERENCES offers (id) ON DELETE CASCADE,
    webmaster_id     INT           NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    advertiser_id    INT           NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    cost_per_click   NUMERIC(10,4) NOT NULL,
    commission       NUMERIC(5,4)  NOT NULL,   -- доля системы, например 0.2000
    ip               VARCHAR(45)   NOT NULL DEFAULT '',
    user_agent       VARCHAR(255)  NOT NULL DEFAULT '',
    refused          BOOLEAN       NOT NULL DEFAULT FALSE,  -- отказ (не подписан / неактивен)
    clicked_at       TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_clicks_offer       ON clicks (offer_id);
CREATE INDEX IF NOT EXISTS idx_clicks_webmaster   ON clicks (webmaster_id);
CREATE INDEX IF NOT EXISTS idx_clicks_advertiser  ON clicks (advertiser_id);
CREATE INDEX IF NOT EXISTS idx_clicks_clicked_at  ON clicks (clicked_at);
CREATE INDEX IF NOT EXISTS idx_clicks_refused     ON clicks (refused);

-- Начальный администратор (пароль: admin123 — сменить при деплое)
-- Хэш BCrypt для 'admin123'
INSERT INTO users (email, password_hash, role)
VALUES (
    'admin@sfadtech.local',
    '$2y$12$Q7L3Xn5vKpMz8hGwUq9O4eF2YDkR1dS0TjBxNvCpLrWyAEsIgOuHK',
    'admin'
) ON CONFLICT (email) DO NOTHING;
