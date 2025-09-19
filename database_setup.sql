-- --------------------------------------------------------
-- No, Bad Ideas! - Database Setup Script --
-- Generated on: 2025-09-19 19:59:38 --
-- --------------------------------------------------------

--
-- Table structure for table `nobadideas_decks`
--
CREATE TABLE `nobadideas_decks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deck_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nobadideas_decks`
--
INSERT INTO `nobadideas_decks` (`id`, `title`, `image_url`, `created_at`, `deck_order`) VALUES ('1', 'Briefing', 'deck_68c54b3f408181.02766528.png', '2025-09-05 14:23:45', '10');
INSERT INTO `nobadideas_decks` (`id`, `title`, `image_url`, `created_at`, `deck_order`) VALUES ('3', 'Creative Ideation', 'deck_68c54b627768a7.69106185.png', '2025-09-05 14:23:45', '30');
INSERT INTO `nobadideas_decks` (`id`, `title`, `image_url`, `created_at`, `deck_order`) VALUES ('4', 'Creative Development', 'deck_68c54b52214465.95568049.png', '2025-09-05 14:23:45', '40');
INSERT INTO `nobadideas_decks` (`id`, `title`, `image_url`, `created_at`, `deck_order`) VALUES ('5', 'Production', 'deck_68c54b78a6bce3.07752377.png', '2025-09-05 14:23:45', '50');
INSERT INTO `nobadideas_decks` (`id`, `title`, `image_url`, `created_at`, `deck_order`) VALUES ('6', 'Strategy', 'deck_68c54bd6b139a5.38282402.png', '2025-09-05 14:40:23', '20');
INSERT INTO `nobadideas_decks` (`id`, `title`, `image_url`, `created_at`, `deck_order`) VALUES ('10', 'Media Boosts', 'deck_68c54e3188d627.16732392.png', '2025-09-12 04:05:01', '60');

--
-- Table structure for table `nobadideas_cards`
--
CREATE TABLE `nobadideas_cards` (
  `id` int NOT NULL AUTO_INCREMENT,
  `deck_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `deck_id` (`deck_id`),
  CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`deck_id`) REFERENCES `nobadideas_decks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nobadideas_cards`
--
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('45', '1', 'The Ghosting Client', 'Your main client contact has gone on annual leave without telling you.\n\nMISS A TURN!', '45.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('46', '1', 'The Moving Goalposts', 'The client adds a major new deliverable but \"the budget can\'t change.\"\n\nGO BACK 2 SPACES', '46.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('47', '1', 'The Budget \"Correction\"', 'The client\'s finance department has made a \"correction.\" The budget is now 30% smaller.\n\nGO BACK 3 SPACES', '47.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('48', '1', 'The Surprise Stakeholder', 'A new senior client you\'ve never met wants to be taken through everything from the beginning.\n\nGO BACK 2 SPACES', '48.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('49', '1', 'The Legal Red Flag', 'The client\'s legal team flags an issue with a core product claim, forcing a major rethink.\n\nGO BACK 1 SPACE', '49.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('50', '1', 'The Outdated Guidelines', 'The client sends over their brand guidelines... from 2008. The logo is pixelated.\n\nGO BACK 1 SPACE', '50.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('51', '1', 'The Key Player is Sick', 'The lead on the project is struck down with the flu and is out of action for a week.\n\nMISS A TURN!', '51.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('52', '1', 'The Procurement Negotiation', 'The client\'s procurement department wants to renegotiate your entire contract.\n\nGO BACK 2 SPACES', '52.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('53', '1', 'The Brief Explosion', 'The \"simple briefing\" is actually for a massive, complex new product launch that\'s due yesterday.\n\nGO BACK 3 SPACES', '53.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('54', '1', 'The Internal Disagreement', 'You discover the client team can\'t agree internally on what the brief is actually for.\n\nGO BACK 2 SPACES', '54.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('55', '6', 'The Contradictory Data', 'The media agency\'s data directly contradicts your research.\n\nGO BACK 2 SPACES', '55.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('56', '6', 'The Unconvinced Client', 'You present a powerful insight, but the client \"just doesn\'t feel it\" and asks for a different direction.\n\nGO BACK 3 SPACES', '56.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('57', '6', 'The Competitor Scoop', 'A competitor launches a campaign using the exact same insight you\'ve just uncovered.\n\nMISS A TURN!', '57.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('58', '6', 'The Focus Group Disaster', 'Your focus group goes completely off the rails and the session is useless.\n\nGO BACK 2 SPACES', '58.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('59', '6', 'The Sign-off Black Hole', 'The creative brief is finished, but it\'s now sitting in the client\'s inbox unsigned.\n\nGO BACK 1 SPACE', '59.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('60', '6', 'The Research Budget Cut', 'Halfway through, the client cuts the budget for strategic research.\n\nGO BACK 2 SPACES', '60.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('61', '6', 'The \"Personal\" Research', 'The client provides their own \"research\"... a survey of five of their friends.\n\nGO BACK 1 SPACE', '61.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('62', '6', 'The Boss Veto', 'The key insight is rejected because your client\'s boss doesn\'t personally agree with it.\n\nGO BACK 3 SPACES', '62.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('63', '6', 'The Urgent Pitch', 'Your strategist gets pulled onto a more urgent new business pitch.\n\nMISS A TURN!', '63.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('64', '6', 'The Data Delay', 'The media agency is late delivering their crucial audience data, holding everything up.\n\nGO BACK 1 SPACE', '64.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('65', '3', 'The CEO\'s \"Great Idea\"', 'The client\'s CEO saw an ad they loved and emails everyone saying \"let\'s do something like this!\"\n\nGO BACK 3 SPACES', '65.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('66', '3', 'The Creative Dead End', 'The team has hit a wall. Every idea feels small or off-brief.\n\nMISS A TURN!', '66.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('67', '3', 'The Partner Problem', 'The client\'s global partner agency has \"input\" on your creative territories.\n\nGO BACK 2 SPACES', '67.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('68', '3', '\"I\'ll Know It When I See It\"', 'You\'ve presented three distinct creative territories, but the client can\'t decide and asks for more.\n\nGO BACK 2 SPACES', '68.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('69', '3', 'The Media Plan Pivot', 'The media plan has changed. The TV spot you were ideating around is now a series of 6-second TikToks.\n\nGO BACK 1 SPACE', '69.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('70', '3', 'The Safe Choice', 'After much deliberation, the client chooses the safest, most boring idea on the table.\n\nGO BACK 1 SPACE', '70.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('71', '3', 'The Nephew\'s Idea', 'The client\'s partner\'s nephew has a \"really funny idea\" they want you to consider.\n\nGO BACK 2 SPACES', '71.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('72', '3', 'The Déjà Vu', 'You present a brilliant idea, and the client says \"our old agency showed us that three years ago.\"\n\nGO BACK 3 SPACES', '72.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('73', '3', 'The Internal Bloodbath', 'The internal review of the creative work is a mess of conflicting, unhelpful opinions.\n\nGO BACK 2 SPACES', '73.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('74', '3', 'The CD is Away', 'The Creative Director is on holiday and unavailable to review the work, stalling progress.\n\nMISS A TURN!', '74.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('75', '4', 'Death by a Thousand Cuts', 'The client sends a single document with vague, contradictory feedback from 12 stakeholders.\n\nGO BACK 3 SPACES', '75.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('76', '4', 'The Brand Guideline Police', 'The client\'s global brand team parachutes in and says your beautiful design is \"off-brand\".\n\nGO BACK 2 SPACES', '76.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('77', '4', 'The Pesky Pre-Test', 'The campaign pre-testing results come back as \"polarising,\" and the client gets spooked.\n\nMISS A TURN!', '77.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('78', '4', 'The Disappearing Feature', 'The key product feature your campaign is built around has been delayed and won\'t be ready for launch.\n\nGO BACK 3 SPACES', '78.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('79', '4', 'The Re-Brief in Disguise', 'After seeing the creative, the client says \"This has made me realise we\'re trying to solve a different problem.\"\n\nGO BACK 2 SPACES', '79.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('80', '4', 'The Surprise Partner', 'You discover the client\'s spouse, who you\'ve never met, is the ultimate decision-maker and they hate it.\n\nGO BACK 3 SPACES', '80.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('81', '4', 'The Creative Clash', 'The copywriter and art director have a massive falling out and refuse to work together.\n\nMISS A TURN!', '81.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('82', '4', '\"Just One More Option\"', 'The campaign is signed off, but the client calls and asks \"Can we just see one more option?\"\n\nGO BACK 2 SPACES', '82.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('83', '4', 'The Overloaded Designer', 'The designer is swamped with other work and can only deliver placeholder layouts.\n\nGO BACK 1 SPACE', '83.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('84', '4', 'The Legal Edit', 'The client\'s legal team has rewritten all your exciting headlines into boring, risk-averse legalese.\n\nGO BACK 2 SPACES', '84.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('85', '5', 'The On-Set \"Plussing\"', 'The client shows up to the shoot and starts giving the director \"ideas\", blowing the schedule.\n\nGO BACK 2 SPACES', '85.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('86', '5', 'The Licensing Nightmare', 'The perfect song for your ad is impossible to license within budget.\n\nMISS A TURN!', '86.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('87', '5', '\"Just One More Thing...\"', 'The final assets are delivered, and the client wants \"one tiny change\" that requires re-doing everything.\n\nGO BACK 3 SPACES', '87.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('88', '5', 'The Media Owner Rejection', 'A major TV network rejects your ad for a code violation, days before going live.\n\nGO BACK 1 SPACE', '88.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('89', '5', 'The Wardrobe Malfunction', 'The custom-made outfits for the talent don\'t fit, and the shoot is on hold.\n\nGO BACK 2 SPACES', '89.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('90', '5', 'The Monsoon', 'The weather forecast for your big outdoor shoot is a week-long monsoon.\n\nGO BACK 2 SPACES', '90.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('91', '5', 'The Sick Actor', 'The lead actor for the shoot gets a sudden case of food poisoning and is out of action.\n\nMISS A TURN!', '91.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('92', '5', 'The Continuity Error', 'Post-production finds a massive, glaring continuity error in the hero shot of the ad.\n\nGO BACK 2 SPACES', '92.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('93', '5', 'The Hated Voiceover', 'The client decides they hate the voiceover artist you\'ve already recorded and paid for.\n\nGO BACK 3 SPACES', '93.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('94', '5', 'The File Size Fail', 'The final video files are too large to send to the media owners and the deadline is today.\n\nGO BACK 1 SPACE', '94.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('95', '10', 'Early Audience Insights', 'Media agency shares a deep dive into audience habits, sparking an insight.\n\nMOVE FORWARD 2 SPACES', '95.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('96', '10', 'Innovative Format Suggestion', 'The media team suggests a new ad format that gets the creatives excited.\n\nMOVE FORWARD 3 SPACES', '96.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('97', '10', 'Proactive Partner Intro', 'Your media planner makes an early intro to the perfect production partner.\r\n\r\nMOVE FORWARD ONE SPACE', '97.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('98', '10', 'Data-backed Creative Angle', 'The media agency provides data that validates a risky creative idea.\n\nMOVE FORWARD 3 SPACES', '98.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('99', '10', 'Budget-saving Media Buy', 'A fantastic media deal frees up budget for higher production values.\n\nMOVE FORWARD 2 SPACES', '99.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('100', '10', 'Contextual Creative Briefing', 'The media team provides a rich overview of the ad environments, helping the creatives.\n\nMOVE FORWARD 1 SPACE', '100.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('101', '10', 'Pre-emptive Problem Solving', 'The media agency flags a potential issue with an ad policy, saving you a headache.\r\n\r\nMOVE FORWARD THREE SPACES', '101.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('102', '10', 'Collaborative Brainstorm', 'A media planner joins a creative brainstorm and unlocks a new angle.\n\nMOVE FORWARD 2 SPACES', '102.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('103', '10', 'Fast-tracked Approval', 'Thanks to a strong relationship, the media agency gets a tricky ad concept approved fast.\n\nMOVE FORWARD 1 SPACE', '103.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('104', '10', '\"Added Value\" Win', 'The media agency secures significant \"added value\", giving your campaign extra reach at no extra cost.\n\nMOVE FORWARD 3 SPACES', '104.png', '2025-09-15 18:16:21');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('105', '10', 'Media Nuance', 'At the territory stage, the media team offers top-line extension thoughts that excite the creatives.\n\nMOVE FORWARD 2 SPACES', '105.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('106', '10', 'The Right Connections', 'Your media planner facilitates a crucial conversation with a key media partner, bringing an idea to life.\r\n\r\nMOVE FORWARD TWO SPACES', '106.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('107', '10', 'Data-driven Activation', 'Media offers a clever API or data solution that makes a creative wish technically possible.\n\nMOVE FORWARD 2 SPACES', '107.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('108', '10', 'The Deadline Extension', 'The media team successfully negotiates a deadline extension on asset delivery when things are running late.\n\nHAVE ANOTHER TURN', '108.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('109', '10', 'The Effectiveness Champion', 'Media defends the need for a 60\" hero film, convincing the client to prioritise effectiveness over efficiency.\n\nMOVE FORWARD 3 SPACES', '109.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('110', '10', 'The Awards Push', 'The media team helps get a specific execution live just in time for an awards deadline.\n\nMOVE FORWARD 1 SPACE', '110.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('111', '10', 'The Results Story', 'Your media partner provides compelling results data that makes your awards paper undeniable.\n\nMOVE FORWARD 2 SPACES', '111.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('112', '10', 'Unified Front', 'Media helps sell in a brave creative idea by providing a strong, data-backed endorsement in a key client meeting.\n\nMOVE FORWARD 3 SPACES', '112.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('113', '10', 'One Framework', 'Media and Creative agree to use a single, consistent communications framework, making client conversations simpler.\n\nMOVE FORWARD 1 SPACE', '113.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('114', '10', 'The Alliance', 'The media team advocates for true collaboration with the client, strengthening the inter-agency partnership.\n\nHAVE ANOTHER TURN', '114.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('115', '10', 'The \"Crazy Idea\" Fund', 'Media finds a way to position a wild creative idea as a \"media activation,\" unlocking a new pot of money.\n\nMOVE FORWARD 2 SPACES', '115.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('116', '10', 'Ratio Rationalisation', 'Media helps calm client nerves over a scary production-to-media-spend ratio by showing how the asset will be used long-term.\n\nMOVE FORWARD 1 SPACE', '116.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('117', '10', 'United We Stand', 'Your media partner helps form an alliance to prevent budget from being siphoned off by other agencies.\n\nMOVE FORWARD 2 SPACES', '117.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('118', '10', 'Theoretical Backup', 'The media planner brings strong marketing theory and tools to the table, helping to sell in the right strategic approach.\n\nMOVE FORWARD 1 SPACE', '118.png', '2025-09-15 18:32:57');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('119', '4', 'Too long!', 'Media reveal there\'s no budget for the 60 second launch film after all and 30 is all we have.\n\nGO BACK TWO SPACES!', '119.png', '2025-09-15 18:45:29');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('120', '4', 'New people, new opinions!', 'A new Brand Manager is hired to replace the client who initially signed off the media plan. They now want to change... everything!\n\nGO BACK THREE SPACES!', '120.png', '2025-09-15 18:45:29');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('121', '4', 'Budget showdown!', 'Media offer the client a great partnership opportunity...but they want half the production budget.\n\nGO BACK TWO SPACES!', '121.png', '2025-09-15 18:45:29');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('122', '5', 'Forgot to hold!', 'Media sold in an amazing sponsorship idea without holding first. You dispatch bespoke creative and the whole sponsorship has gone to a competitor.\r\n\r\nMISS A TURN!', '122.png', '2025-09-15 18:45:29');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('123', '5', 'Team hungover', 'Final deadline for assets is the day after the Axis awards. No one is around to finalise delivery.\n\nMISS A TURN!', '123.png', '2025-09-15 18:45:29');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('124', '5', 'Wrong creative!', 'Typo in key number... catastrophically wrong material set live!\n\nGO BACK ONE SPACE!', '124.png', '2025-09-15 18:45:29');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('125', '5', 'Context is king!', 'Set contextual travel campaign live, only to have client message next to gruesome plane-crash headlines.\n\nGO BACK TWO SPACES!', '125.png', '2025-09-15 18:45:29');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('126', '5', 'Wrong specs!', 'Media supply the wrong digital specs and assets need to be recut.\n\nMISS A TURN!', '126.png', '2025-09-15 18:45:29');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('127', '5', 'Weather worries!', 'Terrible weather on shoot day with no back up planned.\n\nMISS A TURN!', '127.png', '2025-09-15 18:45:29');
INSERT INTO `nobadideas_cards` (`id`, `deck_id`, `title`, `description`, `image_url`, `created_at`) VALUES ('128', '5', 'Licensing fail!', 'Production company forgets to license footage for all channels!\n\nGO BACK THREE SPACES!', '128.png', '2025-09-15 18:45:29');

--
-- Table structure for table `nobadideas_roles`
--
CREATE TABLE `nobadideas_roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image_front` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Path to the card front image',
  `loves` text COLLATE utf8mb4_general_ci,
  `hates` text COLLATE utf8mb4_general_ci,
  `key_phase` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores the role cards for the No Bad Ideas game';

--
-- Dumping data for table `nobadideas_roles`
--
INSERT INTO `nobadideas_roles` (`id`, `title`, `image_front`, `loves`, `hates`, `key_phase`, `display_order`) VALUES ('1', 'Strategist', 'role_68c54ca1623964.20337472-strategist_role.png', 'Deep insights, clear data, surprising truths.', 'Vague briefs, gut feelings without evidence.', 'Strategy', '1');
INSERT INTO `nobadideas_roles` (`id`, `title`, `image_front`, `loves`, `hates`, `key_phase`, `display_order`) VALUES ('2', 'Creative Team', 'role_68c54cbb285be6.12651291-creative_team_role.png', 'Big ideas, brave clients, winning awards.', '\"Make the logo bigger,\" death by a thousand cuts.', 'Creative Ideation', '3');
INSERT INTO `nobadideas_roles` (`id`, `title`, `image_front`, `loves`, `hates`, `key_phase`, `display_order`) VALUES ('3', 'Creative Director', 'role_68c54ccd3b3a13.60731536-cd_role.png', 'Nurturing talent, selling a killer idea, industry respect.', 'Safe ideas, clients who play it safe, internal politics.', 'Creative Development', '2');
INSERT INTO `nobadideas_roles` (`id`, `title`, `image_front`, `loves`, `hates`, `key_phase`, `display_order`) VALUES ('4', 'Account Director', 'role_68c54ce0a16ba8.90243629-account_director_role.png', 'Happy clients, on-time delivery, clear communication.', 'Scope creep, surprising the client with bad news.', 'Briefing', '4');
INSERT INTO `nobadideas_roles` (`id`, `title`, `image_front`, `loves`, `hates`, `key_phase`, `display_order`) VALUES ('5', 'Producer', 'role_68c54cf49660c2.50224383-producer_role.png', 'A perfectly balanced budget, a smooth shoot, making the impossible happen.', 'Unrealistic expectations, last-minute changes.', 'Production', '5');
INSERT INTO `nobadideas_roles` (`id`, `title`, `image_front`, `loves`, `hates`, `key_phase`, `display_order`) VALUES ('6', 'Resource Manager', 'role_68c54d08b370b9.45282118-resource_manager_role.png', 'A perfectly optimized schedule, accurate timesheets.', 'Last-minute briefs for \"yesterday,\" people not tracking their time.', 'Briefing', '6');

--
-- Table structure for table `nobadideas_phases`
--
CREATE TABLE `nobadideas_phases` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image_front` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Path to the card front image',
  `what_goes_in` text COLLATE utf8mb4_general_ci,
  `what_comes_out` text COLLATE utf8mb4_general_ci,
  `pain_points` text COLLATE utf8mb4_general_ci,
  `lead_roles` text COLLATE utf8mb4_general_ci,
  `display_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores the phase cards for the No Bad Ideas game';

--
-- Dumping data for table `nobadideas_phases`
--
INSERT INTO `nobadideas_phases` (`id`, `title`, `image_front`, `what_goes_in`, `what_comes_out`, `pain_points`, `lead_roles`, `display_order`) VALUES ('1', 'Briefing', 'phase_68c54d2dc99e38.41493568-briefing_phase.png', 'A business problem from a client.', 'A signed-off Statement of Work and a clear brief.', 'Vague objectives, unrealistic budgets/timelines.', 'Account Director, Resource Manager.', '1');
INSERT INTO `nobadideas_phases` (`id`, `title`, `image_front`, `what_goes_in`, `what_comes_out`, `pain_points`, `lead_roles`, `display_order`) VALUES ('2', 'Strategy', 'phase_68c54d44183856.94397197-strategy_phase.png', 'The client brief and a lot of research.', 'An inspiring Creative Brief for the creative team.', 'Analysis paralysis, uninspiring insights.', 'Strategist, Account Director.', '2');
INSERT INTO `nobadideas_phases` (`id`, `title`, `image_front`, `what_goes_in`, `what_comes_out`, `pain_points`, `lead_roles`, `display_order`) VALUES ('3', 'Creative Ideation', 'phase_68c54d51b65696.97362862-creative_ideation_phase.png', 'The Creative Brief.', 'A \"Big Idea\" or creative territories to share with the client.', 'Running out of ideas, ideas that don\'t answer the brief.', 'Creative Team, Creative Director.', '3');
INSERT INTO `nobadideas_phases` (`id`, `title`, `image_front`, `what_goes_in`, `what_comes_out`, `pain_points`, `lead_roles`, `display_order`) VALUES ('4', 'Creative Development', 'phase_68c54d639d2059.22468619-creative_development_phase.png', 'The approved Big Idea.', 'The finished campaign creative (scripts, storyboards, layouts).', 'Endless rounds of client feedback, losing the magic of the idea.', 'Creative Director, Designer.', '4');
INSERT INTO `nobadideas_phases` (`id`, `title`, `image_front`, `what_goes_in`, `what_comes_out`, `pain_points`, `lead_roles`, `display_order`) VALUES ('5', 'Production', 'phase_68c54d783dedf0.30024792-production_phase.png', 'Signed-off campaign creative.', 'The final, physical campaign assets (the finished TV ad, etc.).', 'Budget blowouts, technical issues, client changing their mind on set.', 'Producer, Creative Team.', '5');

--
-- Table structure for table `nobadideas_settings`
--
CREATE TABLE `nobadideas_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `setting_value` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_name` (`setting_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nobadideas_settings`
--
INSERT INTO `nobadideas_settings` (`id`, `setting_name`, `setting_value`, `created_at`, `updated_at`) VALUES ('1', 'game_status', 'on', '2025-09-05 21:11:45', '2025-09-12 16:46:55');

