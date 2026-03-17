-- =============================
-- init.sql - 建立資料庫與資料表，並插入測試資料
-- =============================

CREATE DATABASE IF NOT EXISTS school_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_db;

-- 班級資料表
CREATE TABLE IF NOT EXISTS class (
    班級編號 VARCHAR(10)  NOT NULL PRIMARY KEY,
    班級名稱 VARCHAR(50)  NOT NULL,
    班級導師 VARCHAR(20)  NOT NULL
);

-- 同學資料表
CREATE TABLE IF NOT EXISTS Classmates (
    同學編號 VARCHAR(10)  NOT NULL PRIMARY KEY,
    班級編號 VARCHAR(10)  NOT NULL,
    同學姓名 VARCHAR(20)  NOT NULL,
    英文成績 TINYINT UNSIGNED NOT NULL DEFAULT 0,
    數學成績 TINYINT UNSIGNED NOT NULL DEFAULT 0,
    國文成績 TINYINT UNSIGNED NOT NULL DEFAULT 0,
    FOREIGN KEY (班級編號) REFERENCES class(班級編號)
);

-- ── 測試資料 ──────────────────────────────────────────────

INSERT INTO class (班級編號, 班級名稱, 班級導師) VALUES
('C001', '一年一班', '王大明'),
('C002', '一年二班', '李小華'),
('C003', '二年一班', '張美玲'),
('C004', '二年二班', '陳志遠');

INSERT INTO Classmates (同學編號, 班級編號, 同學姓名, 英文成績, 數學成績, 國文成績) VALUES
('S001', 'C001', '林小明', 85, 92, 78),
('S002', 'C001', '陳雅婷', 90, 88, 95),
('S003', 'C001', '黃俊豪', 72, 65, 80),
('S004', 'C002', '吳佳穎', 95, 97, 91),
('S005', 'C002', '劉建宏', 60, 75, 68),
('S006', 'C002', '蔡淑芬', 88, 83, 90),
('S007', 'C003', '鄭文凱', 77, 82, 74),
('S008', 'C003', '許雨萱', 93, 89, 96),
('S009', 'C004', '洪志偉', 69, 71, 65),
('S010', 'C004', '游曉晴', 84, 90, 88);
