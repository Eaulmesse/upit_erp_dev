-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 27 sep. 2023 à 10:39
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `upit_dev`
--

-- --------------------------------------------------------

--
-- Structure de la table `axonaut_companies`
--

DROP TABLE IF EXISTS `axonaut_companies`;
CREATE TABLE IF NOT EXISTS `axonaut_companies` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creation_date` date NOT NULL,
  `address_street` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_zip_code` int NOT NULL,
  `address_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_region` longtext COLLATE utf8mb4_unicode_ci,
  `address_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` longtext COLLATE utf8mb4_unicode_ci,
  `is_supplier` tinyint(1) NOT NULL,
  `is_prospect` tinyint(1) NOT NULL,
  `is_customer` tinyint(1) NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thirdparty_code` int NOT NULL,
  `intracommunity_number` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_thidparty_code` int DEFAULT NULL,
  `siret` int NOT NULL,
  `is_b2_c` tinyint(1) NOT NULL,
  `language` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `axonaut_companies`
--

INSERT INTO `axonaut_companies` (`id`, `name`, `creation_date`, `address_street`, `address_zip_code`, `address_city`, `address_region`, `address_country`, `comments`, `is_supplier`, `is_prospect`, `is_customer`, `currency`, `thirdparty_code`, `intracommunity_number`, `supplier_thidparty_code`, `siret`, `is_b2_c`, `language`) VALUES
(13714980, 'IT-LOGIQ', '2022-11-23', '10 RUE DU PROGRES', 93100, 'Montreuil', NULL, 'FRANCE', '', 1, 0, 0, 'EUR', 4116, '0', 4012, 523, 0, 'fr'),
(13715025, 'PEREN-IT', '2022-11-23', '1390 AVENUE CELESTIN COQ', 0, '13790	 ', NULL, 'FRANCE', '', 1, 0, 0, 'EUR', 4117, '0', 4013, 502, 0, 'fr'),
(13715700, 'BG COM AGENCY', '2022-11-23', '28 RUE BARABAN', 69003, 'Lyon', NULL, '', 'ONE NOTE&nbsp;<a href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/EordQK1VWiBCpEwnv2PuK6cBtSiM8MFJlsKiZaV-TA7D1Q?e=jZyD3r\">BG COM AGENCY</a>', 0, 0, 1, 'EUR', 4118, '0', NULL, 887698918, 0, 'fr'),
(13715722, 'REGIE DES LUMIERES', '2022-11-23', '175 ROUTE DE VIENNE', 69008, 'LYON 08', NULL, 'FRANCE', 'ONE NOTE&nbsp;<a href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/Ep_SDXpkhTpMjs_5vqMY_EIBtiDUuWPJK51sVzmaaXdxVw?e=TwRD5C\">REGIE DES LUMIERES</a>', 0, 0, 1, 'EUR', 4119, '0', NULL, 903, 0, 'fr'),
(13715732, 'JINGLE FOR YOU', '2022-11-23', '53 RUE PRESIDENT PAUL KRUGER', 69008, 'LYON 08', NULL, 'FRANCE', '', 1, 0, 1, 'EUR', 41110, '0', 40126, 538, 0, 'fr'),
(13716719, 'VASSEL GRAPHIQUE', '2022-11-23', 'BOULEVARD DES DROITS DE L HOMME', 69500, 'BRON', NULL, 'FRANCE', '', 1, 1, 1, 'EUR', 41111, '0', 40130, 958, 0, 'fr'),
(13716734, 'LEA LOGISTIQUE', '2022-11-23', '1 AVENUE JACQUES DE VAUCANSON', 66600, 'RIVESALTES', NULL, 'FRANCE', 'ONE NOTE&nbsp;<a href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/Eq5TTHEyvvRIufMBJbYnCXkBPjepUlEo49PmMREPZCMhRA?e=zzzK6p\">LEA LOGISTIQUE</a>', 0, 0, 1, 'EUR', 41112, '0', NULL, 508, 0, 'fr'),
(13716760, 'GIBERTTRANS', '2022-11-23', '12 AVENUE GASPARD MONGE', 69720, 'SAINT-BONNET-DE-MURE', '', 'FRANCE', 'ONENOTE :&nbsp;<a href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/EnSsRe35GEVBr87_NljpphIBloZR_PFOTIPPFTv4-LWWvg?e=HJ7bab\">GIBERTTRANS</a>', 0, 0, 1, 'EUR', 41113, '0', NULL, 508, 0, 'fr'),
(13718985, 'ASTEC ASCENSEURS TECHNIQUES', '2022-11-23', '7 CHEMIN DE LA BROCARDIERE', 69570, 'DARDILLY', NULL, 'FRANCE', 'ONE NOTE&nbsp;<a href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/EsgfdgMmpLhBt21nRDhtrtsBqH3OgIyahZmc9ncYC7rPYg?e=d6USlR\">ASTEC ASCENSEURS</a>', 0, 0, 1, 'EUR', 41114, '0', NULL, 423, 0, 'fr'),
(13719183, 'AKILYA', '2022-11-23', '191 AVENUE SAINT EXUPERY', 69500, 'Bron', NULL, 'FRANCE', 'ONE NOTE <a href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/EpDXUqxHV9hMge3ZinVPN_EB5NaiJkuHgTNKQtG8BLSfjw?e=pzbb4x\">AKILYA - ABSAMS</a>', 0, 0, 1, 'EUR', 41115, '0', NULL, 752, 0, 'fr'),
(13744109, 'OFFICEEASY', '2022-11-25', '60 AVENUE DE L EUROPE', 92270, 'Bois-Colombes', NULL, '', '', 1, 0, 0, 'EUR', 41117, '0', 4014, 511, 0, 'fr'),
(13772239, 'SEWAN', '2022-11-29', '2 CITE PARADIS', 75010, 'PARIS 10', NULL, 'FRANCE', '', 1, 0, 0, 'EUR', 41118, '0', 4015, 452, 0, 'fr'),
(13834789, 'ACCES MEDIA STORE ET ASSISTANCE BUREAUTIQUE', '2022-12-06', '191 AVENUE SAINT EXUPERY', 69500, 'Bron', NULL, '', '', 0, 0, 1, 'EUR', 41119, '0', NULL, 499, 0, 'fr'),
(13834955, 'PRESTINFO MAINTENANCE', '2022-12-06', '18 AVENUE DE LA ZAC CHASSAGNE', 69360, 'TERNAY', NULL, 'FRANCE', '', 0, 0, 1, 'EUR', 41120, '0', NULL, 413, 0, 'fr'),
(13835024, 'HASLER FRANCE', '2022-12-06', '496 Rue Louis Breguet', 38780, 'PONT-EVEQUE', NULL, 'FRANCE', '', 0, 0, 1, 'EUR', 41121, '0', NULL, 514, 0, 'fr'),
(13835942, '3CX', '2022-12-06', 'Markou Drakou 4', 2409, 'NICOSIA', NULL, 'CHYPRE', '', 1, 0, 0, 'EUR', 41122, '0', 4016, 0, 0, 'en'),
(13863171, 'GRENKE LOCATION LYON', '2022-12-08', '54 Rue Marcel Dassault\r\nParc Everest', 69740, 'Genas', NULL, 'FRANCE', '', 0, 0, 1, 'EUR', 41124, '0', NULL, 0, 0, 'fr'),
(13969719, 'PC21', '2022-12-15', '1 ALLEE ROLAND GARROS', 93360, 'Neuilly-Plaisance', NULL, 'FRANCE', '', 1, 0, 0, 'EUR', 41125, '0', 4017, 432, 0, 'fr'),
(13982188, 'JFP & ASSOCIES', '2022-12-16', '2 Chemin des  Vionots', 63720, 'CHAPPES', '', 'FRANCE', 'ONE NOTE&nbsp;<a href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/Estj4MAtnIdGsKFgMvzDdlQBeEJq5ifxPZ7xdmoC7ZPaag?e=ftTqDb\">LE DOMAINE DE LIMAGNE</a>', 0, 0, 1, 'EUR', 41126, '0', NULL, 2147483647, 0, 'fr'),
(14031254, 'DB-FACTORY SGL', '2022-12-21', '2 AVENUE CHARLES DE GAULLE', 69230, 'ST GENIS LAVAL', NULL, '', '', 0, 1, 0, 'EUR', 41127, '0', NULL, 921, 0, 'fr'),
(14227165, 'Gmail test', '2023-01-05', '14-22 Av. Barthelemy Thimonnier', 69300, 'Caluire-et-Cuire', '', 'France', '', 0, 0, 1, 'EUR', 41128, '0', NULL, 0, 0, 'fr'),
(14263067, 'MAGNOLIA GROUPE', '2023-01-09', 'All. des Hêtres', 69760, 'Limonest', '', 'France', '', 0, 1, 1, 'EUR', 41130, '0', NULL, 848, 0, 'fr'),
(14270242, 'SFR BUSINESS SOLUTIONS', '2023-01-10', '12 AVENUE D OCEANIE', 91940, 'Les Ulis', NULL, '', '', 1, 0, 0, 'EUR', 41131, '0', 4018, 348, 0, 'fr'),
(14291573, 'LES BURGERS DE PAPA CHARPENNES', '2023-01-12', '54 Rue Marcel Dassault\nParc Everest', 69740, 'GENAS', NULL, 'France', NULL, 0, 1, 0, 'EUR', 41132, '0', NULL, 0, 0, 'fr'),
(14291606, 'KEPAX', '2023-01-12', '54 Rue Marcel Dassault\nParc Everest', 69740, 'GENAS', NULL, 'France', NULL, 0, 1, 0, 'EUR', 41133, '0', NULL, 0, 0, 'fr'),
(14291628, 'LABARONNE-CITAF', '2023-01-12', '54 Rue Marcel Dassault\nParc Everest', 69740, 'GENAS', NULL, 'France', '<p>ONE NOTE:&nbsp;<a href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/EhBYnFCr7zJCnfQ7Thpe9EcBGjLky-IG0XJ5JpO8vgLdAA?e=dnD7uc\">LABARONNE CITAF</a></p>', 0, 0, 1, 'EUR', 41134, '0', NULL, 2147483647, 0, 'fr'),
(14291655, 'JSP-ASSOCIES', '2023-01-12', '54 Rue Marcel Dassault\nParc Everest', 69740, 'GENAS', NULL, 'France', NULL, 0, 1, 0, 'EUR', 41135, '0', NULL, 0, 0, 'fr'),
(14291695, 'LE DOMAINE DE LIMAGNE', '2023-01-12', '54 Rue Marcel Dassault\nParc Everest', 69740, 'GENAS', NULL, 'France', 'Auvergne :&nbsp;<a style=\"box-sizing: border-box; background-color: rgba(6, 162, 214, 0.12); color: #2574a9; text-decoration-line: none; transition: all 0.2s ease-in-out 0s; font-family: \'Open Sans\', arial, sans-serif; font-size: 14.7px;\" title=\"Voir la fiche du prospect\" href=\"https://axonaut.com/business/company/show/15312833\"><span id=\"companyName15312833\" style=\"box-sizing: border-box;\">PRODUITS GASTRONOMIQUES DE LIMAGNE</span></a><br />Lyon:&nbsp;<a style=\"box-sizing: border-box; background-color: rgba(0, 143, 196, 0.25); color: #2574a9; text-decoration-line: none; transition: all 0.2s ease-in-out 0s; font-family: \'Open Sans\', arial, sans-serif; font-size: 14.7px;\" title=\"Voir la fiche du client\" href=\"https://axonaut.com/business/company/show/13982188\"><span id=\"companyName13982188\" style=\"box-sizing: border-box;\">JFP &amp; ASSOCIES<br /><span style=\"color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; background-color: #ffffff;\">ONE NOTE&nbsp;</span><a style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px;\" href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/Estj4MAtnIdGsKFgMvzDdlQBeEJq5ifxPZ7xdmoC7ZPaag?e=ftTqDb\">LE DOMAINE DE LIMAGNE</a><br /></span></a>', 0, 1, 0, 'EUR', 41136, '0', NULL, 0, 0, 'fr'),
(14291707, 'GROUPE OVISION', '2023-01-12', 'La Roche', 69620, 'VAL-D\'OINGT', '', 'France', 'ONENOTE:&nbsp;<a href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/EveaSKP3hABOm7k3Zb5c4MEBCAv0prAwpuI2U87tyuOLKw?e=kfCriT\">OVISION</a>', 0, 0, 1, 'EUR', 41137, '0', NULL, 2147483647, 0, 'fr'),
(14291927, 'NOTRE AGENCE IMMO', '2023-01-12', '54 Rue Marcel Dassault\nParc Everest', 69740, 'GENAS', NULL, 'France', 'ONE NOTE:&nbsp;<a href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/EhyIMJ8PzrRFjxK7acDLdugBiTfjVYH7lECdpEiEjRbLNA?e=rXsHf5\">NOTRE AGENCE IMMO</a>', 0, 0, 1, 'EUR', 41140, '0', NULL, 2147483647, 0, 'fr'),
(14712612, 'AC NEXITUP', '2023-01-28', '25 RUE JACQUES MONOD', 69120, 'VAULX-EN-VELIN', NULL, 'France', '', 1, 0, 0, 'EUR', 41141, '0', 4019, 2147483647, 0, 'fr'),
(14892920, 'ARRK LCO PROTOMOULE', '2023-02-08', '194 ALL DE CHAMPS GALERE', 74540, 'ALBY SUR CHERAN', NULL, 'FRANCE', '', 0, 1, 0, 'EUR', 41144, '0', NULL, 2147483647, 0, 'fr'),
(14893365, 'SNOM TECHNOLOGY GMBH', '2023-02-08', '130 Avenue Joseph Kessel', 78960, 'VOISINS-LE-BRETONNEUX', NULL, 'France', '', 1, 0, 0, 'EUR', 41145, '0', 40111, 2147483647, 0, 'fr'),
(14974957, 'POL DEVELOPPEMENT', '2023-02-09', '1 Quai du Commerce', 69009, 'LYON', '', '', 'BG CREATION (site de Trattoria) : <a href=\"https://axonaut.com/business/company/show/18093896\">ICI</a><br />LE COMPTOIR DE l\'EST (site M\'LOBSTER) : <a href=\"https://axonaut.com/business/company/show/18093914\">ICI</a>', 0, 0, 1, 'EUR', 41146, '0', NULL, 2147483647, 0, 'fr'),
(15022089, 'GIRERD DISTRIBUTION', '2023-02-13', '1477 Route du Vissoux', 69620, 'SAINT-VERAND', '', '', '', 0, 1, 0, 'EUR', 41147, '0', NULL, 2147483647, 0, 'fr'),
(15251080, 'GT INFORMATIQUE', '2023-02-20', '8 PARC DU CENTRE', 69100, 'VILLEURBANNE', NULL, 'FRANCE', '', 0, 0, 1, 'EUR', 41149, '0', NULL, 2147483647, 0, 'fr'),
(15298012, 'REGIE PEDRINI', '2023-02-23', '', 0, '', '', '', NULL, 0, 1, 0, 'EUR', 41150, '0', NULL, 0, 0, 'fr'),
(15312302, 'SARL BASTIAN', '2023-02-24', '1 RUE DE LA MARINIERE', 74950, 'SCIONZIER', NULL, '', '', 0, 1, 0, 'EUR', 41151, '0', NULL, 2147483647, 0, 'fr'),
(15312833, 'PRODUITS GASTRONOMIQUES DE LIMAGNE', '2023-02-24', '2 Chemin des Vionots', 63720, 'CHAPPES', '', '', 'PROGALIM:&nbsp;<a style=\"box-sizing: border-box; color: #2574a9; text-decoration-line: none; transition: all 0.2s ease-in-out 0s; font-family: \'Open Sans\', arial, sans-serif; font-size: 14.7px;\" title=\"Voir la fiche du prospect\" href=\"https://axonaut.com/business/company/show/14291695\"><span id=\"companyName14291695\" style=\"box-sizing: border-box;\">LE DOMAINE DE LIMAGNE<br /><span style=\"color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; background-color: #ffffff;\">ONE NOTE&nbsp;</span><a style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px;\" href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/Estj4MAtnIdGsKFgMvzDdlQBeEJq5ifxPZ7xdmoC7ZPaag?e=ftTqDb\">LE DOMAINE DE LIMAGNE</a></span></a>', 0, 0, 1, 'EUR', 41152, '0', NULL, 2147483647, 0, 'fr'),
(15364885, 'CABINET SAVOYE', '2023-02-27', '40 RUE DE LA REPUBLIQUE', 38300, 'BOURGOIN-JALLIEU', NULL, '', '', 0, 1, 0, 'EUR', 41153, '0', NULL, 2147483647, 0, 'fr'),
(15447799, 'FRAIS REPAS', '2023-03-01', '', 0, '', NULL, '', '', 1, 0, 0, 'EUR', 41155, '0', 40113, 0, 0, 'fr'),
(15447804, 'FRAIS HOTEL', '2023-03-01', '', 0, '', NULL, '', '', 1, 0, 0, 'EUR', 41156, '0', 40114, 0, 0, 'fr'),
(15447806, 'FRAIS TRANSPORT', '2023-03-01', '', 0, '', NULL, '', '', 1, 0, 0, 'EUR', 41157, '0', 40115, 0, 0, 'fr'),
(15642901, 'AMAZON BUSINESS EU SARL', '2023-03-03', '38 AVENUE JOHN F.KENNEDY', 99137, 'L-1855 LUXEMBOURG', NULL, '', '', 1, 0, 0, 'EUR', 41158, '0', 40116, 2147483647, 0, 'fr'),
(15644318, 'ACT-ON DEVELOPPEMENT', '2023-03-03', '19 RUE D ORLEANS', 92200, 'NEUILLY-SUR-SEINE', '', '', '', 0, 0, 1, 'EUR', 41159, '0', NULL, 2147483647, 0, 'fr'),
(15666928, 'LDLC PRO', '2023-03-06', '2 RUE DES ERABLES', 69760, 'LIMONEST', NULL, '', '', 1, 0, 0, 'EUR', 41160, '0', 40117, 2147483647, 0, 'fr'),
(15789963, 'L-SHOP-TEAM FRANCE', '2023-03-14', '1 RUE MONSEIGNEUR ANCEL', 69800, 'SAINT-PRIEST', NULL, 'France', 'ONE NOTE:&nbsp;<a href=\"https://groupeupit.sharepoint.com/:o:/s/Technique/Ek-E4WfbYJtFpq9Frji1B9sB-G3Q05aCfq7aM-YSHpKuJQ?e=e7d7fw\">L-SHOP-TEAM FRANCE</a><br />', 0, 0, 1, 'EUR', 41161, '0', NULL, 2147483647, 0, 'fr'),
(15802733, 'EXCLUSIVE NETWORKS', '2023-03-16', '20 QUAI DU POINT DU JOUR', 92100, 'BOULOGNE-BILLANCOURT', NULL, 'France', '', 1, 0, 0, 'EUR', 41162, '0', 40118, 2147483647, 0, 'fr'),
(15877134, 'KLYDE AVOCATS', '2023-03-21', '152 RUE PIERRE CORNEILLE', 69003, 'LYON 3EME', NULL, 'FRANCE', '', 0, 1, 0, 'EUR', 41163, '0', NULL, 2147483647, 0, 'fr'),
(15895827, 'OVISION CHARTRES SUD', '2023-03-22', 'ROUTE DEPARTEMENTALE 910', 28630, 'BARJOUVILLE', NULL, 'FRANCE', '', 0, 0, 1, 'EUR', 41164, '0', NULL, 2147483647, 0, 'fr'),
(15931545, 'LOBATO IT', '2023-03-24', '8 RUE CHINARD', 69009, 'LYON 9EME', NULL, 'FRANCE', '', 1, 0, 0, 'EUR', 41165, '0', 40119, 2147483647, 0, 'fr'),
(16056570, 'COMMUNE DE TIGNIEU JAMEYZIEU', '2023-03-31', 'PL DE LA MAIRIE', 38230, 'TIGNIEU-JAMEYZIEU', NULL, '', '', 0, 1, 0, 'EUR', 41166, '0', NULL, 2147483647, 0, 'fr'),
(16140730, 'Vélum/bgcom', '2023-04-06', '', 0, '', '', '', NULL, 1, 0, 0, 'EUR', 41167, '0', 40120, 0, 0, 'fr'),
(16140731, 'TRANSMODAL', '2023-04-06', '', 0, '', '', '', NULL, 1, 0, 1, 'EUR', 41168, '0', 40121, 0, 0, 'fr'),
(16185755, 'DIAC', '2023-04-12', '14 Avenue du Pavé Neuf', 93168, 'NOISY-LE-GRAND', NULL, 'France', '', 1, 0, 0, 'EUR', 41169, '0', 40122, 702002221, 0, 'fr'),
(16185904, 'VOLKSWAGEN BANK', '2023-04-12', '15 Avenue de la Demi-Lune', 95700, 'ROISSY-EN-FRANCE', NULL, 'FRANCE', '', 1, 0, 0, 'EUR', 41170, '0', 40123, 2147483647, 0, 'fr'),
(16239517, 'LYCEE POLYVALENT G.FICHET', '2023-04-14', '219 RUE DE PRESSY', 74130, 'BONNEVILLE', NULL, '', '', 0, 1, 0, 'EUR', 41171, '0', NULL, 2147483647, 0, 'fr'),
(16240131, 'COTE SAONE IMMOBILIER', '2023-04-14', '11 Rue De Bourgogne', 69009, 'LYON', '', 'FRANCE', '<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\"><span style=\"font-weight: bold;\">Entit&eacute; principale :</span></p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\">C&Ocirc;T&Eacute; SA&Ocirc;NE IMMOBILIER (Champagne, Lyon 1, Lyon 9):&nbsp;<br /><a href=\"https://axonaut.com/business/company/show/16240131\">https://axonaut.com/business/company/show/16240131</a></p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\">&nbsp;</p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\"><span style=\"font-weight: bold;\">Entit&eacute;s secondaires :</span></p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\">4F LA TOUR (Tour de Salvagny) :<br /><a href=\"https://axonaut.com/business/company/show/16765650\">https://axonaut.com/business/company/show/16765650</a></p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\">4F IMMO PARC (Lyon 6) :<br /><a href=\"https://axonaut.com/business/company/show/16765675\">https://axonaut.com/business/company/show/16765675</a></p>', 0, 0, 1, 'EUR', 41172, '0', NULL, 2147483647, 0, 'fr'),
(16241535, 'NSE', '2023-04-14', '43 RUE DE LA CITE', 69003, 'LYON 3EME', NULL, '', '', 0, 0, 1, 'EUR', 41173, '0', NULL, 2147483647, 0, 'fr'),
(16241570, 'NOIISE', '2023-04-14', '22 RUE LAURE DIEBOLD', 69009, 'LYON 9EME', NULL, '', '', 0, 1, 0, 'EUR', 41174, '0', NULL, 2147483647, 0, 'fr'),
(16245334, 'BOUYGUES TELECOM', '2023-04-15', '37-39 Rue Boissière', 75116, 'PARIS 16', NULL, 'FRANCE', '', 0, 0, 1, 'EUR', 41175, '0', NULL, 2147483647, 0, 'fr'),
(16245348, 'SOCIETE FRANCAISE DU RADIOTELEPHONE - S.F.R', '2023-04-15', '16 Rue du Général Alain de Boissieu', 75015, 'PARIS', NULL, 'FRANCE', '', 0, 0, 1, 'EUR', 41176, '0', NULL, 2147483647, 0, 'fr'),
(16312105, 'ZFX LYON', '2023-04-18', '89 AV DES BRUYERES', 69150, 'DECINES CHARPIEU', NULL, '', '', 0, 1, 0, 'EUR', 41177, '0', NULL, 2147483647, 0, 'fr'),
(16331374, 'SEABOURNE EXPRESS COURIER SARL', '2023-04-19', '6 ALL DES ERABLES, D6', 69200, 'VENISSIEUX', '', '', '', 1, 0, 1, 'EUR', 41178, '0', 40133, 2147483647, 0, 'fr'),
(16364332, 'OPERA', '2023-04-21', '43 RUE DES REMPARTS D\'AINAY', 69002, 'LYON', NULL, '', '', 0, 1, 1, 'EUR', 41179, '0', NULL, 2147483647, 0, 'fr'),
(16384514, 'STIC', '2023-04-24', '44 RUE LOUIS AULAGNE', 69600, 'OULLINS', NULL, 'FRANCE', '', 0, 1, 0, 'EUR', 41180, '0', NULL, 2147483647, 0, 'fr'),
(16401111, 'BORACAY', '2023-04-24', '3 CHE DU JUBIN', 69570, 'DARDILLY', NULL, '', '', 0, 1, 0, 'EUR', 41181, '0', NULL, 2147483647, 0, 'fr'),
(16446611, 'ATS STUDIO', '2023-04-26', '32 QUAI JAYR', 69009, 'LYON 9EME', NULL, 'France', '', 1, 0, 0, 'EUR', 41182, '0', 40124, 2147483647, 0, 'fr'),
(16486825, 'CROQUE-EN-BOUCHE', '2023-04-28', '13 T PL JULES FERRY', 69006, 'LYON', NULL, '', '', 0, 0, 1, 'EUR', 41183, '0', NULL, 2147483647, 0, 'fr'),
(16486884, 'LA CUISINE LYONNAISE - LEON DE LYON', '2023-04-28', '13 T PL JULES FERRY', 69006, 'LYON', '', 'FRANCE', 'Ancien RUM :&nbsp;<a id=\"customfield51993\" class=\"editable editable-click\" style=\"box-sizing: border-box; color: #2574a9; transition: all 0.2s ease-in-out 0s; border-color: #9499a3 #9499a3 white; border-bottom-width: 1px; border-bottom-style: dashed; font-family: \'Open Sans\', arial, sans-serif;\" data-type=\"text\"></a>EDI2316485RU0000133', 0, 0, 1, 'EUR', 41184, '0', NULL, 2147483647, 0, 'fr'),
(16487271, 'BRASSERIE DE LA CROIX ROUSSE', '2023-04-28', '13 T PL JULES FERRY', 69006, 'LYON', '', 'FRANCE', '', 0, 1, 0, 'EUR', 41185, '0', NULL, 2147483647, 0, 'fr'),
(16487333, 'P&B MERCIERE', '2023-04-28', '13 T PL JULES FERRY', 69006, 'LYON', NULL, '', '', 0, 1, 0, 'EUR', 41186, '0', NULL, 2147483647, 0, 'fr'),
(16492367, 'APPLE RETAIL FRANCE EURL', '2023-04-28', '3 RUE ST GEORGES', 75009, 'PARIS', NULL, 'FRANCE', '', 1, 0, 0, 'EUR', 41187, '0', 40125, 2147483647, 0, 'fr'),
(16668355, 'ENERGIE SERVICE', '2023-05-09', 'ZAC LES A VIES', 38290, 'FRONTONAS', NULL, '', '', 0, 1, 0, 'EUR', 41188, '0', NULL, 2147483647, 0, 'fr'),
(16730074, 'HOTEL DES MUSES', '2023-05-11', '61 RUE CENTRALE', 74940, 'ANNECY', NULL, '', '', 0, 0, 1, 'EUR', 41189, '0', NULL, 2147483647, 0, 'fr'),
(16757502, 'SIMAGE', '2023-05-11', '20 quai de Charezieux\r\n', 69270, 'Saint-Romain-au-mont-d\'or ', NULL, '', '', 1, 0, 0, 'EUR', 41190, '0', 40127, 2147483647, 0, 'fr'),
(16760388, 'STAL SOCIETE DE TRANSFORMATION D\'ALUMINIUM', '2023-05-11', 'AV DE PERPIGNAN', 66140, 'CANET-EN-ROUSSILLON', '', '', '', 0, 1, 0, 'EUR', 41191, '0', NULL, 2147483647, 0, 'fr'),
(16765650, '4F LA TOUR', '2023-05-12', '2 AV DES MONT D OR', 69890, 'LA TOUR DE SALVAGNY', NULL, '', '<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\"><span style=\"font-weight: bold;\">Entit&eacute; principale :</span></p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\">C&Ocirc;T&Eacute; SA&Ocirc;NE IMMOBILIER (Champagne, Lyon 1, Lyon 9): <a href=\"https://axonaut.com/business/company/show/16240131\">https://axonaut.com/business/company/show/16240131</a></p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\">&nbsp;</p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\"><span style=\"font-weight: bold;\">Entit&eacute;s secondaires :</span></p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\">4F LA TOUR (Tour de Salvagny):<br /><a href=\"https://axonaut.com/business/company/show/16765650\">https://axonaut.com/business/company/show/16765650</a></p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\">4F IMMO PARC (Lyon 6) :<br /><a href=\"https://axonaut.com/business/company/show/16765675\">https://axonaut.com/business/company/show/16765675</a></p>', 0, 0, 1, 'EUR', 41192, '0', NULL, 2147483647, 0, 'fr'),
(16765675, '4F IMMO PARC', '2023-05-12', '79 RUE TRONCHET', 69006, 'LYON 6EME', NULL, 'France', '<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\"><span style=\"font-weight: bold;\">Cette entit&eacute; n\'a pas encore de RIB au 29/05, utiliser celui de C&ocirc;t&eacute; Sa&ocirc;ne Immo.<br /><br />Entit&eacute; principale :</span></p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\">C&Ocirc;T&Eacute; SA&Ocirc;NE IMMOBILIER (Champagne, Lyon 1, Lyon 9):<br /><a href=\"https://axonaut.com/business/company/show/16240131\">https://axonaut.com/business/company/show/16240131</a></p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\">&nbsp;</p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\"><span style=\"font-weight: bold;\">Entit&eacute;s secondaires :</span></p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\">4F LA TOUR (Tour de Salvagny):<br /><a href=\"https://axonaut.com/business/company/show/16765650\">https://axonaut.com/business/company/show/16765650</a></p>\n<p style=\"margin: 0in; font-family: Calibri; font-size: 11.0pt;\">4F IMMO PARC (Lyon 6) :<br /><a href=\"https://axonaut.com/business/company/show/16765675\">https://axonaut.com/business/company/show/16765675</a></p>', 0, 0, 1, 'EUR', 41193, '0', NULL, 2147483647, 0, 'fr'),
(16817617, 'PB', '2023-05-15', '3 RUE TUPIN', 69002, 'Lyon', NULL, 'France', '', 0, 0, 1, 'EUR', 41194, '0', NULL, 2147483647, 0, 'fr'),
(16881804, 'AGEMETRA', '2023-05-17', '23, avenue des Saules ', 69922, 'OULLINS cedex', NULL, 'France', 'M&eacute;decine du Travail&nbsp;', 1, 0, 0, 'EUR', 41195, '0', 40128, 0, 0, 'fr'),
(16994016, 'CDISCOUNT', '2023-05-22', '', 0, '', '', '', NULL, 1, 0, 0, 'EUR', 41196, '0', 40129, 0, 0, 'fr'),
(17162690, 'LA CUISINE LYONNAISE - MAMMA OSTERIA', '2023-05-31', '13 T PL JULES FERRY', 69006, 'LYON', '', '', 'Ancien RUM :&nbsp;<a id=\"customfield51993\" class=\"editable editable-click\" style=\"box-sizing: border-box; color: #2574a9; transition: all 0.2s ease-in-out 0s; border-color: #9499a3 #9499a3 white; border-bottom-width: 1px; border-bottom-style: dashed; font-family: \'Open Sans\', arial, sans-serif;\" data-type=\"text\"></a>EDI2316485RU0000133', 0, 0, 1, 'EUR', 41197, '0', NULL, 2147483647, 0, 'fr'),
(17187378, 'REGIE MARTINET ET ASSOCIES', '2023-06-02', '65 RUE DE LA REPUBLIQUE', 69600, 'OULLINS', NULL, '', '', 0, 0, 1, 'EUR', 41198, '0', NULL, 2147483647, 0, 'fr'),
(17233413, 'BOCCARD SERVICES', '2023-06-07', 'Rue Lect 29', 1217, 'MEYRIN', NULL, 'SUISSE', '', 0, 1, 0, 'EUR', 41199, '0', NULL, 0, 0, 'fr'),
(17283554, 'BOCCARD', '2023-06-13', '158 AV ROGER SALENGRO', 69100, 'VILLEURBANNE', NULL, '', '', 0, 0, 1, 'EUR', 411100, '0', NULL, 2147483647, 0, 'fr'),
(17415214, 'SA IESA', '2023-06-20', '30 AV GAL LECLERC', 38200, 'VIENNE', NULL, '', '', 0, 0, 1, 'EUR', 411101, '0', NULL, 2147483647, 0, 'fr'),
(17439071, 'BOCCARD PORTUGAL LDA', '2023-06-22', 'Rua da Indústria nº8 - Ap.10', 2251, 'CONSTANCIA', NULL, 'PORTUGAL', '', 0, 0, 1, 'EUR', 411102, '0', NULL, 502852950, 0, 'fr'),
(17511294, 'ALSO FRANCE', '2023-06-27', '14 CHEMIN DU JUBIN ', 69570, 'Dardilly', NULL, 'France', '', 1, 0, 0, 'EUR', 411103, '0', 40131, 2147483647, 0, 'fr'),
(17525631, 'LEA LOGISTIQUE IMMO', '2023-06-28', 'AV JACQUES DE VAUCANSON', 66600, 'RIVESALTES', NULL, '', '', 0, 0, 1, 'EUR', 411104, '0', NULL, 2147483647, 0, 'fr'),
(17971796, 'PACHA KEBAB', '2023-07-28', '', 0, '', '', '', NULL, 1, 0, 0, 'EUR', 411105, '0', 40132, 0, 0, 'fr'),
(18093896, 'BG CREATION', '2023-08-07', '2 AV SIMONE VEIL', 69150, 'DECINES CHARPIEU', '', '', 'Siret&nbsp;97764871600016<br /><span style=\"color: #333333; font-family: \'Open Sans\', arial, sans-serif;\">Holding :&nbsp;</span><a style=\"box-sizing: border-box; color: #2574a9; text-decoration-line: none; transition: all 0.2s ease-in-out 0s; font-family: \'Open Sans\', arial, sans-serif;\" href=\"https://axonaut.com/business/company/show/14974957\">POL DEVELOPPEMENT&nbsp;</a>', 0, 0, 1, 'EUR', 411106, '0', NULL, 2147483647, 0, 'fr'),
(18093914, 'LE COMPTOIR DE L\'EST', '2023-08-07', '1 QUAI DU COMMERCE', 69009, 'LYON 9EME', NULL, '', 'Holding : <a href=\"https://axonaut.com/business/company/show/14974957\">POL DEVELOPPEMENT&nbsp;</a>', 0, 0, 1, 'EUR', 411107, '0', NULL, 2147483647, 0, 'fr'),
(18258568, 'TD SYNNEX', '2023-08-24', '5, Avenue de l\'Europe Bussy Saint-Georges ', 77611, 'Marne la Vallée Cedex 3', NULL, 'France', '', 1, 0, 0, 'EUR', 411108, '0', 40134, 0, 0, 'fr'),
(18445529, 'OVISION MARSEILLE', '2023-08-31', '16 AV DE DELPHES', 13006, 'MARSEILLE', NULL, '', 'Pas de pr&eacute;l&egrave;vement pour Marseille, c\'est Lauris qui paie par virement.&nbsp;', 0, 0, 1, 'EUR', 411109, '0', NULL, 2147483647, 0, 'fr'),
(18459703, 'REFURB PLANET', '2023-09-01', '6 Rue Clément Ader ', 77170, 'BRIE-COMPTE-ROBERT', NULL, '', '', 1, 0, 0, 'EUR', 411110, '0', 40135, 0, 0, 'fr'),
(18557585, 'OVISION LIMAS', '2023-09-07', '61 RUE HENRI DEPAGNEUX', 69400, 'Informations commerciales', NULL, '', '', 0, 1, 0, 'EUR', 411111, '0', NULL, 2147483647, 0, 'fr'),
(18557608, 'OVISION CSR LYON SUD', '2023-09-07', '73 RUE PASTEUR', 38670, 'CHASSE-SUR-RHONE', NULL, '', '', 0, 1, 0, 'EUR', 411112, '0', NULL, 2147483647, 0, 'fr'),
(18557628, 'OVISION LYON', '2023-09-07', '35 RUE PRE GAUDRY', 69007, 'LYON 7EME', NULL, '', '', 0, 1, 0, 'EUR', 411113, '0', NULL, 2147483647, 0, 'fr'),
(18654176, 'VKARD', '2023-09-12', '95 rue Ampère', 75017, 'Paris', NULL, '', '', 1, 0, 0, 'EUR', 411114, '0', 40136, 0, 0, 'fr'),
(18720052, 'BRASSERIE IRMA', '2023-09-19', '74 RUE CENTRALE', 74940, 'ANNECY', NULL, '', '', 0, 0, 1, 'EUR', 411115, '0', NULL, 2147483647, 0, 'fr'),
(18792528, 'GIBERTNORD', '2023-09-21', '12 AV GASPARD MONGE', 69720, 'ST BONNET DE MURE', NULL, '', '', 0, 1, 0, 'EUR', 411116, '0', NULL, 2147483647, 0, 'fr');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
