-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2026 at 07:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
SET SESSION sql_require_primary_key = 0;
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gp2530_db`
--
-- --------------------------------------------------------
--
-- Table structure for table `admins`
--
CREATE TABLE `admins` (
  `admin_id` int(3) NOT NULL COMMENT 'Id of Admin',
  `admin_username` varchar(10) NOT NULL COMMENT 'Username of Admin',
  `admin_password` varchar(255) NOT NULL COMMENT 'Password of Admin'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--
INSERT INTO
  `admins` (`admin_id`, `admin_username`, `admin_password`)
VALUES
  (
    1,
    'admin',
    '$2y$10$OZpSk3jRQ7MbYMh4BK5Ao.B/U25FTB.4CEL5jUBdVMF6/Q9NU1YxG'
  ),
  (
    2,
    'sp_012',
    '$2y$10$ivc/BK32FeIlbVvavr7/..lsnZ/m4b/.xuRjAHymSWJNz.Qtu9DaO'
  );

-- --------------------------------------------------------
--
-- Table structure for table `bank_detail`
--
CREATE TABLE `bank_detail` (
  `bank_sr_no` int(3) NOT NULL COMMENT 'Serial Number',
  `user_username` varchar(20) NOT NULL COMMENT 'Username of User',
  `card_number` bigint(16) NOT NULL COMMENT 'Card Number of User',
  `card_expiry` text NOT NULL COMMENT 'Card Expiry Date',
  `card_cvv_code` int(3) NOT NULL COMMENT 'CVV Code of Card of User',
  `card_holder_name` varchar(25) NOT NULL COMMENT 'Debit or Credit Card Holder Name',
  `bank_account_number` bigint(15) NOT NULL COMMENT 'Bank Account Number of User',
  `bank_ifsc_code` varchar(15) NOT NULL COMMENT 'Bank Ifsc Code of User',
  `upi_id` varchar(15) NOT NULL COMMENT 'UPI Id of User',
  `upi_pin` bigint(6) NOT NULL COMMENT 'UPI Pin of User'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `bank_detail`
--
INSERT INTO
  `bank_detail` (
    `bank_sr_no`,
    `user_username`,
    `card_number`,
    `card_expiry`,
    `card_cvv_code`,
    `card_holder_name`,
    `bank_account_number`,
    `bank_ifsc_code`,
    `upi_id`,
    `upi_pin`
  )
VALUES
  (
    1,
    'raju123',
    1234567891234567,
    '11/29',
    786,
    'RAJU RASTOGI',
    95173842605,
    'SBIN0013383',
    'rajurastogi@upi',
    987321
  );

-- --------------------------------------------------------
--
-- Table structure for table `contact_us`
--
CREATE TABLE `contact_us` (
  `contact_id` int(3) NOT NULL COMMENT 'Serial Id of User Contact Request',
  `first_name` varchar(10) NOT NULL COMMENT 'Name of User',
  `last_name` varchar(10) NOT NULL COMMENT 'Surname of User',
  `contact_no` bigint(10) NOT NULL COMMENT 'Contact Number',
  `email_id` varchar(25) NOT NULL COMMENT 'Email-id of User',
  `subject` varchar(50) NOT NULL COMMENT 'Subject of User''s Query',
  `message` text NOT NULL COMMENT 'Message of User',
  `contact_request_date` datetime NOT NULL COMMENT 'User Contact Us Date'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--
INSERT INTO
  `contact_us` (
    `contact_id`,
    `first_name`,
    `last_name`,
    `contact_no`,
    `email_id`,
    `subject`,
    `message`,
    `contact_request_date`
  )
VALUES
  (
    1,
    'Smith',
    'Patel',
    8424939366,
    'smithsp5177@gmail.com',
    'About User Experience',
    'Best ui and user experince i have never seen in any other system like airbnb or etc..',
    '2025-09-26 17:31:43'
  );

-- --------------------------------------------------------
--
-- Table structure for table `feedback`
--
CREATE TABLE `feedback` (
  `feedback_id` int(3) NOT NULL COMMENT 'Serial Id of User Feedback Request',
  `first_name` varchar(10) NOT NULL COMMENT 'First Name of User',
  `last_name` varchar(10) NOT NULL COMMENT 'Last Name of User',
  `contact_no` bigint(10) NOT NULL COMMENT 'Contact Number',
  `email_id` varchar(25) NOT NULL COMMENT 'Email-id of User',
  `occupation` varchar(50) NOT NULL,
  `feedback_subject` varchar(25) NOT NULL COMMENT 'Subject of Feedback',
  `feedback_text` text NOT NULL COMMENT 'Feedback of User',
  `rating` int(1) NOT NULL COMMENT 'Rating of User out of 5',
  `feedback_share_date` datetime NOT NULL COMMENT 'User Feedback Sharing Date'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--
INSERT INTO
  `feedback` (
    `feedback_id`,
    `first_name`,
    `last_name`,
    `contact_no`,
    `email_id`,
    `occupation`,
    `feedback_subject`,
    `feedback_text`,
    `rating`,
    `feedback_share_date`
  )
VALUES
  (
    1,
    'Akash',
    'Verma',
    9876543210,
    'av123@gmail.com',
    'College Student',
    'Recommended',
    'I found a clean, affordable room in just a few clicks. Booking was fast, and I got exactly what was shown. Highly recommended!',
    4,
    '2025-04-04 13:06:15'
  ),
  (
    2,
    'Megha',
    'Sharma',
    8424939366,
    'ms3@gmail.com',
    'Marketing Executive',
    'Best Experience',
    'Needed a short stay in the city for a business trip. Found a well-maintained villa through this site. Hassle-free experience!',
    5,
    '2025-08-31 23:54:28'
  ),
  (
    3,
    'Rahul',
    'Jain',
    9765412398,
    'rahulj1@gmail.com',
    'Frequent Traveler',
    'Fast Process',
    'I booked a farmhouse for the weekend with friends. The process was smooth, and the place was exactly as described. Will book again!',
    5,
    '2025-09-01 00:17:49'
  ),
  (
    4,
    'Sneha',
    'Kapoor',
    9764825193,
    'snehak2@gmail.com',
    'Young Professional',
    'Easy to Use',
    'This site made it so easy to compare prices and amenities. I found the perfect rental within my budget in minutes!',
    2,
    '2025-09-01 00:19:32'
  ),
  (
    5,
    'Karan',
    'Malhotra',
    9716039542,
    'kmalhotra51@gmail.com',
    'Software Engineer',
    'Helpful',
    'I was new to the city and needed a place quickly. This platform helped me find a great room in a safe neighborhood. Super helpful!',
    1,
    '2025-09-01 00:24:37'
  ),
  (
    6,
    'Nikita',
    'Rao',
    9876547890,
    'nikita.rao89@gmail.com',
    'Graphic Designer',
    'User-Friendly',
    'The website interface is simple and intuitive. I was able to filter exactly what I needed and book without any issues.',
    4,
    '2025-09-01 10:05:12'
  ),
  (
    7,
    'Arjun',
    'Desai',
    9812345678,
    'arjun.d@gmail.com',
    'Consultant',
    'Smooth Booking',
    'Had a last-minute change in plans. This platform saved me with instant availability and secure booking.',
    5,
    '2025-09-01 10:18:44'
  ),
  (
    8,
    'Pooja',
    'Mehta',
    8887654321,
    'pooja.m@hotmail.com',
    'Interior Designer',
    'Could be Better',
    'Found a decent place, but the photos were slightly misleading. Otherwise, the process was okay.',
    3,
    '2025-08-30 21:42:10'
  ),
  (
    9,
    'Ravi',
    'Singh',
    9823001122,
    'ravi.singh91@gmail.com',
    'Entrepreneur',
    'Highly Efficient',
    'Within 10 minutes, I shortlisted 3 good options. The final property exceeded expectations!',
    5,
    '2025-08-09 16:30:55'
  ),
  (
    10,
    'Ananya',
    'Iyer',
    9797979797,
    'ananya_iyer@gmail.com',
    'Research Scholar',
    'Quick and Reliable',
    'Needed a short-term rental for research purposes. Found exactly what I needed quickly. Very reliable service.',
    4,
    '2025-03-18 11:22:33'
  ),
  (
    11,
    'Dev',
    'Patel',
    9811223344,
    'devpatel88@gmail.com',
    'Architect',
    'Not Satisfied',
    'Faced some issues during check-in. The support team was slow to respond. Not a great experience.',
    2,
    '2025-02-27 19:47:20'
  );

-- --------------------------------------------------------
--
-- Table structure for table `invoice`
--
CREATE TABLE `invoice` (
  `invoice_id` int(3) NOT NULL COMMENT 'Id of Invoice',
  `property_no` varchar(10) NOT NULL COMMENT 'Property No',
  `user_username` varchar(20) NOT NULL COMMENT 'Username of User',
  `first_name` varchar(10) NOT NULL COMMENT 'Name of User',
  `last_name` varchar(10) NOT NULL COMMENT 'Surname of User',
  `contact_no` bigint(10) NOT NULL COMMENT 'Contact Number',
  `email_id` varchar(25) NOT NULL COMMENT 'Email-id of User',
  `rent_amount` varchar(20) NOT NULL COMMENT 'Rent Amount of Payment',
  `rent_dmn` varchar(5) NOT NULL COMMENT 'Rent period type Day, Month and Night',
  `rent_period` int(2) NOT NULL COMMENT 'Rent Period',
  `rent_date` datetime NOT NULL COMMENT 'Rent Period Starting Date',
  `rent_end_date` datetime DEFAULT NULL COMMENT 'Rent Period End Date',
  `verification_doc_no` varchar(20) NOT NULL COMMENT 'Verification Document like Photo Id, Pan Card Number',
  `booking_date` datetime NOT NULL COMMENT 'User Booking Date',
  `transaction_id` varchar(25) NOT NULL COMMENT 'Transaction id of Payment',
  `payment_mode` varchar(25) NOT NULL COMMENT 'Mode of Payment',
  `payment_date` datetime NOT NULL COMMENT 'Date of Payment'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `migrations`
--
-- Error reading structure for table gp2530_db.migrations: #1932 - Table &#039;gp2530_db.migrations&#039; doesn&#039;t exist in engine
-- Error reading data for table gp2530_db.migrations: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `gp2530_db`.`migrations`&#039; at line 1
-- --------------------------------------------------------
--
-- Table structure for table `past_users`
--
CREATE TABLE `past_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_username` varchar(100) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(32) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `contact_no` varchar(32) DEFAULT NULL,
  `email_id` varchar(255) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(32) DEFAULT NULL,
  `deleted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `past_users`
--
INSERT INTO
  `past_users` (
    `id`,
    `user_username`,
    `first_name`,
    `last_name`,
    `user_password`,
    `dob`,
    `gender`,
    `address`,
    `city`,
    `contact_no`,
    `email_id`,
    `reg_date`,
    `state`,
    `pincode`,
    `deleted_at`
  )
VALUES
  (
    1,
    'hetu123',
    'Hetkashi',
    'Davda',
    '$2y$10$RexsTvl.539VUvZqHQ8dl.BTBzoJUFza0E.Uc6Fw.NOZkaVg55qW.',
    '2003-12-01',
    'Female',
    'Bhavnagar',
    'Bhavnagar',
    '8424939366',
    'hetu123@gmail.com',
    '2025-11-26 11:59:21',
    'Gujarat',
    '364001',
    '2025-11-26 12:20:57'
  ),
  (
    2,
    'hetu123',
    'Hetakshi',
    'Davda',
    '$2y$10$7uBUBfGUhYAO6nN39QYdSOBHllvreXHJyFF8LoxC.O/7y8/6QJgWW',
    '2003-12-01',
    'Female',
    'Rajkot',
    'Bhavnagar',
    '8424939366',
    'hetu123@gmail.com',
    '2025-11-26 18:12:59',
    'Gujarat',
    '364001',
    '2025-11-26 18:48:27'
  );

-- --------------------------------------------------------
--
-- Table structure for table `payment`
--
CREATE TABLE `payment` (
  `transaction_id` varchar(25) NOT NULL COMMENT 'Transaction id of Payment',
  `user_username` varchar(20) NOT NULL COMMENT 'Username of User',
  `rent_amount` bigint(5) NOT NULL COMMENT 'Paid Rent Amount',
  `property_no` varchar(10) NOT NULL COMMENT 'Property No',
  `payment_mode` varchar(25) NOT NULL COMMENT 'Mode of Payment',
  `payment_date` datetime NOT NULL COMMENT 'Date of Payment'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `properties`
--
CREATE TABLE `properties` (
  `property_sr_no` int(3) NOT NULL COMMENT 'Property Number',
  `property_no` varchar(10) NOT NULL COMMENT 'Property No',
  `property_type` varchar(25) NOT NULL COMMENT 'Office,PG,Apartment Etc...',
  `property_address` varchar(100) NOT NULL COMMENT 'Address of Property',
  `area` varchar(50) NOT NULL COMMENT 'Area of Property',
  `city` varchar(25) NOT NULL COMMENT 'City of Property',
  `state` varchar(25) NOT NULL COMMENT 'State where property located',
  `pincode` bigint(6) NOT NULL COMMENT 'Pincode of City',
  `rent_amount` varchar(20) NOT NULL COMMENT 'Rent Price of Property',
  `description` text NOT NULL COMMENT 'Details of Property',
  `owner_name` varchar(20) NOT NULL COMMENT 'Name of Property Owner',
  `owner_contact_no` bigint(10) NOT NULL COMMENT 'Owner Contact Number',
  `owner_email_id` varchar(25) NOT NULL COMMENT 'Owner Email-id of User',
  `property_listing_dt` datetime NOT NULL COMMENT 'Date and Time of Property Listing',
  `property_photos` longtext CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid (`property_photos`)),
    `booking` varchar(15) DEFAULT 'Available'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--
INSERT INTO
  `properties` (
    `property_sr_no`,
    `property_no`,
    `property_type`,
    `property_address`,
    `area`,
    `city`,
    `state`,
    `pincode`,
    `rent_amount`,
    `description`,
    `owner_name`,
    `owner_contact_no`,
    `owner_email_id`,
    `property_listing_dt`,
    `property_photos`,
    `booking`
  )
VALUES
  (
    1,
    'B-125',
    'Farmhouse',
    'Punjabi Bagh West',
    'Noida',
    'New Delhi',
    'Delhi',
    110001,
    'Rs.5,500 / Day',
    'Fully furnished luxurious farm house for rent in Noida.\r\n4 Bathrooms,4+ Bedrooms with fully furnishing.\r\nSuper Builtup area (ft²) : 30000\r\nCarpet Area (ft²) : 29000',
    'Rameshbhai Raval',
    7894565177,
    'ramesh12345@gmail.com',
    '2020-11-11 18:59:39',
    '[\"assets\\/img\\/Z Property Images\\/Farmhouse\\/c5c16885-2778-41df-8b7c-66d6f2e1763f.png\",\"assets\\/img\\/Z Property Images\\/Farmhouse\\/2e4bef30-81ed-4cb2-8dc8-74f7540f694e.png\",\"assets\\/img\\/Z Property Images\\/Farmhouse\\/3d734b05-5f88-458c-9cf6-0d2e2d5eb871.png\",\"assets\\/img\\/Z Property Images\\/Farmhouse\\/6eba5ca2-6cf7-448f-8fac-953c21511946.png\",\"assets\\/img\\/Z Property Images\\/Farmhouse\\/8ccd8662-bfd6-450e-821a-bec17241762b.png\",\"assets\\/img\\/Z Property Images\\/Farmhouse\\/96dbbd6c-efa9-4140-8324-4ceb5fe557dd.png\",\"assets\\/img\\/Z Property Images\\/Farmhouse\\/597de72c-dbfe-4b99-b8da-b495b3c49b23.png\",\"assets\\/img\\/Z Property Images\\/Farmhouse\\/696d2d2b-39a5-489a-9c8b-5d6e10251ec7.png\",\"assets\\/img\\/Z Property Images\\/Farmhouse\\/bc4f8047-2857-4ac3-b6a0-eb546864fe7e.png\"]',
    'Available'
  ),
  (
    2,
    'A-276',
    'PG',
    'Near LuLu International Shopping Mall',
    'Edapally',
    'Kochi',
    'Kerala',
    682001,
    'Rs.5,500 / Month',
    'Including food 5500/-\r\nBed and facilities will be provided\r\nNear to edapally, kalamassery, pathadipalam, thrikkakara, Kochi, Kerala.\r\nNearby bus stop & metro station',
    'Vatsalbhai Dave',
    8128501293,
    'vatsaldave@gmail.com',
    '2023-12-01 06:33:22',
    '[\"assets\\/img\\/Z Property Images\\/PG\\/GR2-467651-2280905.png\",\"assets\\/img\\/Z Property Images\\/PG\\/GR2-467651-2287619.png\",\"assets\\/img\\/Z Property Images\\/PG\\/GR2-467651-2287623.png\",\"assets\\/img\\/Z Property Images\\/PG\\/GR2-467651-2287625.png\",\"assets\\/img\\/Z Property Images\\/PG\\/GR2-467651-2287617.png\",\"assets\\/img\\/Z Property Images\\/PG\\/GR2-467651-2280895.png\",\"assets\\/img\\/Z Property Images\\/PG\\/GR2-467651-2280897.png\",\"assets\\/img\\/Z Property Images\\/PG\\/GR2-467651-2280903.png\",\"assets\\/img\\/Z Property Images\\/PG\\/GR2-136519-1108833.png\",\"assets\\/img\\/Z Property Images\\/PG\\/GR2-136519-1570641.png\"]',
    'Available'
  ),
  (
    3,
    'H-786',
    'Apartment',
    'Sector 20',
    'Panchkula',
    'Chandigarh',
    'Haryana',
    122001,
    'Rs.26,000 / Month',
    '3 bhk fully furnished adjoining Sector 20, Panchkula, Haryana\r\nBed and facilities will be provided\r\nFacing : North-East\r\nMaintainance( Monthly ) : 2500',
    'Roshansinh Sodi',
    9765482369,
    'roshanwow@gmail.com',
    '2022-11-17 13:16:32',
    '[\"assets\\/img\\/Z Property Images\\/Apartment\\/70b96b5f-633a-4706-b641-280156bf1edd.png\",\"assets\\/img\\/Z Property Images\\/Apartment\\/d8395f29-e98c-4dcf-beb7-144d0e09825b.png\",\"assets\\/img\\/Z Property Images\\/Apartment\\/708da989-fd0c-4c03-8573-35ff0ef86234.png\",\"assets\\/img\\/Z Property Images\\/Apartment\\/0d3567e1-40bb-4564-9527-c36cc68dfc26.png\",\"assets\\/img\\/Z Property Images\\/Apartment\\/2ea4b441-ab4d-4c11-a2de-47cf7c3344fa.png\",\"assets\\/img\\/Z Property Images\\/Apartment\\/34a50cf4-f4a4-4825-97d9-bc90f3aade60.png\",\"assets\\/img\\/Z Property Images\\/Apartment\\/4077d134-280c-4f6d-b9cb-0628da0787b7.png\",\"assets\\/img\\/Z Property Images\\/Apartment\\/a3465b97-2980-4312-a2de-e61a8cb0c4ab.png\",\"assets\\/img\\/Z Property Images\\/Apartment\\/d6587e60-ae96-40d5-be18-216da17f3a41.png\",\"assets\\/img\\/Z Property Images\\/Apartment\\/5dc39570-77b4-45af-b930-02381bf0f238.png\"]',
    'Available'
  ),
  (
    4,
    'T-01',
    'Tiny Home',
    'Coastal Road',
    'Ozran Beach Road',
    'Goa',
    'Goa',
    403509,
    'Rs.3,500 / Day',
    'Compact, eco-friendly tiny home near Vagator beach.\r\nDesigned for minimalist living with an attached kitchenette.',
    'Vijay Kumar',
    7700112233,
    'vijay.k@example.com',
    '2019-03-15 10:30:00',
    '[\"assets\\/img\\/Z Property Images\\/Tiny Home\\/b5288df1-f42f-44c2-83a1-883ae1e3a869.jpg\",\"assets\\/img\\/Z Property Images\\/Tiny Home\\/1.jpg\",\"assets\\/img\\/Z Property Images\\/Tiny Home\\/1dfbe3c3-505b-48f7-b73c-778548045c3a.jpg\",\"assets\\/img\\/Z Property Images\\/Tiny Home\\/2a76a8b3-b231-432e-acb8-c5e89a16da09.jpg\",\"assets\\/img\\/Z Property Images\\/Tiny Home\\/8ebfcb28-ef52-4d56-b8d0-690b8b451032.jpg\",\"assets\\/img\\/Z Property Images\\/Tiny Home\\/13d2cfc3-14fc-4426-81bb-7a246a29bdc5.jpg\",\"assets\\/img\\/Z Property Images\\/Tiny Home\\/385fab34-6738-4b52-8bda-7ffbcee92a52.jpg\",\"assets\\/img\\/Z Property Images\\/Tiny Home\\/918971d7-a54d-4b81-ace7-2bfccd58eb06.jpg\",\"assets\\/img\\/Z Property Images\\/Tiny Home\\/09522416-7704-4377-b5e2-cdf7c4ec10dd.jpg\",\"assets\\/img\\/Z Property Images\\/Tiny Home\\/ac077578-a6c5-4fe1-a794-690e37c96295.jpg\"]',
    'Available'
  ),
  (
    5,
    'C-10',
    'Cabin',
    'Near Lake',
    'Manali-Leh Highway',
    'Manali',
    'Himachal Pradesh',
    175131,
    'Rs.4,500 / Day',
    'Rustic wooden cabin with a river view.\r\nPerfect for nature lovers and trekkers.\r\nIncludes a small fireplace.',
    'Priya Verma',
    9911223344,
    'priya.v@example.com',
    '2020-07-22 14:45:00',
    '[\"assets\\/img\\/Z Property Images\\/Cabin\\/3d1bab06-14df-437d-96d4-efe82c724a52.png\",\"assets\\/img\\/Z Property Images\\/Cabin\\/7b3aaf75-ffaf-45ee-8e3f-651894c41b66.png\",\"assets\\/img\\/Z Property Images\\/Cabin\\/30f578c6-0a3d-43cc-824c-fe7a0582aa0c.png\",\"assets\\/img\\/Z Property Images\\/Cabin\\/07d2fc24-e78a-4574-8763-b972f5c7a769.png\",\"assets\\/img\\/Z Property Images\\/Cabin\\/843b5fa9-930f-438b-8ca6-13e9fe027623.png\",\"assets\\/img\\/Z Property Images\\/Cabin\\/56120b9d-87f2-45c6-9b07-e0f8e6ddd60e.png\",\"assets\\/img\\/Z Property Images\\/Cabin\\/a68f3f56-cb17-45b5-9311-aded2f5b09b0.png\",\"assets\\/img\\/Z Property Images\\/Cabin\\/a889771d-5c16-4cea-b6e2-0deef8a199aa.png\",\"assets\\/img\\/Z Property Images\\/Cabin\\/c17c713b-e45d-471b-8f74-b42ffec70c13.png\",\"assets\\/img\\/Z Property Images\\/Cabin\\/8fe0142c-6038-4f61-b390-bc53bf2657bb.png\"]',
    'Available'
  ),
  (
    6,
    'F-502',
    'Flat',
    'Sector 18',
    'Central Park Avenue',
    'Gurgaon',
    'Haryana',
    122001,
    'Rs.28,000 / Month',
    'Spacious 1BHK flat on the 5th floor.\r\nFully furnished, close to metro station and major IT hubs.',
    'Neha Singhania',
    8899001122,
    'neha.s@example.com',
    '2022-01-05 09:10:00',
    '[\"assets\\/img\\/Z Property Images\\/Flat\\/4a2dd051-610a-49df-b32d-a20b08d431c4.png\",\"assets\\/img\\/Z Property Images\\/Flat\\/33f50965-9f73-400a-b437-eddc30b40d5d.png\",\"assets\\/img\\/Z Property Images\\/Flat\\/c39f49f2-2189-4c1a-a770-a44e55e0e7c2.png\",\"assets\\/img\\/Z Property Images\\/Flat\\/3b20f9e3-ecf1-4452-802f-151c5986febb.png\",\"assets\\/img\\/Z Property Images\\/Flat\\/182c4d66-ace4-40c4-a1a3-ff05cfe0f240.png\",\"assets\\/img\\/Z Property Images\\/Flat\\/61692f91-282b-400e-8413-af564dbb8ff5.png\",\"assets\\/img\\/Z Property Images\\/Flat\\/a46d98cf-975c-46c2-9ee7-a615814d6c1a.png\",\"assets\\/img\\/Z Property Images\\/Flat\\/b1f30097-3660-4f3b-b265-fd41d34c3141.png\",\"assets\\/img\\/Z Property Images\\/Flat\\/925d9bb4-9f84-4b36-b56e-a7b3ce90c031.png\",\"assets\\/img\\/Z Property Images\\/Flat\\/c6353857-959e-4637-8945-1c305a5d2795.png\"]',
    'Available'
  ),
  (
    7,
    'GH-3',
    'Guest House',
    'Badi Lake',
    'Near Sajjangarh Fort',
    'Udaipur',
    'Rajasthan',
    313001,
    'Rs.5,200 / Day',
    'Traditional Rajasthani Guest House offering lake views and homely meals.\r\nFamily-run establishment.',
    'Arjun Rathore',
    9321456789,
    'arjun.r@example.com',
    '2021-04-18 16:25:00',
    '[\"assets\\/img\\/Z Property Images\\/Guest House\\/1.png\",\"assets\\/img\\/Z Property Images\\/Guest House\\/4f838499-ca1d-4a98-b642-e4a68250eb89.png\",\"assets\\/img\\/Z Property Images\\/Guest House\\/8b8043ff-e18a-4ad5-81ba-5c596eeae01d.png\",\"assets\\/img\\/Z Property Images\\/Guest House\\/22fe0f24-29c2-4023-a3da-224d196a596d.png\",\"assets\\/img\\/Z Property Images\\/Guest House\\/40b64922-d879-4df5-bcc8-347d33f098dd.png\",\"assets\\/img\\/Z Property Images\\/Guest House\\/98d4fd53-7ace-4eb3-a9b1-e6c4cdc3af67.png\",\"assets\\/img\\/Z Property Images\\/Guest House\\/725870bc-d55e-4457-b09a-82367563cd55.png\",\"assets\\/img\\/Z Property Images\\/Guest House\\/c6e78d7d-dce5-47e5-bc4a-e72b63c656e5.png\",\"assets\\/img\\/Z Property Images\\/Guest House\\/d9dcafca-3c01-422f-aac4-ba15502919ab.png\",\"assets\\/img\\/Z Property Images\\/Guest House\\/e8f91d6b-1f95-4231-995c-31b134546131.png\"]',
    'Available'
  ),
  (
    8,
    'V-15',
    'Villa',
    'South City 2',
    'Sohna Road',
    'Gurgaon',
    'Haryana',
    122018,
    'Rs.1,20,000 / Month',
    'Brand new 4BHK luxury villa with a private garden and terrace.\r\n24/7 security and power backup.',
    'Sunita Patel',
    9445566778,
    'sunita.p@example.com',
    '2023-09-01 11:55:00',
    '[\"assets\\/img\\/Z Property Images\\/Villa\\/e7849d0a-c6bd-4e51-b825-883e12f6b548.png\",\"assets\\/img\\/Z Property Images\\/Villa\\/74fad43e-3547-4e84-ba34-13bb8f2d5730.png\",\"assets\\/img\\/Z Property Images\\/Villa\\/2edd1982-39a4-4a7d-b4b8-d5b5002abea9.png\",\"assets\\/img\\/Z Property Images\\/Villa\\/fdeff8ee-9c3e-4c9a-9a07-12567dd367eb.png\",\"assets\\/img\\/Z Property Images\\/Villa\\/7ec024fc-fc0a-4232-86d2-4d92d0e48474.png\",\"assets\\/img\\/Z Property Images\\/Villa\\/501cd5d9-986e-43a1-a422-a365e74fafb9.png\",\"assets\\/img\\/Z Property Images\\/Villa\\/90090a73-f24e-4d98-bf0c-ab7ac88b3662.png\",\"assets\\/img\\/Z Property Images\\/Villa\\/a6cf37c0-5753-4f95-81c5-9040bae5aa8a.png\",\"assets\\/img\\/Z Property Images\\/Villa\\/e8b553b8-8b75-44b6-b444-08648a4bb228.png\",\"assets\\/img\\/Z Property Images\\/Villa\\/b32974f1-58fd-43bc-a2f9-a9bbb7946a42.png\"]',
    'Available'
  ),
  (
    9,
    'H-404',
    'Hotel Rooms',
    'Near Airport',
    'Aerocity',
    'New Delhi',
    'Delhi',
    110037,
    'Rs.6,800 / Day',
    'Premium Hotel Room (4th floor) with complimentary breakfast and airport shuttle service.\r\nIdeal for business travellers.',
    'Gaurav Dutta',
    8000998877,
    'gaurav.d@example.com',
    '2024-02-12 08:00:00',
    '[\"assets\\/img\\/Z Property Images\\/Hotel Rooms\\/a0f9ecf0-de02-4552-9af2-c2d44d3bae11.png\",\"assets\\/img\\/Z Property Images\\/Hotel Rooms\\/719f4354-b62e-4e27-820f-63ebb1f6853d.png\",\"assets\\/img\\/Z Property Images\\/Hotel Rooms\\/93d0549c-6d33-4af4-b7f7-01b1aba73f3e.png\",\"assets\\/img\\/Z Property Images\\/Hotel Rooms\\/64aac38d-a828-4815-acd2-6763b53a3e7d.png\",\"assets\\/img\\/Z Property Images\\/Hotel Rooms\\/494630aa-846c-4382-8b0b-23138d760562.png\",\"assets\\/img\\/Z Property Images\\/Hotel Rooms\\/a8d1b92b-fae8-4b8e-932a-1615435bd0a9.png\",\"assets\\/img\\/Z Property Images\\/Hotel Rooms\\/ab132242-d835-40eb-96a2-4b1fd367b359.png\",\"assets\\/img\\/Z Property Images\\/Hotel Rooms\\/b5bd7e86-0289-43a9-b3d4-acc8f295cd4b.png\",\"assets\\/img\\/Z Property Images\\/Hotel Rooms\\/7b81dc77-2c72-40db-af12-37c5cf4d6e90.png\",\"assets\\/img\\/Z Property Images\\/Hotel Rooms\\/ddbd180f-3999-4a25-a69e-b4716875dfe1.png\"]',
    'Available'
  ),
  (
    10,
    'TH-01',
    'Tree House',
    'Wayanad Forest',
    'Lakkidi View Point',
    'Wayanad',
    'Kerala',
    673576,
    'Rs.7,999 / Day',
    'Unique treehouse experience high up in a coffee plantation.\r\nPanoramic forest views and private balcony.',
    'Lata Menon',
    9797876757,
    'lata.m@example.com',
    '2019-11-20 17:35:00',
    '[\"assets\\/img\\/Z Property Images\\/Tree House\\/5ec153f3-52b8-45a9-aef8-01ef1dc2b36a.png\",\"assets\\/img\\/Z Property Images\\/Tree House\\/8a4c0031-c3af-4014-9fd0-e487b845bbe4.png\",\"assets\\/img\\/Z Property Images\\/Tree House\\/9cd2476e-cb5e-45f7-b2f8-a3aec02a4eb4.png\",\"assets\\/img\\/Z Property Images\\/Tree House\\/43f15754-a196-439b-90b4-10cba08f40c8.png\",\"assets\\/img\\/Z Property Images\\/Tree House\\/650cc83c-c173-4a1a-8448-ad234db150fb.png\",\"assets\\/img\\/Z Property Images\\/Tree House\\/01906f74-8328-42bd-83f3-6d43bc5a7a79.png\",\"assets\\/img\\/Z Property Images\\/Tree House\\/2140660e-ffc6-46e6-a3e3-ff2fcc00444c.png\",\"assets\\/img\\/Z Property Images\\/Tree House\\/a1958cab-60d2-4037-a2d9-df52ceea269b.png\",\"assets\\/img\\/Z Property Images\\/Tree House\\/afb70c2a-ab69-4752-afc7-2369e6ac4c6b.png\",\"assets\\/img\\/Z Property Images\\/Tree House\\/d84b4a9a-2a7d-4049-9f3a-39469acdf33b.png\"]',
    'Available'
  ),
  (
    11,
    'COT-1',
    'Cottage',
    'Naukuchiatal',
    'Near Bhimtal Lake',
    'Nainital',
    'Uttarakhand',
    263136,
    'Rs.5,500 / Day',
    'Quaint lakeside cottage with 2 bedrooms.\r\nPerfect for a peaceful retreat in the Kumaon hills.',
    'Rajesh Tiwari',
    9834567890,
    'rajesh.t@example.com',
    '2025-10-28 13:20:00',
    '[\"assets\\/img\\/Z Property Images\\/Cottage\\/1.png\",\"assets\\/img\\/Z Property Images\\/Cottage\\/02c3d403-d7bf-4d3f-9660-f43fb8263a4c.png\",\"assets\\/img\\/Z Property Images\\/Cottage\\/5e69bf7d-aa02-4be7-b5cc-9d1eb65ba5d3.png\",\"assets\\/img\\/Z Property Images\\/Cottage\\/19ea3098-80bb-4c1f-ad5a-067a3f49a147.png\",\"assets\\/img\\/Z Property Images\\/Cottage\\/872eea34-1d25-4980-9b6d-7bfa9843ab0f.png\",\"assets\\/img\\/Z Property Images\\/Cottage\\/7131c7ec-e7ad-45e8-81d1-65ed2c660bbe.png\",\"assets\\/img\\/Z Property Images\\/Cottage\\/30225af6-8e07-4fef-84a9-add838876cd8.png\",\"assets\\/img\\/Z Property Images\\/Cottage\\/c189567f-eb29-4f4e-ae0c-db1c39da7732.png\",\"assets\\/img\\/Z Property Images\\/Cottage\\/f53afb85-d5ef-4243-970a-11055962646a.png\",\"assets\\/img\\/Z Property Images\\/Cottage\\/fe5c2190-f2fd-4204-b3e9-de60ee5dfb4f.png\"]',
    'Available'
  ),
  (
    12,
    'CON-05',
    'Container',
    'Industrial Area',
    'Gujarat GIDC',
    'Surat',
    'Gujarat',
    395006,
    'Rs.15,000 / Month',
    'Modular shipping container office space.\r\nIdeal for startups, with AC and basic furnishings.',
    'Harshil Joshi',
    9654321098,
    'harshil.j@example.com',
    '2024-05-19 12:15:00',
    '[\"assets\\/img\\/Z Property Images\\/Container\\/33748fe7-33d5-4517-8350-aad8705ba250.png\",\"assets\\/img\\/Z Property Images\\/Container\\/0ef966be-b18b-45f1-bfe3-51283fbdb8fa.png\",\"assets\\/img\\/Z Property Images\\/Container\\/1adb509f-895e-4531-a42d-7a561c8a7ea8.png\",\"assets\\/img\\/Z Property Images\\/Container\\/3fe88b97-eb6c-4432-9e2a-08566e97634c.png\",\"assets\\/img\\/Z Property Images\\/Container\\/04c7a0d1-a38e-46e8-852e-fa1cf242efe7.png\",\"assets\\/img\\/Z Property Images\\/Container\\/22b4d488-73d5-4bd2-bba5-f29d48b14c22.png\",\"assets\\/img\\/Z Property Images\\/Container\\/64b49a85-20b0-4c3a-90bb-d3da3256cf00.png\",\"assets\\/img\\/Z Property Images\\/Container\\/b1869d2e-c184-43a9-8cca-269f36d46577.png\",\"assets\\/img\\/Z Property Images\\/Container\\/c81c6380-18f3-40b2-a047-c28aa5e8f7c2 (1).png\",\"assets\\/img\\/Z Property Images\\/Container\\/c81c6380-18f3-40b2-a047-c28aa5e8f7c2.png\"]',
    'Available'
  );

-- --------------------------------------------------------
--
-- Table structure for table `tenants`
--
CREATE TABLE `tenants` (
  `tenant_id` int(3) NOT NULL COMMENT 'Tenant ID',
  `property_no` varchar(10) NOT NULL COMMENT 'Property No',
  `user_username` varchar(20) NOT NULL COMMENT 'Username of User',
  `first_name` varchar(10) NOT NULL COMMENT 'Name of User',
  `last_name` varchar(10) NOT NULL COMMENT 'Surname of User',
  `contact_no` bigint(10) NOT NULL COMMENT 'Contact Number',
  `email_id` varchar(25) NOT NULL COMMENT 'Email-id of User',
  `rent_amount` varchar(20) NOT NULL COMMENT 'Amount of Payment with time (Day,Night,Month)',
  `rent_dmn` varchar(5) NOT NULL COMMENT 'Rent period type Day, Month and Night',
  `rent_period` int(2) NOT NULL COMMENT 'Rent Period',
  `rent_date` datetime NOT NULL COMMENT 'Rent Period Starting Date',
  `verification_doc_no` varchar(20) NOT NULL COMMENT 'Verification Document like Photo Id, Pan Card Number',
  `payment_status` varchar(7) NOT NULL COMMENT 'Payment Done or Pending',
  `booking_date` datetime NOT NULL COMMENT 'User Booking Date'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `user_id` int(3) NOT NULL COMMENT 'Id of User',
  `user_username` varchar(20) NOT NULL COMMENT 'Username of User',
  `first_name` varchar(10) NOT NULL COMMENT 'Name of User',
  `last_name` varchar(10) NOT NULL COMMENT 'Surname of User',
  `user_password` varchar(255) NOT NULL COMMENT 'Password of User',
  `dob` date NOT NULL COMMENT 'Date of Birth of User',
  `gender` varchar(6) NOT NULL COMMENT 'Gender of User',
  `address` varchar(100) NOT NULL COMMENT 'Address of User',
  `city` varchar(50) NOT NULL COMMENT 'City of User',
  `contact_no` bigint(10) NOT NULL COMMENT 'Contact Number',
  `email_id` varchar(25) NOT NULL COMMENT 'E-mail id of User',
  `reg_date` datetime NOT NULL COMMENT 'User Registration Date',
  `state` varchar(20) NOT NULL COMMENT 'State of User Living',
  `pincode` bigint(6) NOT NULL COMMENT 'Pincode of User City'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `users`
--
INSERT INTO
  `users` (
    `user_id`,
    `user_username`,
    `first_name`,
    `last_name`,
    `user_password`,
    `dob`,
    `gender`,
    `address`,
    `city`,
    `contact_no`,
    `email_id`,
    `reg_date`,
    `state`,
    `pincode`
  )
VALUES
  (
    1,
    'raju123',
    'Raju',
    'Rastogi',
    '$2y$10$/ygxC/UzYMiMeRvWDTGfNuwRM/y8NG1djKOZsG/pWy9SXYy6VIStW',
    '2004-06-25',
    'Male',
    'Sahid Chowk',
    'Rajkot',
    9876543210,
    'raju123@gmail.com',
    '2024-04-03 00:00:00',
    'Gujarat',
    364001
  );

--
-- Indexes for dumped tables
--
--
-- Indexes for table `admins`
--
ALTER TABLE `admins` ADD PRIMARY KEY (`admin_id`, `admin_username`);

--
-- Indexes for table `bank_detail`
--
ALTER TABLE `bank_detail` ADD PRIMARY KEY (
  `bank_sr_no`,
  `user_username`,
  `card_number`,
  `card_cvv_code`,
  `bank_account_number`,
  `upi_id`
);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us` ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback` ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice` ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `past_users`
--
ALTER TABLE `past_users` ADD PRIMARY KEY (`id`),
ADD KEY `idx_user_username` (`user_username`),
ADD KEY `idx_email` (`email_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment` ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties` ADD PRIMARY KEY (`property_sr_no`),
ADD UNIQUE KEY `property_no_UNIQUE` (`property_no`),
ADD UNIQUE KEY `owner_contact_no_UNIQUE` (`owner_contact_no`),
ADD UNIQUE KEY `owner_email_id_UNIQUE` (`owner_email_id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants` ADD PRIMARY KEY (`tenant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users` ADD PRIMARY KEY (
  `user_id`,
  `user_username`,
  `contact_no`,
  `email_id`
);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins` MODIFY `admin_id` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Id of Admin',
AUTO_INCREMENT = 3;

--
-- AUTO_INCREMENT for table `bank_detail`
--
ALTER TABLE `bank_detail` MODIFY `bank_sr_no` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Serial Number',
AUTO_INCREMENT = 2;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us` MODIFY `contact_id` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Serial Id of User Contact Request',
AUTO_INCREMENT = 18;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback` MODIFY `feedback_id` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Serial Id of User Feedback Request',
AUTO_INCREMENT = 18;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice` MODIFY `invoice_id` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Id of Invoice';

--
-- AUTO_INCREMENT for table `past_users`
--
ALTER TABLE `past_users` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 3;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties` MODIFY `property_sr_no` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Property Number',
AUTO_INCREMENT = 22;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants` MODIFY `tenant_id` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Tenant ID';

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users` MODIFY `user_id` int(3) NOT NULL AUTO_INCREMENT COMMENT 'Id of User',
AUTO_INCREMENT = 4;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
