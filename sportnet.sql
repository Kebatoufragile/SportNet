SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `activations` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `activations` (`id`, `user_id`, `code`, `completed`, `completed_at`, `created_at`, `updated_at`) VALUES
(9, 3, 'MbSbCIGty2xfsLnOhUnZPn8P0yLVjEoO', 1, '2016-11-18 13:39:02', '2016-11-18 13:39:02', '2016-11-18 13:39:02'),
(10, 4, 'dCBqpcgj95vBeLtcqtaop6heFpVuFXs7', 1, '2016-11-18 13:39:20', '2016-11-18 13:39:20', '2016-11-18 13:39:20'),
(11, 5, 'yQZqKQNRjDT51GFX6FENLqRDtKrj55Vr', 1, '2016-11-18 13:40:16', '2016-11-18 13:40:16', '2016-11-18 13:40:16');

CREATE TABLE `comment` (
  `idComment` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `idEvent` int(11) NOT NULL,
  `idParticipant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `event` (
  `idEvent` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(511) NOT NULL,
  `location` varchar(100) NOT NULL,
  `discipline` varchar(100) NOT NULL,
  `dates` varchar(100) NOT NULL,
  `state` varchar(20) NOT NULL,
  `idOrg` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `event` (`idEvent`, `name`, `description`, `location`, `discipline`, `dates`, `state`, `idOrg`) VALUES
(4, 'Apéro chez Thibaut', 'Petit apéro chez Thibaut pour décompresser après les cours, venez nombreux !  ', 'Chez Thibaut', 'Apéro', 'Toute l&#39;année', 'open', 3),
(5, 'Marre à Thon', 'Petit marathon à Thon mais n&#39;en ayez pas marre.', 'Thon', 'Marre', '15-12-2016', 'open', 4),
(6, 'Tour de Corse', 'Venez voir de beaux paysages.', 'Ajaccio', 'Vélo', '15 août 2018', 'open', 5);

CREATE TABLE `inscription` (
  `idParticipant` int(11) NOT NULL,
  `idTrial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `inscription` (`idParticipant`, `idTrial`) VALUES
(8, 4),
(9, 5),
(10, 4),
(11, 4);

CREATE TABLE `participant` (
  `idParticipant` int(11) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `age` int(3) NOT NULL,
  `bib` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `participant` (`idParticipant`, `lastname`, `firstname`, `mail`, `age`, `bib`) VALUES
(8, 'COLLIN', 'Thibaut', 'thibaut.collin@sportnet.com', 20, 1),
(9, 'COLLIN', 'Thibaut', 'thibaut.collin@sportnet.com', 20, 1),
(10, 'Au revoir', 'Bonjour', 'bonjour@aurevoir.net', 15, 2),
(11, 'GIOVANELLI', 'Alexis', 'alexis.giovanelli@sportnet.com', 12, 3);

CREATE TABLE `persistences` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `persistences` (`id`, `user_id`, `code`, `created_at`, `updated_at`) VALUES
(22, 3, '5jMCr4T0hKrIpmEoJVavkz2SshxzdWVh', '2016-11-18 13:40:34', '2016-11-18 13:40:34'),
(24, 4, '8I8mJWdHznlisk9uknWt4ByWGIXtpv1w', '2016-11-18 13:45:30', '2016-11-18 13:45:30'),
(26, 5, 'vItehuAq4nRR3eO6OFhdk2i4PihajAd0', '2016-11-18 13:52:26', '2016-11-18 13:52:26'),
(28, 3, 'E086BrXCvQxXPDgRr56fB9HmFX393S7L', '2016-11-18 13:53:18', '2016-11-18 13:53:18'),
(30, 4, 'zJgpCy1FTaLJAGiq8CjWINWaA72ZBx1V', '2016-11-18 13:53:34', '2016-11-18 13:53:34'),
(32, 5, 'F6T6EXOjKbk90ANHA7HMm7r2JR8lJmLu', '2016-11-18 13:54:57', '2016-11-18 13:54:57'),
(33, 5, 'EnYzdX101tdy1TzT66KNx51HK9O2EHNS', '2016-11-18 13:54:57', '2016-11-18 13:54:57');

CREATE TABLE `picture` (
  `idPicture` int(11) NOT NULL,
  `description` varchar(511) NOT NULL,
  `path` varchar(100) NOT NULL,
  `idEvent` int(11) NOT NULL,
  `idParticipant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `reminders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `result` (
  `idResult` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `idTrial` int(11) NOT NULL,
  `idParticipant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `role_users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `throttle` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `throttle` (`id`, `user_id`, `type`, `ip`, `created_at`, `updated_at`) VALUES
(1, NULL, 'global', NULL, '2016-11-18 13:40:22', '2016-11-18 13:40:22'),
(2, NULL, 'ip', '::1', '2016-11-18 13:40:23', '2016-11-18 13:40:23'),
(3, NULL, 'global', NULL, '2016-11-18 13:54:45', '2016-11-18 13:54:45'),
(4, NULL, 'ip', '::1', '2016-11-18 13:54:46', '2016-11-18 13:54:46');

CREATE TABLE `trial` (
  `idTrial` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(511) NOT NULL,
  `date` date NOT NULL,
  `price` float NOT NULL,
  `idEvent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `trial` (`idTrial`, `name`, `description`, `date`, `price`, `idEvent`) VALUES
(4, 'Début de soirée', 'On commence doucement !', '2016-11-30', 10, 4),
(5, 'Apéro dinatoire', 'Pour combler une petite faim !', '2016-11-30', 15, 4),
(6, 'Fin de soirée', 'Ceux encore debout, venez !', '2016-11-30', 50, 4),
(7, 'Collin-maillard', 'Venez comme vous êtes.', '2016-12-15', 5, 5),
(8, 'Construction de murs', 'Featuring Alexandre Peirera', '2016-12-15', 100, 5),
(9, 'Bastia - Ajaccio', '', '2018-08-16', 10, 6),
(10, 'Sartène - Bastia', '', '2018-08-31', 20, 6);

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `last_login` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `users` (`id`, `email`, `password`, `permissions`, `last_login`, `first_name`, `last_name`, `created_at`, `updated_at`, `username`) VALUES
(3, 'sebastien.dupuis@sportnet.com', '$2y$10$6R9iUZcEecBp1wbg7bJV4e1x3Uy8.tESzXMWAVkC0ZTy8MU5xo1OC', NULL, '2016-11-18 13:53:19', 'Sébastien', 'Dupuis', '2016-11-18 13:39:02', '2016-11-18 13:53:19', 'Kixot'),
(4, 'thibaut.collin@sportnet.com', '$2y$10$VOJtDPpJpD3og5/gLv9nfOtG3dqrqsB3F.9ylj8Ba65YxbA.Hl9Nm', NULL, '2016-11-18 13:53:35', 'Thibaut', 'COLLIN', '2016-11-18 13:39:20', '2016-11-18 13:53:35', 'Grenadator'),
(5, 'alexis.giovanelli@sportnet.com', '$2y$10$THqkv9tVa9Taf9.r13vKBOpCTaK4nEZcHwu4V1Oi4rTi/Vb.XdMxC', NULL, '2016-11-18 13:54:57', 'Alexis', 'GIOVANELLI', '2016-11-18 13:40:16', '2016-11-18 13:54:57', 'LeCorse');


ALTER TABLE `activations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `comment`
  ADD PRIMARY KEY (`idComment`);

ALTER TABLE `event`
  ADD PRIMARY KEY (`idEvent`);

ALTER TABLE `participant`
  ADD PRIMARY KEY (`idParticipant`),
  ADD KEY `index_participant` (`idParticipant`);

ALTER TABLE `persistences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `persistences_code_unique` (`code`);

ALTER TABLE `picture`
  ADD PRIMARY KEY (`idPicture`);

ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `result`
  ADD PRIMARY KEY (`idResult`);

ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

ALTER TABLE `role_users`
  ADD PRIMARY KEY (`user_id`,`role_id`);

ALTER TABLE `throttle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `throttle_user_id_index` (`user_id`);

ALTER TABLE `trial`
  ADD PRIMARY KEY (`idTrial`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);


ALTER TABLE `activations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
ALTER TABLE `comment`
  MODIFY `idComment` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `event`
  MODIFY `idEvent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `participant`
  MODIFY `idParticipant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
ALTER TABLE `persistences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
ALTER TABLE `picture`
  MODIFY `idPicture` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `reminders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `result`
  MODIFY `idResult` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `throttle`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `trial`
  MODIFY `idTrial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
