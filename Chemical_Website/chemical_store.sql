-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 01:57 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chemical_store`
--
CREATE DATABASE IF NOT EXISTS `chemical_store` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `chemical_store`;

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `headline` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`id`, `title`, `image`, `headline`, `description`) VALUES
(1, 'About Us', 'uploads/1743249576_page2_pic1.jpg', 'Innovating Chemistry for a Better Tomorrow', 'Chemicals Pvt. Ltd. is a leading name in the chemical industry, offering a wide range of high-quality chemical products. From industrial solutions to specialty chemicals, we are dedicated to delivering excellence and reliability to our clients.\r\n\r\n\r\n\r\nWe pride ourselves on our commitment to innovation, sustainability, and customer satisfaction. With advanced manufacturing facilities and a skilled workforce, we ensure that our products meet international standards and cater to diverse industrial needs.\r\n\r\nOur focus on research and development enables us to create solutions that not only meet current demands but also anticipate future challenges. We believe in building strong partnerships with our clients, ensuring mutual growth and success.');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123'),
(2, 'superadmin', 'superadmin@121');

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT 'admin',
  `created_at` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `content`, `image_path`, `author`, `created_at`) VALUES
(8, 'Advanced Chemical Technology for Modern Industries', 'Bharat Chemicals is a leading provider of high-quality chemicals, serving industries such as pharmaceuticals, agriculture, water treatment, and manufacturing. Committed to innovation and sustainability, we deliver eco-friendly and reliable chemical solutions tailored to meet industry needs. With a focus on safety, quality, and customer satisfaction, we strive to drive progress through cutting-edge chemical technology.', '../uploads/page2_pic4.jpg', 'admin', '2025-02-18');

-- --------------------------------------------------------

--
-- Table structure for table `content_sections`
--

CREATE TABLE `content_sections` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `dropdown_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content_sections`
--

INSERT INTO `content_sections` (`id`, `title`, `subtitle`, `description`, `dropdown_text`) VALUES
(1, 'Innovations', 'Innovations in Chemical Manufacturing', 'Advancing sustainable and efficient chemical solutions with innovative technologies and eco-friendly practices.', 'Explore our cutting-edge innovations in chemical manufacturing. From sustainable practices to eco-friendly solutions, we drive industry transformation with technological advancements.'),
(2, 'Performance', 'Performance-Oriented Solutions', 'Our products are engineered to meet industry standards with exceptional consistency and performance.', 'Discover performance-focused solutions designed to exceed industry benchmarks. Our products ensure reliability, consistency, and efficiency in every application.'),
(3, 'Technologies', 'Cutting-Edge Chemical Technologies', 'With state-of-the-art technology and expert research, we bring innovative chemical solutions to life.', 'Leverage cutting-edge technology and research expertise to create innovative solutions that redefine chemical industry standards.');

-- --------------------------------------------------------

--
-- Table structure for table `email_data`
--

CREATE TABLE `email_data` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_data`
--

INSERT INTO `email_data` (`id`, `email`, `telephone`, `fax`, `address`) VALUES
(2, 'admin@gmail.com', '123-456-7890', '123-456-7891', '123 Street, City, Country');

-- --------------------------------------------------------

--
-- Table structure for table `feature_sections`
--

CREATE TABLE `feature_sections` (
  `id` int(11) NOT NULL,
  `letter` char(1) NOT NULL,
  `icon_class` varchar(50) NOT NULL,
  `title1` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feature_sections`
--

INSERT INTO `feature_sections` (`id`, `letter`, `icon_class`, `title1`, `description`) VALUES
(1, 'A', '<i class=\"fa-solid fa-trash\"></i>', 'Waste Management \r\nSolutions', 'Efficient disposal and recycling methods for chemical waste, ensuring environmental safety.'),
(2, 'B', '<i class=\"fa-solid fa-flask\"></i>', 'Innovative Lab\r\nTechniques', 'Explore advanced chemical methodologies to enhance productivity and precision.'),
(3, 'C', '<i class=\"fa-solid fa-wrench\"></i>', 'Industrial Tools\r\nand Applications', 'Specialized tools designed for seamless integration into chemical industries.'),
(4, 'D', '<i class=\"fa-brands fa-pagelines\"></i>', 'Eco-Friendly\r\nProduct Line', 'Discover our range of environmentally safe chemical products.');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `footer_settings`
--

CREATE TABLE `footer_settings` (
  `id` int(11) NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `fax` varchar(50) NOT NULL,
  `copyright` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `footer_settings`
--

INSERT INTO `footer_settings` (`id`, `company_name`, `address`, `telephone`, `fax`, `copyright`) VALUES
(1, 'Bharat Chemicals', 'Bharat Chemicals\r\nVadodara, Gujarat\r\n370009, India', '+91 7926512345', '+91 79 262345545', 'Chemicals © 2025');

-- --------------------------------------------------------

--
-- Table structure for table `home_page_settings`
--

CREATE TABLE `home_page_settings` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `slider_image1` varchar(255) NOT NULL,
  `slider_image2` varchar(255) NOT NULL,
  `slider_image3` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_page_settings`
--

INSERT INTO `home_page_settings` (`id`, `company_name`, `slider_image1`, `slider_image2`, `slider_image3`) VALUES
(1, 'Bharat Chemicals', 'frontendside/images/gall_pic1.jpg', 'frontendside/images/gall_pic2.jpg', 'frontendside/images/gall_pic3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `iframe`
--

CREATE TABLE `iframe` (
  `id` int(11) NOT NULL,
  `iframe` text DEFAULT NULL,
  `email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `iframe`
--

INSERT INTO `iframe` (`id`, `iframe`, `email`) VALUES
(1, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d4802.5796181137375!2d73.19568804401375!3d22.35965953101516!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1741669317550!5m2!1sen!2sin\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'demo@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`id`, `name`, `image_url`, `description`) VALUES
(1, 'Arjun Mehta', 'uploads/page1_pic2.jpg', 'Expert in chemical innovations and sustainable solutions with extensive industry experience.'),
(2, 'Neeraj Gupta', 'uploads/page1_pic3.jpg', 'Specialist in industrial safety and process optimization for chemical plants.'),
(3, 'Rohan Iyer', 'uploads/page1_pic4.jpg', 'Strategist with a focus on eco-friendly and cost-efficient chemical production methods.'),
(4, 'Pankaj Sharma', 'uploads/page1_pic5.jpg', 'Experienced engineer in chemical manufacturing and infrastructure development.');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `appearance` varchar(255) DEFAULT NULL,
  `formula` varchar(100) DEFAULT NULL,
  `application` text DEFAULT NULL,
  `packaging_details` varchar(255) DEFAULT NULL,
  `storage_info` text DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `molecular_weight` varchar(20) NOT NULL,
  `specific_gravity` varchar(20) NOT NULL,
  `ph` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `description`, `appearance`, `formula`, `application`, `packaging_details`, `storage_info`, `category`, `molecular_weight`, `specific_gravity`, `ph`) VALUES
(29, 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `product_display`
--

CREATE TABLE `product_display` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_display`
--

INSERT INTO `product_display` (`id`, `title`, `description`, `image`) VALUES
(1, 'Industrial Solvents', 'Essential for cleaning, degreasing, and chemical synthesis, our range of industrial solvents ensures optimal performance across various industries.', 'uploads/page2_pic2.jpg'),
(2, 'Water Treatment Chemicals', 'Our water treatment solutions include coagulants, flocculants, and disinfectants to help industries maintain clean and safe water systems.', 'uploads/page2_pic3.jpg'),
(3, 'Polymers and Resins', 'High-quality polymers and resins designed for applications in adhesives, coatings, and composite materials.', 'uploads/page2_pic4.jpg'),
(4, 'Agricultural Chemicals', 'Providing fertilizers, pesticides, and growth enhancers to support sustainable and efficient farming practices.', 'uploads/page2_pic5.jpg'),
(5, 'Petrochemical', 'Our petrochemical products include key components for plastics, fuels, and industrial materials.', 'uploads/page2_pic6.jpg'),
(6, 'Specialty Additives', 'Innovative chemical additives to enhance the performance and durability of products across industries.', 'uploads/page2_pic7.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `section_images`
--

CREATE TABLE `section_images` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section_images`
--

INSERT INTO `section_images` (`id`, `section_id`, `image_url`, `title`, `description`) VALUES
(1, 1, 'uploads/page2_pic1.jpg', 'Innovative Chemical Solutions', 'Discover groundbreaking innovations in chemical research and production that drive sustainable and eco-friendly solutions.\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `heading` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `heading`) VALUES
(1, 'Our Products');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `visit_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `ip_address`, `visit_time`) VALUES
(1, '::1', '2025-02-28 04:17:23');

-- --------------------------------------------------------

--
-- Table structure for table `why_us`
--

CREATE TABLE `why_us` (
  `id` int(11) NOT NULL,
  `heading` varchar(255) NOT NULL DEFAULT 'Why Us?',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `why_us`
--

INSERT INTO `why_us` (`id`, `heading`, `title`, `description`) VALUES
(1, 'Why Us?', 'Trusted Quality', 'We adhere to stringent quality standards, ensuring that every product we deliver is reliable, safe, and effective.'),
(2, 'Why Us?', 'Sustainable Practices', 'Our operations are designed to minimize environmental impact, contributing to a greener and more sustainable future.'),
(3, 'Why Us?', 'Customer-Centric Approach', 'We value our clients and work closely with them to provide customized solutions that exceed their expectations.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_sections`
--
ALTER TABLE `content_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_data`
--
ALTER TABLE `email_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feature_sections`
--
ALTER TABLE `feature_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_settings`
--
ALTER TABLE `footer_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_page_settings`
--
ALTER TABLE `home_page_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iframe`
--
ALTER TABLE `iframe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_display`
--
ALTER TABLE `product_display`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section_images`
--
ALTER TABLE `section_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `why_us`
--
ALTER TABLE `why_us`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `content_sections`
--
ALTER TABLE `content_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `email_data`
--
ALTER TABLE `email_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feature_sections`
--
ALTER TABLE `feature_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `footer_settings`
--
ALTER TABLE `footer_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `home_page_settings`
--
ALTER TABLE `home_page_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `iframe`
--
ALTER TABLE `iframe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `product_display`
--
ALTER TABLE `product_display`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `section_images`
--
ALTER TABLE `section_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `why_us`
--
ALTER TABLE `why_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `section_images`
--
ALTER TABLE `section_images`
  ADD CONSTRAINT `section_images_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `content_sections` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
