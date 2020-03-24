-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2019 at 08:32 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `video_status_app_buyer`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `username`, `password`, `email`, `image`) VALUES
(1, 'admin', 'admin', 'viaviwebtech@gmail.com', 'profile.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `cid` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_image` varchar(255) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`cid`, `category_name`, `category_image`, `status`) VALUES
(8, 'Music Videos', '60522_Music_1.jpg', 1),
(10, 'Comedy Videos', '52875_comedy.jpg', 1),
(11, 'Love Video Status', '95217_love.jpg', 1),
(12, 'ગુજરાતી વિડિયોઝ', '60999_gujarati_cat.png', 1),
(13, 'ਪੰਜਾਬੀ ਵੀਡੀਓ', '43590_punjabi.jpg', 1),
(14, 'Animated Videos', '17821_animation_1.jpg', 1),
(19, 'فيديوهات عربية', '93792_arabic.jpg', 1),
(20, 'हिंदी वीडियो', '56364_hindi_1.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comments`
--

CREATE TABLE `tbl_comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `comment_on` varchar(150) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contact_list`
--

CREATE TABLE `tbl_contact_list` (
  `id` int(11) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_subject` int(5) NOT NULL,
  `contact_msg` text NOT NULL,
  `created_at` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contact_sub`
--

CREATE TABLE `tbl_contact_sub` (
  `id` int(5) NOT NULL,
  `title` varchar(150) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_contact_sub`
--

INSERT INTO `tbl_contact_sub` (`id`, `title`, `status`) VALUES
(2, 'Suspend', 1),
(3, 'Other', 1),
(4, 'Transaction', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_follows`
--

CREATE TABLE `tbl_follows` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_like`
--

CREATE TABLE `tbl_like` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `unlike` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reports`
--

CREATE TABLE `tbl_reports` (
  `id` int(11) NOT NULL,
  `video_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `report` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `envato_buyer_name` varchar(255) NOT NULL,
  `envato_purchase_code` varchar(255) NOT NULL,
  `envato_buyer_email` varchar(150) NOT NULL,
  `envato_purchased_status` int(1) NOT NULL DEFAULT '0',
  `package_name` varchar(255) NOT NULL,
  `email_from` varchar(255) NOT NULL,
  `redeem_points` int(11) NOT NULL,
  `redeem_money` float(11,2) NOT NULL,
  `redeem_currency` varchar(255) NOT NULL,
  `minimum_redeem_points` int(11) NOT NULL,
  `onesignal_app_id` text NOT NULL,
  `onesignal_rest_key` text NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `app_logo` varchar(255) NOT NULL,
  `app_email` varchar(255) NOT NULL,
  `app_version` varchar(255) NOT NULL,
  `app_author` varchar(255) NOT NULL,
  `app_contact` varchar(255) NOT NULL,
  `app_website` varchar(255) NOT NULL,
  `app_description` text NOT NULL,
  `app_developed_by` varchar(255) NOT NULL,
  `app_privacy_policy` text NOT NULL,
  `api_page_limit` int(11) NOT NULL,
  `api_all_order_by` varchar(255) NOT NULL,
  `api_latest_limit` int(3) NOT NULL,
  `api_cat_order_by` varchar(255) NOT NULL,
  `api_cat_post_order_by` varchar(255) NOT NULL,
  `publisher_id` text NOT NULL,
  `interstital_ad` text NOT NULL,
  `registration_reward` int(255) NOT NULL,
  `app_refer_reward` int(255) NOT NULL,
  `video_views` int(255) NOT NULL,
  `video_add` int(11) NOT NULL,
  `like_video_points` int(11) NOT NULL,
  `download_video_points` int(11) NOT NULL,
  `registration_reward_status` varchar(255) NOT NULL DEFAULT 'true',
  `app_refer_reward_status` varchar(255) NOT NULL DEFAULT 'true',
  `video_views_status` varchar(255) NOT NULL DEFAULT 'true',
  `video_add_status` varchar(255) NOT NULL DEFAULT 'true',
  `like_video_points_status` varchar(255) NOT NULL DEFAULT 'false',
  `download_video_points_status` varchar(255) NOT NULL DEFAULT 'false',
  `other_user_video_status` varchar(10) NOT NULL,
  `other_user_video_point` varchar(10) NOT NULL,
  `interstital_ad_id` text NOT NULL,
  `interstital_ad_click` varchar(255) NOT NULL,
  `banner_ad` text NOT NULL,
  `banner_ad_id` text NOT NULL,
  `rewarded_video_ads` varchar(255) NOT NULL,
  `rewarded_video_ads_id` varchar(255) NOT NULL,
  `rewarded_video_click` int(3) NOT NULL DEFAULT '5',
  `app_faq` text NOT NULL,
  `payment_method1` varchar(255) NOT NULL,
  `payment_method2` varchar(255) NOT NULL,
  `payment_method3` varchar(255) NOT NULL,
  `payment_method4` varchar(255) NOT NULL,
  `watermark_on_off` varchar(255) NOT NULL DEFAULT 'false',
  `watermark_image` varchar(255) DEFAULT NULL,
  `spinner_opt` varchar(10) NOT NULL DEFAULT 'Enable',
  `spinner_limit` int(10) NOT NULL DEFAULT '1',
  `auto_approve` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `envato_buyer_name`, `envato_purchase_code`, `envato_buyer_email`, `envato_purchased_status`, `package_name`, `email_from`, `redeem_points`, `redeem_money`, `redeem_currency`, `minimum_redeem_points`, `onesignal_app_id`, `onesignal_rest_key`, `app_name`, `app_logo`, `app_email`, `app_version`, `app_author`, `app_contact`, `app_website`, `app_description`, `app_developed_by`, `app_privacy_policy`, `api_page_limit`, `api_all_order_by`, `api_latest_limit`, `api_cat_order_by`, `api_cat_post_order_by`, `publisher_id`, `interstital_ad`, `registration_reward`, `app_refer_reward`, `video_views`, `video_add`, `like_video_points`, `download_video_points`, `registration_reward_status`, `app_refer_reward_status`, `video_views_status`, `video_add_status`, `like_video_points_status`, `download_video_points_status`, `other_user_video_status`, `other_user_video_point`, `interstital_ad_id`, `interstital_ad_click`, `banner_ad`, `banner_ad_id`, `rewarded_video_ads`, `rewarded_video_ads_id`, `rewarded_video_click`, `app_faq`, `payment_method1`, `payment_method2`, `payment_method3`, `payment_method4`, `watermark_on_off`, `watermark_image`, `spinner_opt`, `spinner_limit`, `auto_approve`) VALUES
(1, '', '', '', 0, 'com.example.videostatus', 'info@viaviweb.in', 100, 1.00, 'USD', 1, '616c202c-140b-4be5-9ae5-59ad9562dc1e', 'MzY1Y2FlNGItYWNmNi00YmViLTgxMDctYzk0NzQxMzg4ODU4', 'Video Status App', 'ic_launcher.png', 'viaviwebtech@gmail.com', '1.0.0', 'Viavi Webtech', '+91 9227777522', 'www.viaviweb.com', '<p>As Viavi Webtech is finest offshore IT company which has expertise in the below mentioned all technologies and our professional, dedicated approach towards our work has always satisfied our clients as well as users. We have reached to this level because of the dedication and hard work of our 10+ years experienced team as well as new ideas of freshers, they always provide the best solutions. Here are the promising services served by Viavi Webtech.</p>\r\n\r\n<p>Contact on Skype &amp; Email for more information.</p>\r\n\r\n<p><strong>Skype ID:</strong> support.viaviweb <strong>OR</strong> viaviwebtech<br />\r\n<strong>Email:</strong> info@viaviweb.com <strong>OR</strong> viaviwebtech@gmail.com<br />\r\n<strong>Website:</strong> <a href=\"http://www.viaviweb.com\">http://www.viaviweb.com</a><br />\r\n<br />\r\nOur Products : <em><strong><a href=\"https://codecanyon.net/user/viaviwebtech/portfolio?ref=viaviwebtech\">CODECANYON</a></strong></em></p>\r\n', 'Viavi Webtech', '<p><strong>We are committed to protecting your privacy</strong></p>\n\n<p>We collect the minimum amount of information about you that is commensurate with providing you with a satisfactory service. This policy indicates the type of processes that may result in data being collected about you. Your use of this website gives us the right to collect that information.&nbsp;</p>\n\n<p><strong>Information Collected</strong></p>\n\n<p>We may collect any or all of the information that you give us depending on the type of transaction you enter into, including your name, address, telephone number, and email address, together with data about your use of the website. Other information that may be needed from time to time to process a request may also be collected as indicated on the website.</p>\n\n<p><strong>Information Use</strong></p>\n\n<p>We use the information collected primarily to process the task for which you visited the website. Data collected in the UK is held in accordance with the Data Protection Act. All reasonable precautions are taken to prevent unauthorised access to this information. This safeguard may require you to provide additional forms of identity should you wish to obtain information about your account details.</p>\n\n<p><strong>Cookies</strong></p>\n\n<p>Your Internet browser has the in-built facility for storing small files - &quot;cookies&quot; - that hold information which allows a website to recognise your account. Our website takes advantage of this facility to enhance your experience. You have the ability to prevent your computer from accepting cookies but, if you do, certain functionality on the website may be impaired.</p>\n\n<p><strong>Disclosing Information</strong></p>\n\n<p>We do not disclose any personal information obtained about you from this website to third parties unless you permit us to do so by ticking the relevant boxes in registration or competition forms. We may also use the information to keep in contact with you and inform you of developments associated with us. You will be given the opportunity to remove yourself from any mailing list or similar device. If at any time in the future we should wish to disclose information collected on this website to any third party, it would only be with your knowledge and consent.&nbsp;</p>\n\n<p>We may from time to time provide information of a general nature to third parties - for example, the number of individuals visiting our website or completing a registration form, but we will not use any information that could identify those individuals.&nbsp;</p>\n\n<p>In addition Dummy may work with third parties for the purpose of delivering targeted behavioural advertising to the Dummy website. Through the use of cookies, anonymous information about your use of our websites and other websites will be used to provide more relevant adverts about goods and services of interest to you. For more information on online behavioural advertising and about how to turn this feature off, please visit youronlinechoices.com/opt-out.</p>\n\n<p><strong>Changes to this Policy</strong></p>\n\n<p>Any changes to our Privacy Policy will be placed here and will supersede this version of our policy. We will take reasonable steps to draw your attention to any changes in our policy. However, to be on the safe side, we suggest that you read this document each time you use the website to ensure that it still meets with your approval.</p>\n\n<p><strong>Contacting Us</strong></p>\n\n<p>If you have any questions about our Privacy Policy, or if you want to know what information we have collected about you, please email us at hd@dummy.com. You can also correct any factual errors in that information or require us to remove your details form any list under our control.</p>\n', 5, 'DESC', 15, 'category_name', 'DESC', 'pub-9456493320432553', 'true', 5, 5, 1, 5, 2, 1, 'true', 'true', 'true', 'true', 'true', 'true', 'true', '1', 'ca-app-pub-3940256099942544/1033173712', '5', 'true', 'ca-app-pub-3940256099942544/6300978111', 'true', 'ca-app-pub-3940256099942544/5224354917', 3, '<p><strong>How to earn points in video status app?</strong></p>\r\n\r\n<p>- When user views,like,download video or upload video then they will get reward points.</p>\r\n\r\n<p>- Share your reference code to others and get reward points for every user registered with your reference code.</p>\r\n\r\n<p>- When user registers in application they will get reward points</p>\r\n\r\n<p><strong>Note:-</strong> When user will upload video and when admin approves user video, after that user will get reward points.</p>\r\n\r\n<p><strong>Video Upload Guidance :-</strong></p>\r\n\r\n<p>- Please check that uploading video file name is in english and there is no space in video file name.</p>\r\n\r\n<p>- Please follow the instruction of video file size, duration and format.</p>\r\n\r\n<p><strong>How to claim reward points and earn money?</strong></p>\r\n\r\n<p>- User need to acquire minimum points to claim money from reward points.</p>\r\n\r\n<p>- To claim money from reward points user have to fill the form and if there is any mistake in form you submitted, then you have to fill Contact Us form and admin will contact user ASAP</p>\r\n\r\n<p>- When admin approves user&#39;s claim for money after that user will get money</p>\r\n\r\n<p><strong>Note :-</strong></p>\r\n\r\n<p>- When you share video status to any social media app, the length of the video will be depended on the app you are sharing.</p>\r\n\r\n<p>- Any misbehavior or any type of sexual and unnecessary video upload will make admin block your account.</p>\r\n\r\n<p>- Allow the read and write file permission then you will be able to use download, upload and share the video feature otherwise you will not able to use.</p>\r\n\r\n<p>- Share video works only on supported social media applications.</p>\r\n\r\n<p>- User can select payment method when filling form to claim money.&nbsp;</p>\r\n\r\n<p>- User can upload video only in mp4 format</p>\r\n\r\n<p>- Spamming report video feature may lead to account ban.</p>\r\n', 'PayPal', 'PayTm', 'Bank Details', 'Other', 'true', 'watermark_1.png', 'true', 3, 'on');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_spinner`
--

CREATE TABLE `tbl_spinner` (
  `block_id` int(5) NOT NULL,
  `block_points` varchar(5) NOT NULL,
  `block_bg` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_spinner`
--

INSERT INTO `tbl_spinner` (`block_id`, `block_points`, `block_bg`) VALUES
(1, '0', 'BE4EBA'),
(2, '1', '0DABE9'),
(3, '2', 'E0E92D'),
(4, '3', 'E94C1E'),
(5, '4', 'E91E06'),
(6, '5', '0B8945');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_suspend_account`
--

CREATE TABLE `tbl_suspend_account` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `suspended_on` varchar(255) NOT NULL,
  `activated_on` int(11) DEFAULT NULL,
  `suspension_reason` text NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `user_code` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `device_id` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `user_image` varchar(500) DEFAULT NULL,
  `total_followers` int(11) NOT NULL DEFAULT '0',
  `total_following` int(11) NOT NULL DEFAULT '0',
  `user_youtube` varchar(500) DEFAULT NULL,
  `user_instagram` varchar(500) DEFAULT NULL,
  `confirm_code` varchar(255) DEFAULT NULL,
  `total_point` int(11) NOT NULL DEFAULT '0',
  `is_verified` int(1) NOT NULL DEFAULT '0',
  `player_id` text,
  `is_duplicate` int(1) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `user_code`, `user_type`, `device_id`, `name`, `email`, `password`, `phone`, `user_image`, `total_followers`, `total_following`, `user_youtube`, `user_instagram`, `confirm_code`, `total_point`, `is_verified`, `player_id`, `is_duplicate`, `status`) VALUES
(0, 'adminsi5', 'Admin', '', 'Admin', 'admin@admin.com', '123456', NULL, 'profile.png', 2, 0, NULL, NULL, NULL, 97, 1, '', 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_redeem`
--

CREATE TABLE `tbl_users_redeem` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_points` int(11) NOT NULL,
  `redeem_price` float(11,2) NOT NULL,
  `payment_mode` varchar(255) DEFAULT NULL,
  `bank_details` text NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cust_message` longtext,
  `receipt_img` text,
  `responce_date` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_rewards_activity`
--

CREATE TABLE `tbl_users_rewards_activity` (
  `id` int(10) NOT NULL,
  `user_id` int(255) NOT NULL,
  `video_id` int(255) NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `points` int(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `redeem_id` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users_rewards_activity`
--

INSERT INTO `tbl_users_rewards_activity` (`id`, `user_id`, `video_id`, `activity_type`, `points`, `date`, `redeem_id`, `status`) VALUES
(1, 0, 2, 'Add Video', 5, '2019-07-18 05:51:57', 0, 1),
(2, 0, 1, 'Add Video', 5, '2019-07-18 05:51:57', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_verify_user`
--

CREATE TABLE `tbl_verify_user` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `document` text NOT NULL,
  `created_at` varchar(150) NOT NULL,
  `verify_at` varchar(150) NOT NULL DEFAULT '0',
  `reject_reason` text,
  `is_opened` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_video`
--

CREATE TABLE `tbl_video` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `cat_id` int(11) NOT NULL,
  `video_type` varchar(255) NOT NULL,
  `video_title` varchar(255) NOT NULL,
  `video_url` text NOT NULL,
  `video_id` varchar(255) NOT NULL,
  `video_layout` varchar(255) NOT NULL DEFAULT 'Landscape',
  `video_thumbnail` text NOT NULL,
  `video_duration` varchar(255) DEFAULT NULL,
  `total_likes` int(11) NOT NULL DEFAULT '0',
  `totel_viewer` int(11) NOT NULL DEFAULT '0',
  `featured` int(1) DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_video`
--

INSERT INTO `tbl_video` (`id`, `user_id`, `cat_id`, `video_type`, `video_title`, `video_url`, `video_id`, `video_layout`, `video_thumbnail`, `video_duration`, `total_likes`, `totel_viewer`, `featured`, `status`) VALUES
(1, 0, 8, 'server_url', 'Tamil Music Video', 'http://www.viaviweb.in/envato/cc/demo_video/S_portrait_01.mp4', '', 'Portrait', '23716_13120_2.png', NULL, 0, 0, 0, 1),
(2, 0, 11, 'server_url', 'Tere Bina Jeena Saza Ho Gaya', 'http://www.viaviweb.in/envato/cc/demo_video/S_landscape3.mp4', '', 'Landscape', '33254_1978_2.png', NULL, 0, 0, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contact_list`
--
ALTER TABLE `tbl_contact_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contact_sub`
--
ALTER TABLE `tbl_contact_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_follows`
--
ALTER TABLE `tbl_follows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_like`
--
ALTER TABLE `tbl_like`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reports`
--
ALTER TABLE `tbl_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_spinner`
--
ALTER TABLE `tbl_spinner`
  ADD PRIMARY KEY (`block_id`);

--
-- Indexes for table `tbl_suspend_account`
--
ALTER TABLE `tbl_suspend_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users_redeem`
--
ALTER TABLE `tbl_users_redeem`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users_rewards_activity`
--
ALTER TABLE `tbl_users_rewards_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_verify_user`
--
ALTER TABLE `tbl_verify_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_video`
--
ALTER TABLE `tbl_video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_contact_list`
--
ALTER TABLE `tbl_contact_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_contact_sub`
--
ALTER TABLE `tbl_contact_sub`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_follows`
--
ALTER TABLE `tbl_follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_like`
--
ALTER TABLE `tbl_like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_reports`
--
ALTER TABLE `tbl_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_spinner`
--
ALTER TABLE `tbl_spinner`
  MODIFY `block_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tbl_suspend_account`
--
ALTER TABLE `tbl_suspend_account`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_users_redeem`
--
ALTER TABLE `tbl_users_redeem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_users_rewards_activity`
--
ALTER TABLE `tbl_users_rewards_activity`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_verify_user`
--
ALTER TABLE `tbl_verify_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_video`
--
ALTER TABLE `tbl_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
