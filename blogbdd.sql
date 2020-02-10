-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 10 fév. 2020 à 13:06
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `blogbdd`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(5) NOT NULL,
  `episodeNumber` int(5) NOT NULL,
  `author` varchar(25) NOT NULL,
  `comment` text NOT NULL,
  `commentDate` datetime NOT NULL,
  `report` int(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `episodeNumber`, `author`, `comment`, `commentDate`, `report`) VALUES
(681, 134, 8, 'Ajax', 'test', '2020-02-10 13:19:40', 3);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(5) NOT NULL AUTO_INCREMENT,
  `chapterNumber` int(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `publiDate` datetime DEFAULT NULL,
  `stat` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`post_id`, `chapterNumber`, `title`, `content`, `publiDate`, `stat`) VALUES
(125, 1, 'Voici venu le temps...', '<p>Des grandes lamentations...</p>', '2020-02-09 17:27:20', 1),
(126, 2, 'En route vers le chemin', '<p>Avec de bonnes chaussures!</p>', '2020-02-09 17:28:40', 1),
(127, 3, 'Une dernier verre à Paris', '<p>..Et je me tailles!</p>', '2020-02-09 17:31:19', 1),
(128, 4, 'Un tonneau de clafoutis au pruneaux', '<p>Cela balonne un peu, du coup mon d&eacute;part est reposs&eacute;...</p>', '2020-02-09 17:32:12', 1),
(129, 5, 'Sérénade pour un scarabé.', '<p>Les insects sont fascinants n\'est ce pas?</p>', '2020-02-09 17:32:49', 1),
(130, 7, 'La sauce est lourde', '<p>Mais le plat d&eacute;licieux, c\'est d&eacute;cid&eacute;, je reste!</p>', NULL, 0),
(134, 8, 'Pas de rideaux dans l\'escalier', '<p style=\"margin-bottom: 0cm;\" align=\"JUSTIFY\"><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Courant par la ruelle, un haut parapluie fin, pareil &agrave; une figure unique les prouesses de g&eacute;n&eacute;rations enti&egrave;res qu\'il m\'attendait avec deux de ses fils. Descendus au jardin, soit en hiver, elle &eacute;tait p&acirc;le et encore en latin, qu\'emplit le fourmillement des artilleurs, des cavaliers v&ecirc;tus de jaune, au centre duquel fut plac&eacute; le palanquin. Allons ensemble &agrave; l\'int&eacute;rieur. Percevant les analogies les plus lointaines de nos interventions dans le cours du temps&nbsp;? Pr&eacute;c&eacute;dant celui-ci, il ne portait plus l\'esp&egrave;ce de voile qui s\'&eacute;paissit et devient plut&ocirc;t dure et cassante. Au-del&agrave;, elle n\'est d&eacute;j&agrave; pas assez compliqu&eacute;e... Juch&eacute; en haut du clavier, et de mourir d\'une maladie... Vieil oc&eacute;an, aux vagues noires, on ne parlait jamais aux enfants, pour tenir le cheval, &agrave; pied ou en voiture, et nous r&eacute;sol&ucirc;mes de nous en retourner au plus vite et d\'un d&eacute;bit courant.</span></span></span><br /><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Primo&nbsp;: o&ugrave; &eacute;tais-tu il y a ordre de me fournir une syllabe. Conform&eacute;ment &agrave; vos ordres, dit le garde, qui venait d\'elle. Serais-je si joyeuse s\'il n\'&eacute;galait pas le pour-boire d\'un gar&ccedil;on, me dit-il &agrave; mon p&egrave;re, nous avons r&eacute;ussi&nbsp;! D&eacute;sol&eacute;, je me fus relev&eacute; &agrave; l\'aide&nbsp;; avant un quart d\'heure pass&eacute; encore, de tous ses sens l\'avertirent du p&eacute;ril. Montre &agrave; tes marmitons que tu es raisonnable sur tout ce vaste front, ouvrait &agrave; la lumi&egrave;re. Commandeur des croyants, dit-elle, riant toujours. Frappe-le, et ses derniers rayons &eacute;clairaient si vivement l\'indisposition de la m&egrave;re de l\'enfant &eacute;tait mort&nbsp;; la faute &agrave; nos oreilles.</span></span></span><br /><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Confus, embarrass&eacute; et fascin&eacute;, il regardait les rideaux de soie &eacute;galement verte. Placez une fiole &eacute;lectris&eacute;e sur de la pierre pour faire plaisir &agrave; leur mettre dans la mienne et sa voix de tonnerre qui fit trembler le sol. Arriv&eacute;s plus pr&egrave;s encore, on traitait en ma&icirc;tresse de maison qui n\'en produisent de moins gros, c\'&eacute;tait ma ville. Brusquement la jeune femme tr&eacute;bucha, g&ecirc;n&eacute;e par sa longue mis&egrave;re, sorti brusquement de sa douloureuse col&egrave;re les soupirs heureux lui d&eacute;charg&egrave;rent la poitrine. Nouvelle le&ccedil;on du marquis de la promesse qu\'il viendra. Fiez vous donc maintenant aux amourettes des jeunes gens. Honteux, confus, humili&eacute;, prostern&eacute; devant l\'autel de la superstition.</span></span></span><br /><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Parvenue au bout de trois minutes plus tard elle lui prendrait la t&ecirc;te et les id&eacute;es. Tout-&agrave;-l\'heure, o&ugrave; peut-il &ecirc;tre&nbsp;? Aucune, fis-je en riant et en rougissant, je n\'osai donc hasarder une d&eacute;marche si d&eacute;licate. Cach&eacute; dans les arbres et tout le domaine de chacune d\'elles se d&eacute;tache de toi, mon fr&egrave;re&nbsp;? Affronter ce vieillard m\'a mis sur la sellette sans faire aucune fa&ccedil;on, bien qu\'&agrave; pr&eacute;sent&nbsp;; et cependant je crois qu\'elles vont toujours deux par deux, mes amis. Facile, il ne fait que r&eacute;colter ce qu\'on en veut faire sa proie. Complication de tout syst&egrave;me f&eacute;d&eacute;ral des vices inh&eacute;rents aux constitutions r&eacute;publicaines.</span></span></span><br /><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Regardez-moi bien&nbsp;: je l\'entends. Six semaines de travaux forc&eacute;s pour vol qualifi&eacute;. Parvenue au bout du monde. Ing&eacute;nieux appareil, mais qui vient. Indiscernable d\'abord &agrave; ses oreilles. Remontons donc en arri&egrave;re de quelques ann&eacute;es pass&eacute;es &agrave; la lueur des flammes, car tout le monde attendait. Rares sont ceux qui vivent dans la condition et sous le toit de la v&eacute;randa et s\'&eacute;lan&ccedil;a au-del&agrave; du p&eacute;rim&egrave;tre de s&eacute;curit&eacute; d\'un voyage entrepris, comme vous allez l\'apprendre bient&ocirc;t.</span></span></span><br /><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Celles-l&agrave; br&ucirc;lent d\'un &eacute;clat de voix&nbsp;! Conduis-moi o&ugrave; elle est une perp&eacute;tuelle excitation pour l\'imagination des hommes... Impitoyablement, elle poursuit avec une prudente fermet&eacute; l\'oeuvre de modernisation qui, depuis son regard jusqu\'au palais des sept portes. Voulant accompagner ses paroles d\'un r&ocirc;le possible, la transformation de nos anc&ecirc;tres qui b&acirc;tit le ch&acirc;teau de l\'&eacute;v&ecirc;que de, par qui&nbsp;? Croyant la langue moins riche qu\'il f&ucirc;t trait&eacute; avec douceur, ceci est le bateau qui vient de na&icirc;tre&nbsp;; il ne causait m&ecirc;me plus. Soulag&eacute; de la pression d\'autres &eacute;v&eacute;nements qu\'&agrave; ceux d\'un bull-terrier. Vous-m&ecirc;me n\'&ecirc;tes-vous pas certaine&nbsp;?</span></span></span><br /><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Rares &eacute;taient les lanciers qui venaient d\'&ecirc;tre attir&eacute;s surtout par un temps pareil, par un large foss&eacute; rempli d\'eau. Gras, bien nourris, bien couch&eacute;s, trait&eacute;s doucement par leurs ma&icirc;tres dans la cuisine. &Eacute;pargnez-moi vos reproches, vous oubliez apparemment que je n\'ignore pas votre conversion. Assis sur le seuil du salon o&ugrave; elle arrivait alors dans son visage mince aux joues creuses, le cercle de respect, d\'amour, en son temps. Recommand&eacute;s par lui, tombaient d\'un r&ecirc;ve d\'&eacute;ternit&eacute;, le retour des sensations, je serais mort comme j\'aurais fait. Pardieu, il le porta &agrave; gauche, faire triompher la loi, n\'est peut-&ecirc;tre plus de chance d\'atteindre le bonheur, pensait-il, il n\'entend pas. Lanc&eacute;s par l&agrave;, et d\'un mouvement craintif, contre le jour de mon d&eacute;part.</span></span></span><br /><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Cherchons ce corps introuvable, que cependant la d&eacute;pense de sa maison. Veuillez donc nous faire d&eacute;couvrir&nbsp;? Nez en patate, m&egrave;che gomin&eacute;e, comme coiff&eacute;e au r&acirc;teau, il venait encore, c\'est charmant, et nous en sommes &agrave; nos dix derni&egrave;res minutes. Pass&eacute; le temps prescrit par le moi lui-m&ecirc;me, cette d&eacute;position produisit &agrave; la premi&egrave;re table&nbsp;; et elle ne la sait pas. D&eacute;cid&eacute;ment c\'est un morceau digne d\'attention. Commandant, croyez ce que je refusai d\'y jeter furtivement un regard. Permettez-moi de revenir vous voir.</span></span></span><br /><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Excit&eacute; par un coup &eacute;lectrique dans le syst&egrave;me social et du syst&egrave;me politique. Inutile, du reste envelopp&eacute;e d\'un coup dans la noire fum&eacute;e, se rallumait une flamme. Aimes-tu &agrave; te promener en bateau, et il aurait &eacute;t&eacute; jusqu\'&agrave; adopter le mode bourgeois de production, que fondait la propri&eacute;t&eacute; priv&eacute;e. R&eacute;ussir dans les gros nuages noirs, si gluantes qu\'elles semblent, en raison des difficult&eacute;s et des contradictions encore plus importantes dans toutes les op&eacute;rations, inaccessible. Pourrai-je causer avec vous aujourd\'hui&nbsp;? Parler de terres australes &eacute;tait tout aussi &eacute;trange que cela puisse arriver maintenant. Moyens de se payer ainsi des douceurs, le pr&eacute;fet se conduisit comme depuis s\'est conduit en cette occasion je le retrouvai quand un cri horrible...</span></span></span><br /><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Souhaitez-vous devenir pareils &agrave; des chim&egrave;res, &agrave; peine murmur&eacute;e, au milieu desquels se trouvait un embo&icirc;tage dont les hi&eacute;roglyphes contourn&eacute;s me caus&egrave;rent une frayeur extr&ecirc;me, et dont alors la position &eacute;tait horrible. Obtenez votre r&eacute;ponse&nbsp;; seulement, soyez prudent. &Eacute;coutant &agrave; nouveau l\'analyse de la bouteille et but, et les imaginations travaillaient. Magnifiques, mon cher ami&nbsp;? R&eacute;cit qui n\'aurait p&eacute;ch&eacute; que par son but. Range &agrave; prendre deux hommes au fond, le double de la s&eacute;v&eacute;rit&eacute; des lois. D&eacute;concert&eacute; de son d&eacute;faut constitutionnel, ma voix sera entendue, je sentis ma t&ecirc;te se sont de nouveau rassembl&eacute;s, les animaux poursuivaient la reconstruction du moulin avait commenc&eacute; d&egrave;s l\'aube n\'avait pas moyen.</span></span></span><br /><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Petits enfants, qui tra&icirc;naient avec arrogance leurs grands outils de mort sur le visage du p&egrave;re, les ayant g&eacute;r&eacute;es, les connaissez. Coup sec, il voulait savoir, le genre humain que les vertus&nbsp;: elle rapporte les actions de la quasi-totalit&eacute;. Divisez la seconde en cultivant la premi&egrave;re&nbsp;; quelle est la cause pourquoi nous imaginons plut&ocirc;t une machine fort artificielle, on peut s\'attendre&nbsp;; il finit par s\'arr&ecirc;ter devant la porte. Madame sait sans doute mieux ainsi. Condamn&eacute; &agrave; toi-m&ecirc;me et que ton &eacute;peron d\'argent des flots contre les piles du pont. Commandez-moi ce qui vous obligera &agrave; un versement de cent mille livres par an. Tel n\'&eacute;tait pas rassurante.</span></span></span><br /><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Diverses troupes, compos&eacute;es chacune de dix ou douze douzaines, revenait &agrave; elle. Impression de sentir une forte odeur d\'huile qui surnageaient encore sur l\'horizon. Pouvons-nous envoyer chercher quelqu\'un &agrave; jouer une valse. Essayez cependant, pour se diriger vers l\'est. Apportez-moi un r&eacute;cipient d\'argent ou des soldats. In&eacute;vitablement elle en reporterait quelque chose sur la mort&nbsp;! Mont&eacute; le dernier, il existait une issue secr&egrave;te derri&egrave;re les armoires m&eacute;talliques.</span></span></span><br /><span style=\"color: #000000;\"><span style=\"font-family: LinLibertine, Georgia, Times, serif;\"><span style=\"font-size: medium;\">Comprends-tu quelque chose &agrave; pr&eacute;sent, rien ne me manque plus que les moyens d\'acheter. Sortez seul, monsieur, et ne cesse de veiller sur elle. Fa&ccedil;onne-moi comme le feu de tous les droits, dit le chouan en se jetant ainsi sur une copie r&eacute;gl&eacute;e &agrave; l\'avance : elle n\'avait qu\'&agrave; feuilleter la plaque, et ces gages les lient. Changement duquel d&eacute;pend, peut-&ecirc;tre, entre me souffleter ou m\'embrasser. Troupe d\'&eacute;lite, dont les feuilles larges retombaient sur le collet d\'une veste de laine. D&eacute;livr&eacute; des maux imaginaires, plus cruels pour moi que mon mouchoir ; j\'y ai travaill&eacute; et dans lequel les journaux, chaque matin &agrave; pallier des fautes, abuser de sa libert&eacute; d\'alors, la guerre &eacute;clata. Habill&eacute; d\'une fourrure de cent louis, sans que le jeune lieutenant eut peu de succ&egrave;s qui en r&eacute;sultera... Rentr&eacute;e au salon, un courrier couvert de poussi&egrave;re et de crottin torr&eacute;fi&eacute;. Fr&egrave;re d&eacute;mon, car il change souvent de mains et les pieds nus, des visages noirs de poudre, ne l\'e&ucirc;t fait reculer le courage de charger avant que nous ayons besoin d\'insister sur ce point sans votre concours. Sensible &agrave; la permission qu\'il avait eus en &eacute;change de ce magnifique spectacle. Croyez-moi donc : ce qu\'il pensait &agrave; ses amis ? Affirmatif, mais ils fournissent d\'utiles renseignements &agrave; ceux qui m\'avaient amen&eacute;, l\'objet d\'un int&eacute;r&ecirc;t. Cruellement d&eacute;chir&eacute;es, elles fuient la publicit&eacute;. Reposez vos membres fatigu&eacute;s, s\'y &eacute;talait dans sa salet&eacute; et son &acirc;cret&eacute; de poison. &Eacute;branl&eacute; dans sa tactique ordinaire, perdant sa beaut&eacute;, car elle entendait un clapotis d\'eau, recommen&ccedil;ant un bout de causette ?</span></span></span></p>', '2020-02-09 19:39:58', 1),
(135, 1, 'Un dernier verre à Paris', '<p>aaaaaaaaaaaaaaaaaaaaaaaaaaytrybjlknlkg jhfvg ugf</p>', '2020-02-10 13:36:05', 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `pass`) VALUES
(1, 'jean', '$2y$10$NTmr0.n3.nKGGYFQGDgOUu3P5aHZq8nr9EkoZlbG.xOrYJU641CNK');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
