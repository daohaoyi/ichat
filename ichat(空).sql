-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- 主機: 127.0.0.1
-- 產生時間： 
-- 伺服器版本: 10.1.37-MariaDB
-- PHP 版本： 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `ichat`
--

-- --------------------------------------------------------

--
-- 資料表結構 `chats`
--

CREATE TABLE `chats` (
  `chatId` int(11) NOT NULL COMMENT '編號',
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '創建者編號',
  `sort` varchar(2) COLLATE utf8mb4_bin NOT NULL DEFAULT '0' COMMENT '聊天分類',
  `chatName` varchar(64) COLLATE utf8mb4_bin NOT NULL DEFAULT '0' COMMENT '聊天室名稱',
  `chatTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '創建時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `chats_message`
--

CREATE TABLE `chats_message` (
  `chmeId` int(11) NOT NULL COMMENT '編號',
  `chatId` int(11) NOT NULL DEFAULT '0' COMMENT '聊天室編號',
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '發言者編號',
  `chatMessage` text COLLATE utf8mb4_bin NOT NULL COMMENT '聊天訊息',
  `chmeTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '訊息發送時間',
  `view` int(11) NOT NULL DEFAULT '0' COMMENT '0正常1刪除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `friends`
--

CREATE TABLE `friends` (
  `friendId` int(11) NOT NULL COMMENT '編號',
  `inviter` int(11) NOT NULL DEFAULT '0' COMMENT '邀請者編號',
  `invitee` int(11) NOT NULL DEFAULT '0' COMMENT '被邀請者編號',
  `friendReview` int(1) NOT NULL DEFAULT '0' COMMENT '審核中0 審核通過1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `friends_message`
--

CREATE TABLE `friends_message` (
  `frmeId` int(11) NOT NULL COMMENT '編號',
  `friendId` int(11) NOT NULL DEFAULT '0' COMMENT '好友編號',
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '發言者編號',
  `friendMessage` text COLLATE utf8mb4_bin NOT NULL COMMENT '好友訊息',
  `friendReady` int(1) NOT NULL DEFAULT '0',
  `friendTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '發送時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `groups`
--

CREATE TABLE `groups` (
  `groupId` int(11) NOT NULL COMMENT '編號',
  `groupName` varchar(50) COLLATE utf8mb4_bin NOT NULL DEFAULT '0' COMMENT '名稱',
  `imgUrl` varchar(255) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `groups_member`
--

CREATE TABLE `groups_member` (
  `groupId` int(11) NOT NULL DEFAULT '0',
  `userId` int(11) NOT NULL DEFAULT '0',
  `groupReview` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `groups_message`
--

CREATE TABLE `groups_message` (
  `grmeId` int(11) NOT NULL COMMENT '群組訊息編號',
  `groupId` int(11) NOT NULL DEFAULT '0' COMMENT '群組編號',
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '發言者編號',
  `groupMessage` text COLLATE utf8mb4_bin NOT NULL COMMENT '群組訊息',
  `groupTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '發送時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `groups_read`
--

CREATE TABLE `groups_read` (
  `groupId` int(11) DEFAULT NULL,
  `grmeId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `seen` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `manager_ban`
--

CREATE TABLE `manager_ban` (
  `userId` int(11) NOT NULL,
  `banTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `manager_message`
--

CREATE TABLE `manager_message` (
  `chmeId` int(11) DEFAULT NULL,
  `reason` int(11) DEFAULT NULL,
  `verify` int(11) DEFAULT '0' COMMENT '0未確認1確認'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL COMMENT '編號',
  `account` varchar(12) COLLATE utf8mb4_bin NOT NULL DEFAULT '0' COMMENT '帳號',
  `password` varchar(256) COLLATE utf8mb4_bin NOT NULL DEFAULT '0' COMMENT '密碼',
  `userName` varchar(12) COLLATE utf8mb4_bin NOT NULL DEFAULT '0' COMMENT '名稱',
  `email` varchar(36) COLLATE utf8mb4_bin NOT NULL DEFAULT '0' COMMENT '信箱',
  `gender` varchar(50) COLLATE utf8mb4_bin NOT NULL DEFAULT '0' COMMENT '性別',
  `staus` int(11) NOT NULL DEFAULT '0' COMMENT '狀態 上(1)/下(0)線',
  `permission` int(11) NOT NULL DEFAULT '0' COMMENT '權限:0會員 1管理 2處罰的會員',
  `imgUrl` varchar(256) COLLATE utf8mb4_bin NOT NULL COMMENT '圖檔路徑'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chatId`),
  ADD KEY `FK__users` (`userId`);

--
-- 資料表索引 `chats_message`
--
ALTER TABLE `chats_message`
  ADD PRIMARY KEY (`chmeId`),
  ADD KEY `FK_chats_message_users` (`userId`),
  ADD KEY `FK_chats_message_chats` (`chatId`);

--
-- 資料表索引 `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`friendId`),
  ADD KEY `FK_friends_users` (`inviter`),
  ADD KEY `FK_friends_users_2` (`invitee`);

--
-- 資料表索引 `friends_message`
--
ALTER TABLE `friends_message`
  ADD PRIMARY KEY (`frmeId`),
  ADD KEY `friendId` (`friendId`),
  ADD KEY `userId` (`userId`);

--
-- 資料表索引 `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`groupId`);

--
-- 資料表索引 `groups_member`
--
ALTER TABLE `groups_member`
  ADD KEY `FK_groups_member_groups` (`groupId`),
  ADD KEY `FK_groups_member_users` (`userId`);

--
-- 資料表索引 `groups_message`
--
ALTER TABLE `groups_message`
  ADD PRIMARY KEY (`grmeId`),
  ADD KEY `FK__groups` (`groupId`),
  ADD KEY `FK_groups_message_users` (`userId`);

--
-- 資料表索引 `groups_read`
--
ALTER TABLE `groups_read`
  ADD KEY `FK_groups_read_groups_member_2` (`userId`),
  ADD KEY `FK_groups_read_groups_message` (`grmeId`),
  ADD KEY `groupId` (`groupId`);

--
-- 資料表索引 `manager_ban`
--
ALTER TABLE `manager_ban`
  ADD PRIMARY KEY (`userId`);

--
-- 資料表索引 `manager_message`
--
ALTER TABLE `manager_message`
  ADD KEY `FK_manager_message_chats_message` (`chmeId`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `chats`
--
ALTER TABLE `chats`
  MODIFY `chatId` int(11) NOT NULL AUTO_INCREMENT COMMENT '編號';

--
-- 使用資料表 AUTO_INCREMENT `chats_message`
--
ALTER TABLE `chats_message`
  MODIFY `chmeId` int(11) NOT NULL AUTO_INCREMENT COMMENT '編號';

--
-- 使用資料表 AUTO_INCREMENT `friends`
--
ALTER TABLE `friends`
  MODIFY `friendId` int(11) NOT NULL AUTO_INCREMENT COMMENT '編號';

--
-- 使用資料表 AUTO_INCREMENT `friends_message`
--
ALTER TABLE `friends_message`
  MODIFY `frmeId` int(11) NOT NULL AUTO_INCREMENT COMMENT '編號';

--
-- 使用資料表 AUTO_INCREMENT `groups`
--
ALTER TABLE `groups`
  MODIFY `groupId` int(11) NOT NULL AUTO_INCREMENT COMMENT '編號';

--
-- 使用資料表 AUTO_INCREMENT `groups_message`
--
ALTER TABLE `groups_message`
  MODIFY `grmeId` int(11) NOT NULL AUTO_INCREMENT COMMENT '群組訊息編號';

--
-- 使用資料表 AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT COMMENT '編號';

--
-- 已匯出資料表的限制(Constraint)
--

--
-- 資料表的 Constraints `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `FK__users` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `chats_message`
--
ALTER TABLE `chats_message`
  ADD CONSTRAINT `FK_chats_message_chats` FOREIGN KEY (`chatId`) REFERENCES `chats` (`chatId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_chats_message_users` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `FK_friends_users` FOREIGN KEY (`inviter`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_friends_users_2` FOREIGN KEY (`invitee`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `friends_message`
--
ALTER TABLE `friends_message`
  ADD CONSTRAINT `FK_friends_message_friends` FOREIGN KEY (`friendId`) REFERENCES `friends` (`friendId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_friends_message_users` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `groups_member`
--
ALTER TABLE `groups_member`
  ADD CONSTRAINT `FK_groups_member_groups` FOREIGN KEY (`groupId`) REFERENCES `groups` (`groupId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_groups_member_users` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `manager_ban`
--
ALTER TABLE `manager_ban`
  ADD CONSTRAINT `FK_manager_ban_users` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- 資料表的 Constraints `manager_message`
--
ALTER TABLE `manager_message`
  ADD CONSTRAINT `FK_manager_message_chats_message` FOREIGN KEY (`chmeId`) REFERENCES `chats_message` (`chmeId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
