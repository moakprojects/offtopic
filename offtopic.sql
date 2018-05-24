-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2018 at 04:18 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

DROP DATABASE IF EXISTS Offtopic;
CREATE DATABASE Offtopic;
USE Offtopic;

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_check_login_id` (IN `loginID` VARCHAR(255))  READS SQL DATA
SELECT * FROM user WHERE email = loginID OR username = loginID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_dissolve_suspension` (IN `dissolveUser` VARCHAR(255))  MODIFIES SQL DATA
UPDATE user SET accessLevel = 1 WHERE username = dissolveUser$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_get_all_category` ()  READS SQL DATA
SELECT category.*, numberOfTopics, numberOfPosts, numberOfLikes
    FROM category 
    LEFT JOIN (
        SELECT categoryID, count(*) as numberOfTopics, topicID 
        FROM topic 
        GROUP BY categoryID) as topics ON topics.categoryID = category.categoryID 
    LEFT JOIN (
        SELECT topic.categoryID, topic.topicID, sum(postQuantity) as numberOfPosts 
        FROM `topic` 
        LEFT JOIN (
            SELECT topicID, count(*) as postQuantity 
            FROM post 
            GROUP BY topicID) as countPost ON countPost.topicID = topic.topicID 
        GROUP BY topic.categoryID ) as posts ON posts.topicID = topics.topicID 
    LEFT JOIN (
        SELECT categoryID, count(*) as numberOfLikes 
        FROM favouritecategory 
        GROUP BY categoryID) as likes ON likes.categoryID = category.categoryID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_get_description_of_the_site` (OUT `description` TEXT)  READS SQL DATA
SELECT aboutUs INTO description FROM descriptionofthesite$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_get_original_post` (IN `topicIdentification` INT, IN `offsetValue` INT)  READS SQL DATA
SELECT user.userID, user.username, post.postID, post.text FROM post INNER JOIN user ON user.userID = post.userID WHERE topicID = topicIdentification ORDER BY postID LIMIT offsetValue, 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_get_selected_post` (IN `postIdentification` INT)  READS SQL DATA
SELECT post.postID, post.text, post.postedOn, post.replyID, post.attachedFilesCode as postAttachedFilesCode, topic.topicID, topic.topicName, topic.topicText, topic.createdAt, topic.attachedFilesCode as topicAttachedFilesCode, topic.CategoryID, category.categoryID, category.categoryName, topicCreatorID, topicCreatorName, topicCreatorImage, topicCreatorRankID, topicCreatorRankColor, post.attachedFilesCode, postWriterID, postWriterName, postWriterImage, postWriterRankColor, postWriterRankID, originalPostID, originalUserID, originalUsername, postIdInTopic
 FROM post
 INNER JOIN (
     SELECT userID as postWriterID, username as postWriterName, profileImage as postWriterImage, rank.rankID as postWriterRankID, rankColor as postWriterRankColor
     FROM user INNER JOIN rank ON rank.rankID = user.rankID) as postWriter ON postWriter.postWriterID = post.userID
 INNER JOIN topic ON topic.topicID = post.topicID
 INNER JOIN category ON category.categoryID = topic.CategoryID
 INNER JOIN (
     SELECT userID as topicCreatorID, username as topicCreatorName, profileImage as topicCreatorImage, rank.rankID as topicCreatorRankID, rankColor as topicCreatorRankColor
     FROM user 
     INNER JOIN rank ON rank.rankID = user.rankID) as topicCreator ON topicCreator.topicCreatorID = topic.createdBy
 LEFT JOIN (
     SELECT post.postID as originalPostID, post.text as originalPostText, originalUserID, originalUsername
     FROM post
     INNER JOIN (
         SELECT userID as originalUserID, username as originalUserName
         FROM user) 
         as originalUser ON originalUser.originalUserID = post.userID
     ) as originalPost ON originalPost.originalPostID = post.replyID
INNER JOIN (
    SELECT topicID, count(*) as postIdInTopic
    FROM post 
    WHERE postID <= postIdentification
    GROUP BY topicID
    ) as postIdInTopic ON postIdInTopic.topicID = topic.topicID
 WHERE postID = postIdentification$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `accesslevel`
--

CREATE TABLE `accesslevel` (
  `accessLevelID` int(11) NOT NULL,
  `accessLevelTitle` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `accesslevel`
--

INSERT INTO `accesslevel` (`accessLevelID`, `accessLevelTitle`) VALUES
(0, 'Not verified'),
(1, 'Regular'),
(2, 'Blocked'),
(3, 'Suspended'),
(4, 'Anonymous'),
(18, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `badge`
--

CREATE TABLE `badge` (
  `badgeID` int(11) NOT NULL,
  `badgeName` varchar(255) COLLATE utf8_bin NOT NULL,
  `badgeDescription` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `badge`
--

INSERT INTO `badge` (`badgeID`, `badgeName`, `badgeDescription`) VALUES
(1, 'Autobiographer', 'Complete \"About Me\" section of user profile'),
(2, 'Commentator', 'Leave 10 comments'),
(3, 'Refiner', 'Answer 50 questions'),
(4, 'Fanatic', 'Visit the site each day for 100 consecutive day'),
(5, 'Curious', 'Ask a received question on 5 separate days'),
(6, 'Enthusiast', 'Visit the site each day for 30 consecutive days'),
(7, 'Supporter', 'First up vote'),
(8, 'Critic', 'First down vote'),
(9, 'Celeb', 'Get 25 followers for one of your topics'),
(10, 'Reliable', 'Get 25 likes for one of your posts');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(255) COLLATE utf8_bin NOT NULL,
  `categoryDescription` text COLLATE utf8_bin NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryID`, `categoryName`, `categoryDescription`, `thumbnail`) VALUES
(1, 'Everyday Life', 'Here you can talk about everything besides school with the schoolmates.', 'everydayLife.jpg'),
(2, 'Multimedia Design', '\"The education as a multimedia designer empowers you to solve dissemination tasks in both digital and print media, and you learn to develop and design web solutions.\"\r\n<br><br>\r\nHere you can get answer for your questions about exams, homeworks, teachers etc. as multimedia design student.', 'multimedia.jpg'),
(3, 'Web Development', '\"With an education in web development, you can build on your education as a multimedia designer or computer technician . The education teaches you to analyze and apply systems, such as XML and CMS.\"\r\n<br><br>\r\nHere you can get answer for your questions about exams, homeworks, teachers etc. as web development student.', 'webDesign.jpg'),
(4, 'Marketing', '\"The education as a marketing economist gives you a broad knowledge of a company\'s many work functions with the emphasis on marketing.\"\r\n<br><br>\r\nHere you can get answer for your questions about exams, homeworks, teachers etc. as marketing student.', 'marketing.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `contactinformation`
--

CREATE TABLE `contactinformation` (
  `ID` int(11) NOT NULL,
  `generalText` varchar(255) COLLATE utf8_bin NOT NULL,
  `phoneNumber` varchar(255) COLLATE utf8_bin NOT NULL,
  `location` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `contactinformation`
--

INSERT INTO `contactinformation` (`ID`, `generalText`, `phoneNumber`, `location`) VALUES
(1, 'If you have any question or problem, or if you need some help, just feel free to contact us through this form.', '4550707036', 'Esbjerg');

-- --------------------------------------------------------

--
-- Table structure for table `defaultavatar`
--

CREATE TABLE `defaultavatar` (
  `avatarID` int(11) NOT NULL,
  `fileName` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `defaultavatar`
--

INSERT INTO `defaultavatar` (`avatarID`, `fileName`) VALUES
(1, 'avatar.jpg'),
(2, 'ben10.jpg'),
(3, 'borat.jpg'),
(4, 'breakingbad.png'),
(5, 'ghost.png'),
(6, 'ironman.PNG'),
(7, 'jenkins.PNG'),
(8, 'lady.png'),
(9, 'mrrobots.png');

-- --------------------------------------------------------

--
-- Table structure for table `descriptionofthesite`
--

CREATE TABLE `descriptionofthesite` (
  `ID` int(11) NOT NULL,
  `aboutUs` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `descriptionofthesite`
--

INSERT INTO `descriptionofthesite` (`ID`, `aboutUs`) VALUES
(1, 'OffTopic is a place where you can get connection with other students at EASV. There is a strong community where you find answers for your questions and find easily people with the same interest as you have.\r\n\r\n<p>On the site a \"Feel free to ask\" system is working. If you join us you will get access to create your own topic.</p>\r\n\r\n<p>OffTopic was established in 2018. Our aim to give a platform for the students where they can chat about school things and outdoor activities as well.</p>\r\n\r\n<p>Be part of this great community and experience the power of EASV.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `earnedbadge`
--

CREATE TABLE `earnedbadge` (
  `userID` int(11) NOT NULL,
  `badgeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `earnedbadge`
--

INSERT INTO `earnedbadge` (`userID`, `badgeID`) VALUES
(21, 1),
(21, 2),
(21, 3),
(21, 5);

-- --------------------------------------------------------

--
-- Table structure for table `favouritecategory`
--

CREATE TABLE `favouritecategory` (
  `userID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `favouritecategory`
--

INSERT INTO `favouritecategory` (`userID`, `categoryID`) VALUES
(21, 1),
(21, 3),
(31, 2);

-- --------------------------------------------------------

--
-- Table structure for table `favouritetopic`
--

CREATE TABLE `favouritetopic` (
  `userID` int(11) NOT NULL,
  `topicID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `favouritetopic`
--

INSERT INTO `favouritetopic` (`userID`, `topicID`) VALUES
(18, 7),
(21, 5),
(21, 6),
(21, 7),
(24, 3),
(24, 4),
(24, 5),
(24, 6),
(24, 7),
(28, 5),
(28, 7),
(29, 7),
(30, 5),
(31, 6),
(35, 2);

-- --------------------------------------------------------

--
-- Table structure for table `period`
--

CREATE TABLE `period` (
  `periodID` int(11) NOT NULL,
  `periodName` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `period`
--

INSERT INTO `period` (`periodID`, `periodName`) VALUES
(1, 'none'),
(2, 'first'),
(3, 'second'),
(4, 'third'),
(5, 'fourth'),
(6, 'fifth'),
(7, 'sixth'),
(8, 'seventh');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `postID` int(11) NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `postedOn` datetime NOT NULL,
  `replyID` int(11) DEFAULT NULL,
  `userID` int(11) NOT NULL,
  `topicID` int(11) NOT NULL,
  `attachedFilesCode` varchar(255) COLLATE utf8_bin NOT NULL,
  `isReported` tinyint(1) NOT NULL,
  `isSticky` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`postID`, `text`, `postedOn`, `replyID`, `userID`, `topicID`, `attachedFilesCode`, `isReported`, `isSticky`) VALUES
(79, '<p>Send Kim the link via email, preferably with the page on a live-server. Otherwise I guess just link him the github repository or a zip-file with all of the content :)</p>', '2018-04-22 17:54:14', NULL, 21, 7, 'Att1524412454', 0, 0),
(80, '<p>Thank you! :D There are still a couple of hours left. So I am going to work like crazy before I hand it in. What is his email btw? I don\'t think I have it.</p>', '2018-04-22 17:55:01', NULL, 30, 7, 'Att1524412501', 0, 0),
(83, '<p>His mail is kt@easv.dk. :)</p>', '2018-04-22 17:57:14', 2, 18, 7, 'Att1524412634', 0, 0),
(84, '<p>Tak for det!</p>', '2018-04-22 17:57:21', 3, 30, 7, 'Att1524412641', 0, 1),
(85, '<p><span style=\"color: rgb(33, 33, 33);\">Be at the canteen no later than 8:10 – the bus will leave 8:15. :) </span></p><p><br></p><p><span style=\"color: rgb(33, 33, 33);\">I guess we won\'t get any lunch because we will be back at the school around 14-ish.</span></p>', '2018-04-22 18:00:40', NULL, 28, 5, 'Att1524412840', 0, 0),
(86, '<p>Ohh thank you so much!</p>', '2018-04-22 18:00:52', 1, 29, 5, 'Att1524412852', 0, 0),
(87, '<p>I can’t see any reason why not?</p>', '2018-03-21 18:15:01', NULL, 21, 1, 'Att1524413701', 0, 0),
(88, '<p>Just worrying about duplicate entries...</p>', '2018-03-21 18:21:43', 1, 18, 1, 'Att1524413743', 0, 0),
(89, '<p>True. I dont know if that would be a problem. But my guess is that its ok?</p>', '2018-03-21 20:16:10', 2, 21, 1, 'Att1524413770', 0, 0),
(90, '<p>Yeah, that\'s no problem, if you need the primary key to get some specific info from other tables it is fine.</p>', '2018-03-22 18:16:34', NULL, 24, 1, 'Att1524413794', 0, 0),
(91, '<p>Nope, Can\'t see any problem doing that.</p>', '2018-03-23 18:17:01', NULL, 29, 1, 'Att1524413821', 0, 0),
(95, '<p>I have the same problem maybe its the server now..</p>', '2017-12-11 18:31:34', NULL, 21, 8, 'Att1524414694', 0, 0),
(96, '<p><span style=\"color: rgb(114, 114, 114);\">But we can find a good place to eat all together after the event</span></p>', '2018-04-22 20:15:09', 2, 28, 5, 'Att1524428109', 1, 0),
(97, '<p>I guess you missed it :D</p>', '2018-04-22 20:22:04', NULL, 28, 2, 'Att1524428524', 0, 0),
(98, '<p>Hey! We are three hungarian cool guys now. If you want to join, feel free!</p>', '2018-04-22 20:22:18', NULL, 31, 6, 'Att1524428538', 0, 0),
(99, '<p>Oh, sounds like good, I want to join!</p>', '2018-04-22 20:24:53', 1, 28, 6, 'Att1524428693', 0, 0),
(101, '<p>Was the event good?</p>', '2018-04-26 22:23:46', NULL, 21, 5, 'Att1524774226', 0, 0),
(102, '<p>Somebody wants to practise??</p>', '2018-04-26 23:42:21', 2, 21, 6, 'Att1524778941', 0, 0),
(103, '<p>Anybody is at the afterparty?</p>', '2018-05-09 22:29:53', NULL, 39, 6, 'Att1525897793', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `postattachment`
--

CREATE TABLE `postattachment` (
  `postAttachmentID` int(11) NOT NULL,
  `postAttachmentName` varchar(255) COLLATE utf8_bin NOT NULL,
  `postAttachmentDisplayName` varchar(255) COLLATE utf8_bin NOT NULL,
  `postAttachedFileCode` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `postlike`
--

CREATE TABLE `postlike` (
  `userID` int(11) NOT NULL,
  `postID` int(11) NOT NULL,
  `isLike` tinyint(1) NOT NULL,
  `isDislike` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `postlike`
--

INSERT INTO `postlike` (`userID`, `postID`, `isLike`, `isDislike`) VALUES
(18, 79, 1, 0),
(18, 89, 1, 0),
(18, 90, 1, 0),
(18, 91, 1, 0),
(21, 80, 1, 0),
(21, 83, 1, 0),
(21, 85, 1, 0),
(28, 80, 0, 1),
(28, 86, 1, 0),
(29, 85, 1, 0),
(29, 87, 0, 1),
(30, 79, 1, 0),
(30, 83, 1, 0),
(30, 85, 1, 0),
(31, 86, 0, 1),
(31, 99, 1, 0),
(38, 96, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `rank`
--

CREATE TABLE `rank` (
  `rankID` int(11) NOT NULL,
  `rankTitle` varchar(255) COLLATE utf8_bin NOT NULL,
  `rankColor` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `rank`
--

INSERT INTO `rank` (`rankID`, `rankTitle`, `rankColor`) VALUES
(1, 'none', 'none'),
(2, 'bronze', '#A57164'),
(3, 'silver', '#A9A9A9'),
(4, 'gold', '#CFB53B');

-- --------------------------------------------------------

--
-- Table structure for table `ruleandregulation`
--

CREATE TABLE `ruleandregulation` (
  `ID` int(11) NOT NULL,
  `generalTxt` text COLLATE utf8_bin NOT NULL,
  `acceptanceOfTerms` text COLLATE utf8_bin NOT NULL,
  `modificationOfTerms` text COLLATE utf8_bin NOT NULL,
  `rulesAndConduct` text COLLATE utf8_bin NOT NULL,
  `termination` text COLLATE utf8_bin NOT NULL,
  `integration` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `ruleandregulation`
--

INSERT INTO `ruleandregulation` (`ID`, `generalTxt`, `acceptanceOfTerms`, `modificationOfTerms`, `rulesAndConduct`, `termination`, `integration`) VALUES
(1, 'PLEASE READ THESE TERMS OF USE (\"AGREEMENT\" OR \"TERMS OF USE\") CAREFULLY BEFORE USING THE SERVICES OFFERED BY OFF-TOPIC. THIS AGREEMENT SETS FORTH THE LEGALLY BINDING TERMS AND CONDITIONS FOR YOUR USE OF THE WEBSITE AT HTTP://OFF-TOPIC.TK (THE \"SITE\") AND THE SERVICE OWNED AND OPERATED BY OFF-TOPIC (COLLECTIVELY WITH THE SITE, THE \"SERVICE\"). BY USING THE SITE OR SERVICE IN ANY MANNER, INCLUDING BUT NOT LIMITED TO VISITING OR BROWSING THE SITE, YOU AGREE TO BE BOUND BY THIS AGREEMENT. THIS AGREEMENT APPLIES TO ALL USERS OF THE SITE OR SERVICE, INCLUDING USERS WHO ARE ALSO CONTRIBUTORS OF CONTENT, INFORMATION, AND OTHER MATERIALS OR SERVICES ON THE SITE.', 'The Service is offered subject to acceptance without modification of all of the terms and conditions contained herein (the \"Terms of Use\"), which terms also incorporates Off-topic\'s Privacy Policy, Off-topic\'s Copyright Policy and all other operating rules, policies and procedures that may be published from time to time on the Site by Off-topic, each of which is incorporated by reference and each of which may be updated by Off-topic from time to time without notice to you. In addition, some services offered through the Service may be subject to additional terms and conditions promulgated by Off-topic from time to time; your use of such services is subject to those additional terms and conditions, which are incorporated into these Terms of Use by this reference.\r\n\r\nThe Service is available only to individuals who are at least 13 years old. You represent and warrant that if you are an individual, you are of legal age to form a binding contract, and that all registration information you submit is accurate and truthful. Off-topic may, in its sole discretion, refuse to offer the Service to any person or entity and change its eligibility criteria at any time. This provision is void where prohibited by law and the right to access the Service is revoked in such jurisdictions.\r\n', 'Off-topic reserves the right, at its sole discretion, to modify or replace any of the Terms of Use, or change, suspend, or discontinue the Service (including without limitation, the availability of any feature, database, or content) at any time by posting a notice on the Site or by sending you an email. Off-topic may also impose limits on certain features and services or restrict your access to parts or all of the Service without notice or liability. It is your responsibility to check the Terms of Use periodically for changes. Your continued use of the Service following the posting of any changes to the Terms of Use constitutes acceptance of those changes.\r\n', 'As a condition of use, you promise not to use the Service for any purpose that is prohibited by the Terms of Use. The Service (including, without limitation, any Content or User Submissions (both as defined below)) is provided only for your own personal, non-commercial use. You are responsible for all of your activity in connection with the Service.\r\n\r\nFor purposes of the Terms of Use, the term \"Content\" includes, without limitation, any User Submissions, videos, audio clips, written forum comments, information, data, text, photographs, software, scripts, graphics, and interactive features generated, provided, or otherwise made accessible by Off-topic.\r\n\r\nBy way of example, and not as a limitation, you shall not (and shall not permit any third party to) either (a) take any action or (b) upload, download, post, submit or otherwise distribute or facilitate distribution of any Content on or through the Service, including without limitation any User Submission, that:\r\n\r\ninfringes any patent, trademark, trade secret, copyright, right of publicity or other right of any other person or entity or violates any law or contractual duty;\r\n\r\nyou know is false, misleading, untruthful or inaccurate;\r\n\r\nis unlawful, threatening, abusive, harassing, defamatory, libelous, deceptive, fraudulent, invasive of another\'s privacy, tortious, obscene, offensive, or profane;\r\n\r\nconstitutes unauthorized or unsolicited advertising, junk or bulk e-mail (\"spamming\");\r\n\r\ninvolves commercial activities and/or sales without Off-topic\'s prior written consent such as contests, sweepstakes, barter, advertising, or pyramid schemes;\r\n\r\ncontains software viruses or any other computer codes, files, or programs that are designed or intended to disrupt, damage, limit or interfere with the proper function of any software, hardware, or telecommunications equipment or to damage or obtain unauthorized access to any system, data, password or other information of Off-topic; or\r\n\r\nimpersonates any person or entity, including any employee or representative of Off-topic.\r\n\r\nAdditionally, you shall not: (i) take any action that imposes or may impose (as determined by Off-topic in its sole discretion) an unreasonable or disproportionately large load on Off-topic\'s (or its third party providers\') infrastructure; (ii) interfere or attempt to interfere with the proper working of the Service or any activities conducted on the Service; (iii) bypass any measures Off-topic may use to prevent or restrict access to the Service (or other accounts, computer systems or networks connected to the Service); (iv) run any form of auto-responder or \"spam\" on the Service; or (v) use manual or automated software, devices, or other processes to \"crawl\" or \"spider\" any page of the Site.\r\n\r\nYou shall not (directly or indirectly): (i) decipher, decompile, disassemble, reverse engineer or otherwise attempt to derive any source code or underlying ideas or algorithms of any part of the Service, except to the limited extent applicable laws specifically prohibit such restriction, (ii) modify, translate, or otherwise create derivative works of any part of the Service, or (iii) copy, rent, lease, distribute, or otherwise transfer any of the rights that you receive hereunder. You shall abide by all applicable local, state, national and international laws and regulations.\r\n\r\nOff-topic does not guarantee that any Content or User Submissions (as defined below) will be made available on the Site or through the Service. Off-topic has no obligation to monitor the Site, Service, Content, or User Submissions. However, Off-topic reserves the right to (i) remove, edit or modify any Content in its sole discretion, including without limitation any User Submissions, from the Site or Service at any time, without notice to you and for any reason (including, but not limited to, upon receipt of claims or allegations from third parties or authorities relating to such Content or if Off-topic is concerned that you may have violated the Terms of Use), or for no reason at all and (ii) to remove or block any User Submissions from the Service.\r\n', 'Off-topic may terminate your access to all or any part of the Service at any time, with or without cause, with or without notice, effective immediately, which may result in the forfeiture and destruction of all information associated with your membership. If you wish to terminate your account, you may do so by following the instructions on the Site. Any fees paid hereunder are non-refundable. All provisions of the Terms of Use which by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity and limitations of liability.', 'The Terms of Use are the entire agreement between you and Off-topic with respect to the Service and use of the Site, and supersede all prior or contemporaneous communications and proposals (whether oral, written or electronic) between you and Off-topic with respect to the Site. If any provision of the Terms of Use is found to be unenforceable or invalid, that provision will be limited or eliminated to the minimum extent necessary so that the Terms of Use will otherwise remain in full force and effect and enforceable. The failure of either party to exercise in any respect any right provided for herein shall not be deemed a waiver of any further rights hereunder.');

-- --------------------------------------------------------

--
-- Table structure for table `sidebarstickypost`
--

CREATE TABLE `sidebarstickypost` (
  `stickyPostID` int(11) NOT NULL,
  `stickyPostTitle` varchar(255) COLLATE utf8_bin NOT NULL,
  `stickyPostText` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `sidebarstickypost`
--

INSERT INTO `sidebarstickypost` (`stickyPostID`, `stickyPostTitle`, `stickyPostText`) VALUES
(1, 'Email Address Check', 'It is important to check periodically your email address provided in the <em>Settings</em> menu,  under <strong>My Profile</strong> page. In addition, we are increasingly seeing that the mails we send do not or just come to the SPAM folder, so we recommend everyone to use gmail.com e-mail.');

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE `topic` (
  `topicID` int(11) NOT NULL,
  `topicName` varchar(255) COLLATE utf8_bin NOT NULL,
  `topicText` text COLLATE utf8_bin NOT NULL,
  `createdAt` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `attachedFilesCode` varchar(255) COLLATE utf8_bin NOT NULL,
  `isReported` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `topic`
--

INSERT INTO `topic` (`topicID`, `topicName`, `topicText`, `createdAt`, `createdBy`, `semester`, `categoryID`, `attachedFilesCode`, `isReported`) VALUES
(1, 'Database problem', 'Hey geniuses! Does anyone know if a primary key is allowed to be the foreign key of two tables?', '2018-03-20 12:14:20', 18, 3, 3, 'Att1427146501', 0),
(2, 'User research replacement class', 'I can\'t see in the class calendar when will be the user research replacement class. Trine said that it will be at April, so is there anyone who knows on which day should we go extra to the school?', '2018-04-06 09:41:27', 21, 3, 3, 'Att1427146502', 0),
(3, 'Ionic push notification problem', 'Hi, I would like to implement the push notification into my exam project, but every time I tried to call its API, it return with an error. I read a lot of tutorial but I couldn\'t figure out how can I solve the problem. So is there anyone who has the same problem? Or is there anyone who knows how can I solve it?', '2018-01-09 23:47:18', 18, 2, 3, 'Att1427146503', 0),
(4, 'Kunstrunden Southwest Jutland', 'Is there anyone who would like to join me to go along the exhibitions?', '2018-04-13 20:17:06', 28, 1, 1, 'Att1427146504', 0),
(5, 'Visit to the Tirpitz museum', 'When will depart the bus? When should I be there? Will we get lunch?', '2018-04-22 17:38:33', 29, 3, 2, 'Att1427146505', 0),
(6, 'KongeKampen \'18', 'Hi! I want to participate in the dodgeball competition, but I do not have a team. Is there anyone who has the same problem? Because we can create the best team together.', '2018-04-19 11:26:10', 28, 1, 1, 'Att1427146506', 0),
(7, 'Backend assignment ', 'Does anybody know where you can hand in your DWP for backend? How do you need to hand it in?? What file format?', '2018-04-21 13:12:41', 30, 3, 3, 'Att1427146507', 0),
(8, 'Interface Design Wiseflow problem', 'Hey guys, when I log in to WISEflow the Interface Design flow is not working- I\'m getting an error. Does anyone have the same problem? ', '2017-12-10 10:00:00', 28, 2, 3, 'Att1427146508', 0),
(12, 'Somebody table tennis?', 'Hi guys, \n\nin our dormitory there is an opportunity to play table tennis, somebody wants to join?\nIt would be great fun!', '2018-05-11 20:33:37', 39, 1, 1, 'Att1526063617', 1);

-- --------------------------------------------------------

--
-- Table structure for table `topicattachment`
--

CREATE TABLE `topicattachment` (
  `topicAttachmentID` int(11) NOT NULL,
  `topicAttachmentName` varchar(255) COLLATE utf8_bin NOT NULL,
  `topicAttachmentDisplayName` varchar(255) COLLATE utf8_bin NOT NULL,
  `topicattachedFileCode` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `birthdate` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `profileImage` varchar(255) COLLATE utf8_bin NOT NULL,
  `rankID` int(11) NOT NULL,
  `accessLevel` int(11) NOT NULL,
  `regDate` datetime NOT NULL,
  `visitors` int(11) DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `lastLoginDate` datetime DEFAULT NULL,
  `aboutMe` text COLLATE utf8_bin,
  `consecutiveVisit` int(11) DEFAULT NULL,
  `hadSuspendPeriod` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `email`, `username`, `password`, `birthdate`, `profileImage`, `rankID`, `accessLevel`, `regDate`, `visitors`, `location`, `lastLoginDate`, `aboutMe`, `consecutiveVisit`, `hadSuspendPeriod`) VALUES
(18, 'molakos@gmail.com', 'Dronning Margrethe', '$2y$08$hq4t0ecLRuOu1fAc6hRvGunr3TEuywWDhcODvuInBQrnos3Bje7xS', NULL, '1524411225_lady.png', 1, 2, '2018-03-30 20:53:10', 104, NULL, NULL, NULL, NULL, 1),
(21, 'uuuuu@uuuuu.hu', 'Hans', '$2y$08$6uEHct3JyS9nW/nJXDgIVuR.xENMn.nfHDRix8Y5e0W0enSIsLQ7K', 'January 15', '1527145807_width.jpg', 1, 1, '2018-04-04 20:12:10', 152, 'Esbjerg, Denmark', '2018-05-24 09:22:06', 'Something about meee', 1, 0),
(24, 'email@email.email', 'Peter05', '$2y$08$wDSBZn3Qd4iLkfgQrtK/ousEu630mw0dVstC64DKftyaG8u.hYGTy', NULL, '1523710699_ironman.png', 1, 2, '2018-04-10 17:35:27', 283, NULL, NULL, NULL, NULL, 1),
(28, 'johnny@gmail.com', 'Johnny', '$2y$08$wLIaex.lJF29ZG76Kz9z..NlvNchhcqnAB4c2tGglwOmczfdQwZjC', NULL, '1524428385_ghost.png', 1, 1, '2018-04-22 17:02:00', 26, NULL, '2018-05-14 13:27:00', NULL, NULL, 0),
(29, 'test@test.dk', 'Panda Bear', '$2y$08$pALYlhFTODAXdmNc4skITOslGNvzwjuin0uthWtlCMG3aJuVbNfUO', NULL, '1524412294_avatar.jpg', 1, 1, '2018-04-22 17:40:59', 87, NULL, NULL, NULL, NULL, 0),
(30, 'joergen@gmail.com', 'Morgen Jorgen', '$2y$08$02s03DkjVhVR5YDjMkOUT.Dhb6GYumS1fSN918nzhunGkijDWT1GC', NULL, '1523724211_breakingbad.png', 1, 1, '2018-04-22 17:50:00', 12, NULL, NULL, NULL, NULL, 0),
(31, 'erikagrebur@gmail.com', 'erikagrebur', '$2y$08$OKU7xJn4/FDwPGpOvBypH.GAbAKxOmp65nxcvRoDnqXPKEbUiN.1W', NULL, '1524428228_jenkins.PNG', 1, 2, '2018-04-22 19:25:48', 0, NULL, NULL, NULL, NULL, 1),
(34, 'molak8@gmail.com', 'moak', '$2y$08$HR4QrerI5hNbxmTFEJir1OUQHXWPYJPrRvXfYcQ1HBiDaVt9rIUk6', NULL, 'defaultAvatar.png', 1, 2, '2018-04-22 20:41:39', 6, NULL, NULL, NULL, NULL, 1),
(35, 'kt@easv.dk', 'kim', '$2y$08$2q3M5PvaGp5W/ZeCNHqGkeUggrK6RsgLL0b6OADA.oG0SKacvDi0.', NULL, '1524507326_Kim.jpg', 1, 2, '2018-04-23 18:12:31', 12, NULL, NULL, NULL, NULL, 1),
(36, 'ezegyuj@ezegyuj.hu', 'ezegyuj', '$2y$08$6liD0vriBL/CinkOYFNCYeBxk1GblFZZO5fvu9OxJFEFtBv9R921S', NULL, 'defaultAvatar.png', 1, 3, '2018-04-27 00:21:13', 4, NULL, '2018-05-15 13:25:44', NULL, NULL, 1),
(38, 'admin@off-topic.tk', 'admin', '$2y$08$/v7y8Fglw3IH/qjKVGMaq.hc.gguoxb5C7XnRHTp1dpQHXwYU.LgK', NULL, 'admin.png', 1, 18, '2018-05-09 20:40:50', 0, NULL, '2018-05-24 16:02:42', NULL, 0, 0),
(39, 'anonymus@off-topic.tk', 'Anonymous', '$2y$08$j8p3GI26M9Qv3RMI8vyAGuEibHXdwHFWjuAHtrtuWaYamFNUMYVYS', NULL, 'anonymous.png', 1, 4, '2018-05-09 20:43:13', 1, NULL, NULL, NULL, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accesslevel`
--
ALTER TABLE `accesslevel`
  ADD PRIMARY KEY (`accessLevelID`),
  ADD UNIQUE KEY `accessLevelID_2` (`accessLevelID`),
  ADD KEY `accessLevelID` (`accessLevelID`);

--
-- Indexes for table `badge`
--
ALTER TABLE `badge`
  ADD PRIMARY KEY (`badgeID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `contactinformation`
--
ALTER TABLE `contactinformation`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `defaultavatar`
--
ALTER TABLE `defaultavatar`
  ADD PRIMARY KEY (`avatarID`);

--
-- Indexes for table `descriptionofthesite`
--
ALTER TABLE `descriptionofthesite`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `earnedbadge`
--
ALTER TABLE `earnedbadge`
  ADD PRIMARY KEY (`userID`,`badgeID`),
  ADD KEY `badgeID` (`badgeID`);

--
-- Indexes for table `favouritecategory`
--
ALTER TABLE `favouritecategory`
  ADD PRIMARY KEY (`userID`,`categoryID`);

--
-- Indexes for table `favouritetopic`
--
ALTER TABLE `favouritetopic`
  ADD PRIMARY KEY (`userID`,`topicID`);

--
-- Indexes for table `period`
--
ALTER TABLE `period`
  ADD PRIMARY KEY (`periodID`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`postID`),
  ADD KEY `attachedFilesCode` (`attachedFilesCode`),
  ADD KEY `topicID` (`topicID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `postattachment`
--
ALTER TABLE `postattachment`
  ADD PRIMARY KEY (`postAttachmentID`),
  ADD KEY `postAttachedFileCode` (`postAttachedFileCode`);

--
-- Indexes for table `postlike`
--
ALTER TABLE `postlike`
  ADD PRIMARY KEY (`userID`,`postID`);

--
-- Indexes for table `rank`
--
ALTER TABLE `rank`
  ADD PRIMARY KEY (`rankID`);

--
-- Indexes for table `ruleandregulation`
--
ALTER TABLE `ruleandregulation`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `sidebarstickypost`
--
ALTER TABLE `sidebarstickypost`
  ADD PRIMARY KEY (`stickyPostID`);

--
-- Indexes for table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`topicID`),
  ADD KEY `fk_topic_period` (`semester`),
  ADD KEY `fk_topic_user` (`createdBy`),
  ADD KEY `attachedFilesCode` (`attachedFilesCode`),
  ADD KEY `fk_topic_category` (`categoryID`);

--
-- Indexes for table `topicattachment`
--
ALTER TABLE `topicattachment`
  ADD PRIMARY KEY (`topicAttachmentID`),
  ADD KEY `topicattachedFileCode` (`topicattachedFileCode`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `accessLevel` (`accessLevel`),
  ADD KEY `rankID` (`rankID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `badge`
--
ALTER TABLE `badge`
  MODIFY `badgeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contactinformation`
--
ALTER TABLE `contactinformation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `defaultavatar`
--
ALTER TABLE `defaultavatar`
  MODIFY `avatarID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `descriptionofthesite`
--
ALTER TABLE `descriptionofthesite`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `period`
--
ALTER TABLE `period`
  MODIFY `periodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `postID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `postattachment`
--
ALTER TABLE `postattachment`
  MODIFY `postAttachmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rank`
--
ALTER TABLE `rank`
  MODIFY `rankID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ruleandregulation`
--
ALTER TABLE `ruleandregulation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sidebarstickypost`
--
ALTER TABLE `sidebarstickypost`
  MODIFY `stickyPostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `topic`
--
ALTER TABLE `topic`
  MODIFY `topicID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `topicattachment`
--
ALTER TABLE `topicattachment`
  MODIFY `topicAttachmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `earnedbadge`
--
ALTER TABLE `earnedbadge`
  ADD CONSTRAINT `earnedbadge_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `earnedbadge_ibfk_2` FOREIGN KEY (`badgeID`) REFERENCES `badge` (`badgeID`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`topicID`) REFERENCES `topic` (`topicID`),
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `postattachment`
--
ALTER TABLE `postattachment`
  ADD CONSTRAINT `postattachment_ibfk_1` FOREIGN KEY (`postAttachedFileCode`) REFERENCES `post` (`attachedFilesCode`);

--
-- Constraints for table `topic`
--
ALTER TABLE `topic`
  ADD CONSTRAINT `fk_topic_category` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`),
  ADD CONSTRAINT `fk_topic_period` FOREIGN KEY (`semester`) REFERENCES `period` (`periodID`),
  ADD CONSTRAINT `fk_topic_user` FOREIGN KEY (`createdBy`) REFERENCES `user` (`userID`);

--
-- Constraints for table `topicattachment`
--
ALTER TABLE `topicattachment`
  ADD CONSTRAINT `topicattachment_ibfk_1` FOREIGN KEY (`topicattachedFileCode`) REFERENCES `topic` (`attachedFilesCode`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_accesslevel` FOREIGN KEY (`accessLevel`) REFERENCES `accesslevel` (`accessLevelID`),
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`rankID`) REFERENCES `rank` (`rankID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
