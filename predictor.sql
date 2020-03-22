-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2020 at 01:37 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `predictor`
--

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `User_ID` int(6) NOT NULL,
  `Username` varchar(30) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Password` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`User_ID`, `Username`, `Email`, `Password`) VALUES
(1, 'admin', 'admin', 'admin'),
(5, 'maruf_memon', 'maruf@gmail.com', 'M1234'),
(6, NULL, '', ''),
(7, NULL, 'tanuj@gmail.com', 'T1234'),
(8, NULL, 'gor@gmail.com', 'Y1234'),
(9, NULL, 'demo@gmail.com', 'D1234');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `News_Name` varchar(30) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Content` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prediction`
--

CREATE TABLE `prediction` (
  `Prediction_ID` int(6) NOT NULL,
  `Comp_ID` int(6) DEFAULT NULL,
  `User_ID` int(6) DEFAULT NULL,
  `Prediction` double(20,5) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Open` double(20,5) DEFAULT NULL,
  `Close` double(20,5) DEFAULT NULL,
  `Shares_Traded` double(20,5) DEFAULT NULL,
  `Turnover` double(20,5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock_details`
--

CREATE TABLE `stock_details` (
  `Comp_ID` int(6) NOT NULL,
  `Symbol` varchar(30) DEFAULT NULL,
  `Open` varchar(30) DEFAULT NULL,
  `Close` varchar(30) DEFAULT NULL,
  `Volume` varchar(30) DEFAULT NULL,
  `prev_close` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_details`
--

INSERT INTO `stock_details` (`Comp_ID`, `Symbol`, `Open`, `Close`, `Volume`, `prev_close`) VALUES
(1, 'ADANIPORTS', '257.6', '256.4', '7028556', '258.35'),
(2, 'ASIANPAINT', '1610', '1742.95', '3127942', '1601.2'),
(3, 'AXISBANK', '431.4', '428.15', '30758419', '428.25'),
(4, 'BAJAJ-AUTO', '2189.9', '2242.3', '717890', '2166.6'),
(5, 'BAJFINANCE', '2799.85', '2951.9', '6108751', '2746.1'),
(6, 'BAJAJFINSV', '5725.2', '6233.05', '657189', '5785.5'),
(7, 'BPCL', '305', '317.85', '10188011', '300.25'),
(8, 'BHARTIARTL', '445', '462.65', '19391202', '444.75'),
(9, 'INFRATEL', '124.2', '148.15', '16256162', '124.05'),
(10, 'BRITANNIA', '2341.3', '2467.8', '742349', '2318.15'),
(11, 'CIPLA', '379.8', '392.8', '4713063', '374.7'),
(12, 'COALINDIA', '124.5', '132.8', '23986832', '123.55'),
(13, 'DRREDDY', '2641', '2897.25', '1554516', '2623.95'),
(14, 'EICHERMOT', '15394.5', '15678.45', '211217', '15046.5'),
(15, 'GAIL', '71', '80.8', '34400366', '69.4'),
(16, 'GRASIM', '495', '529.4', '3028069', '491.05'),
(17, 'HCLTECH', '413.5', '444.9', '11916116', '413.45'),
(18, 'HDFCBANK', '875', '882.85', '44309215', '895.55'),
(19, 'HEROMOTOCO', '1725', '1815.7', '1243497', '1712.45'),
(20, 'HINDALCO', '101.2', '105.5', '13907782', '99.75'),
(21, 'HINDUNILVR', '1850', '2051.7', '4250019', '1838.3'),
(22, 'HDFC', '1624', '1753.95', '11367088', '1617.6'),
(23, 'ICICIBANK', '328', '345.7', '88623827', '338.55'),
(24, 'ITC', '165.1', '175.5', '61978889', '161.85'),
(25, 'IOC', '89', '90.6', '19999054', '87.95'),
(26, 'INDUSINDBK', '444.05', '440.35', '28083050', '444.05'),
(27, 'INFY', '549.5', '585.2', '21352580', '545.55'),
(28, 'JSWSTEEL', '167', '175.75', '7442999', '163.3'),
(29, 'KOTAKBANK', '1217', '1262.35', '10378049', '1210.85'),
(30, 'LT', '849', '864.7', '6128834', '843.1'),
(31, 'M&M', '316', '322.6', '8818522', '314.2'),
(32, 'MARUTI', '4869', '5079.2', '1614213', '4819.55'),
(33, 'NTPC', '78.5', '81.25', '58496884', '78.35'),
(34, 'NESTLEIND', '13198', '14145.5', '204921', '13120.3'),
(35, 'ONGC', '63.05', '72.35', '128696028', '61.05'),
(36, 'POWERGRID', '148.25', '157.25', '73960336', '147.15'),
(37, 'RELIANCE', '939.5', '1017.95', '28941912', '917.7'),
(38, 'SBIN', '205.55', '209.85', '77369934', '203.65'),
(39, 'SUNPHARMA', '365.2', '365.25', '10628334', '360.4'),
(40, 'TCS', '1630', '1797.45', '8546833', '1636.35'),
(41, 'TATAMOTORS', '74', '77.3', '86010406', '72.95'),
(42, 'TATASTEEL', '279.7', '297.75', '17301762', '271.85'),
(43, 'TECHM', '525.1', '576.35', '6315610', '530'),
(44, 'TITAN', '906.1', '904.9', '4407151', '903.5'),
(45, 'UPL', '294', '300.15', '7462829', '296.75'),
(46, 'ULTRACEMCO', '3185', '3573.85', '825325', '3166.05'),
(47, 'VEDL', '72.4', '75.45', '29754344', '69.35'),
(48, 'WIPRO', '163.1', '179.2', '10157128', '162.35'),
(49, 'YESBANK', '59.2', '45.85', '118143282', '53.85'),
(50, 'ZEEL', '144.2', '144.9', '18343197', '141.2');

-- --------------------------------------------------------

--
-- Table structure for table `s_c_details`
--

CREATE TABLE `s_c_details` (
  `Comp_ID` int(6) NOT NULL,
  `Comp_Name` varchar(30) NOT NULL,
  `Comp_Website` varchar(50) DEFAULT NULL,
  `Headquaters` varchar(50) DEFAULT NULL,
  `Founded` int(5) DEFAULT NULL,
  `Industry` varchar(30) DEFAULT NULL,
  `Symbol` varchar(20) DEFAULT NULL,
  `Series` varchar(5) DEFAULT NULL,
  `ISIN_Code` varchar(30) DEFAULT NULL,
  `Img` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `s_c_details`
--

INSERT INTO `s_c_details` (`Comp_ID`, `Comp_Name`, `Comp_Website`, `Headquaters`, `Founded`, `Industry`, `Symbol`, `Series`, `ISIN_Code`, `Img`) VALUES
(1, 'Adani Ports  Ltd.', 'www.adaniports.com', 'Ahmedabad,Gujarat', 1998, 'INFRASTRUCTURE', 'ADANIPORTS', 'EQ', 'INE742F01042', 'resources/img/nifty-logo/adani.png'),
(2, 'Asian Paints Ltd.', 'www.asianpaints.com', 'Mumbai, Maharashtra', 1942, 'CONSUMER GOODS', 'ASIANPAINT', 'EQ', 'INE021A01026', 'resources/img/nifty-logo/asian.png'),
(3, 'Axis Bank Ltd.', 'www.axisbank.com', 'Mumbai, Maharashtra', 1993, 'FINANCIAL SERVICES', 'AXISBANK', 'EQ', 'INE238A01034', 'resources/img/nifty-logo/axis.png'),
(4, 'Bajaj Auto Ltd.', 'www.bajajauto.com', 'Pune, Maharashtra', 1945, 'AUTOMOBILE', 'BAJAJ-AUTO', 'EQ', 'INE917I01010', 'resources/img/nifty-logo/bajaj.png'),
(5, 'Bajaj Finance Ltd.', 'www.bajajfinserv.in/finance', 'Pune, Maharashtra', 1987, 'FINANCIAL SERVICES', 'BAJFINANCE', 'EQ', 'INE296A01024', 'resources/img/nifty-logo/bajaj_fin.jpeg'),
(6, 'Bajaj Finserv Ltd.', 'www.bajajfinserv.in', 'Pune, Maharashtra', 2007, 'FINANCIAL SERVICES', 'BAJAJFINSV', 'EQ', 'INE918I01018', 'resources/img/nifty-logo/bajaj_fin.jpeg'),
(7, 'Bharat Petroleum Corporation L', 'www.bharatpetroleum.com', 'Mumbai, Maharashtra', 1950, 'ENERGY-OIL&GAS', 'BPCL', 'EQ', 'INE029A01011', 'resources/img/nifty-logo/bharat.png'),
(8, 'Bharti Airtel Ltd.', 'www.airtel.com', 'New Delhi,Delhi', 1995, 'TELECOM', 'BHARTIARTL', 'EQ', 'INE397D01024', 'resources/img/nifty-logo/airtel.png'),
(9, 'Bharti Infratel Ltd.', 'www.bharti.com', 'New Delhi,Delhi', 1976, 'TELECOM', 'INFRATEL', 'EQ', 'INE121J01017', 'resources/img/nifty-logo/bharti.png'),
(10, 'Britannia Industries Ltd.', 'www.britannia.co.in', 'Kolkata, West Bengal', 1982, 'CONSUMER GOODS', 'BRITANNIA', 'EQ', 'INE216A01030', 'resources/img/nifty-logo/britannia.png'),
(11, 'Cipla Ltd.', 'www.cipla.com', 'Mumbai, Maharashtra', 1935, 'PHARMA', 'CIPLA', 'EQ', 'INE059A01026', 'resources/img/nifty-logo/cipla.png'),
(12, 'Coal India Ltd.', 'www.coalindia.in', 'Kolkata, West Bengal', 1975, 'METALS', 'COALINDIA', 'EQ', 'INE522F01014', 'resources/img/nifty-logo/coal.png'),
(13, 'Dr. Reddy\'s Laboratories Ltd', 'www.drreddys.com', 'Hyderabad, Telangana', 1984, 'PHARMA', 'DRREDDY', 'EQ', 'INE089A01023', 'resources/img/nifty-logo/reddy.png'),
(14, 'Eicher Motors Ltd.', 'www.eicher.in', 'New Delhi,Delhi', 1948, 'AUTOMOBILE', 'EICHERMOT', 'EQ', 'INE066A01013', 'resources/img/nifty-logo/eicher.png'),
(15, 'GAIL (India) Ltd.', 'www.gailonline.com', 'New Delhi,Delhi', 1984, 'ENERGY', 'GAIL', 'EQ', 'INE129A01019', 'resources/img/nifty-logo/gail.png'),
(16, 'Grasim Industries Ltd.', 'grasim.com', 'Mumbai, Maharashtra', 1947, 'CEMENT & CEMENT PRODUCTS', 'GRASIM', 'EQ', 'INE047A01021', 'resources/img/nifty-logo/grasim.png'),
(17, 'HCL Technologies Ltd.', 'www.hcltech.com', 'Noida, Uttar Pradesh', 1976, 'IT', 'HCLTECH', 'EQ', 'INE860A01027', 'resources/img/nifty-logo/hcl.ong'),
(18, 'HDFC Bank Ltd.', 'www.hdfcbank.com', 'Mumbai, Maharashtra', 1994, 'FINANCIAL SERVICES', 'HDFCBANK', 'EQ', 'INE040A01034', 'resources/img/nifty-logo/hdfc.png'),
(19, 'Hero MotoCorp Ltd.', 'Hero Motocorp', 'New Delhi,Delhi', 1984, 'AUTOMOBILE', 'HEROMOTOCO', 'EQ', 'INE158A01026', 'resources/img/nifty-logo/hero.png'),
(20, 'Hindalco Industries Ltd.', 'www.hindalco.com', 'Mumbai, Maharashtra', 1958, 'METALS', 'HINDALCO', 'EQ', 'INE038A01020', 'resources/img/nifty-logo/hindalco.png'),
(21, 'Hindustan Unilever Ltd.', 'www.hul.co.in', 'Mumbai, Maharashtra', 1933, 'CONSUMER GOODS', 'HINDUNILVR', 'EQ', 'INE030A01027', 'resources/img/nifty-logo/hindustan.png'),
(22, 'Housing Development Finance Co', 'www.hdfc.com', 'Mumbai, Maharashtra', 1977, 'FINANCIAL SERVICES', 'HDFC', 'EQ', 'INE001A01036', 'resources/img/nifty-logo/hdfc_ins.png'),
(23, 'ICICI Bank Ltd.', 'www.icicibank.com', 'Mumbai, Maharashtra', 1994, 'FINANCIAL SERVICES', 'ICICIBANK', 'EQ', 'INE090A01021', 'resources/img/nifty-logo/icici.png'),
(24, 'ITC Ltd.', 'www.itcportal.com', 'Kolkata, West Bengal', 1910, 'CONSUMER GOODS', 'ITC', 'EQ', 'INE154A01025', 'resources/img/nifty-logo/itc.png'),
(25, 'Indian Oil Corporation Ltd.', 'www.iocl.com', 'New Delhi,Delhi', 1959, 'ENERGY', 'IOC', 'EQ', 'INE242A01010', 'resources/img/nifty-logo/indianoil.png'),
(26, 'IndusInd Bank Ltd.', 'www.indusind.com', 'Pune, Maharashtra', 1994, 'FINANCIAL SERVICES', 'INDUSINDBK', 'EQ', 'INE095A01012', 'resources/img/nifty-logo/induslnd.png'),
(27, 'Infosys Ltd.', 'www.infosys.com', 'Bangalore, Karnataka', 1981, 'IT', 'INFY', 'EQ', 'INE009A01021', 'resources/img/nifty-logo/infosys.png'),
(28, 'JSW Steel Ltd.', 'www.jswsteel.in', 'Mumbai, Maharashtra', 1982, 'METALS', 'JSWSTEEL', 'EQ', 'INE019A01038', 'resources/img/nifty-logo/jsw.png'),
(29, 'Kotak Mahindra Bank Ltd.', 'www.kotak.com', 'Mumbai, Maharashtra', 2003, 'FINANCIAL SERVICES', 'KOTAKBANK', 'EQ', 'INE237A01028', 'resources/img/nifty-logo/kotak.png'),
(30, 'Larsen & Toubro Ltd.', 'www.larsentoubro.com', 'Mumbai, Maharashtra', 1938, 'CONSTRUCTION', 'LT', 'EQ', 'INE018A01030', 'resources/img/nifty-logo/lt.png'),
(31, 'Mahindra & Mahindra Ltd.', 'www.mahindra.com', 'Mumbai, Maharashtra', 1945, 'AUTOMOBILE', 'M&M', 'EQ', 'INE101A01026', 'resources/img/nifty-logo/mahindra.png'),
(32, 'Maruti Suzuki India Ltd.', 'www.marutisuzuki.com', 'New Delhi,Delhi', 1981, 'AUTOMOBILE', 'MARUTI', 'EQ', 'INE585B01010', 'resources/img/nifty-logo/maruti.png'),
(33, 'NTPC Ltd.', 'www.ntpc.co.in', 'New Delhi,Delhi', 1975, 'ENERGY', 'NTPC', 'EQ', 'INE733E01010', 'resources/img/nifty-logo/ntpc.png'),
(34, 'Nestle India Ltd.', 'www.nestle.com', 'Vevey, Vaud, Switzerland', 1866, 'CONSUMER GOODS', 'NESTLEIND', 'EQ', 'INE239A01016', 'resources/img/nifty-logo/nestle.png'),
(35, 'Oil & Natural Gas Corporation ', 'www.ongcindia.com', 'New Delhi,Delhi', 1956, 'ENERGY', 'ONGC', 'EQ', 'INE213A01029', 'resources/img/nifty-logo/ongc.png'),
(36, 'Power Grid Corporation of Indi', 'powergridindia.com', 'Gurgaon, Haryana', 1989, 'ENERGY', 'POWERGRID', 'EQ', 'INE752E01010', 'resources/img/nifty-logo/powergrid.png'),
(37, 'Reliance Industries Ltd.', 'www.ril.com', 'Mumbai, Maharashtra', 1973, 'ENERGY', 'RELIANCE', 'EQ', 'INE002A01018', 'resources/img/nifty-logo/reliance.png'),
(38, 'State Bank of India', 'sbi.co.in', 'Mumbai, Maharashtra', 1955, 'FINANCIAL SERVICES', 'SBIN', 'EQ', 'INE062A01020', 'resources/img/nifty-logo/sbi.png'),
(39, 'Sun Pharmaceutical Industries ', 'www.sunpharma.com', 'Mumbai, Maharashtra', 1983, 'PHARMA', 'SUNPHARMA', 'EQ', 'INE044A01036', 'resources/img/nifty-logo/sun.jpeg'),
(40, 'Tata Consultancy Services Ltd.', 'www.tcs.com', 'Mumbai, Maharashtra', 1968, 'IT', 'TCS', 'EQ', 'INE467B01029', 'resources/img/nifty-logo/tcs.png'),
(41, 'Tata Motors Ltd.', 'TataMotor', 'Mumbai, Maharashtra', 1945, 'AUTOMOBILE', 'TATAMOTORS', 'EQ', 'INE155A01022', 'resources/img/nifty-logo/tata_motors.png'),
(42, 'Tata Steel Ltd.', 'www.tatasteel.com', 'Kolkata, West Bengal,', 1907, 'METALS', 'TATASTEEL', 'EQ', 'INE081A01012', 'resources/img/nifty-logo/tata_steel.png'),
(43, 'Tech Mahindra Ltd.', 'www.techmahindra.com', 'Pune, Maharashtra', 1986, 'IT', 'TECHM', 'EQ', 'INE669C01036', 'resources/img/nifty-logo/tech.png'),
(44, 'Titan Company Ltd.', 'titancompany.in', 'Chennai,Tamilnadu', 1984, 'CONSUMER GOODS', 'TITAN', 'EQ', 'INE280A01028', 'resources/img/nifty-logo/titan.png'),
(45, 'UPL Ltd.', 'www.upl-ltd.com', 'Mumbai, Maharashtra', 1969, 'FERTILISERS & PESTICIDES', 'UPL', 'EQ', 'INE628A01036', 'resources/img/nifty-logo/upl.png'),
(46, 'UltraTech Cement Ltd.', 'www.ultratechcement.com', 'Mumbai, Maharashtra', 1983, 'CEMENT & CEMENT PRODUCTS', 'ULTRACEMCO', 'EQ', 'INE481G01011', 'resources/img/nifty-logo/ultratech.jpeg'),
(47, 'Vedanta Ltd.', 'www.vedantaresources.com', 'London, United Kingdom', 1976, 'METALS', 'VEDL', 'EQ', 'INE205A01025', 'resources/img/nifty-logo/vedanta.png'),
(48, 'Wipro Ltd.', 'www.wipro.com', 'Bangalore, Karnataka', 1945, 'IT', 'WIPRO', 'EQ', 'INE075A01022', 'resources/img/nifty-logo/wipro.png'),
(49, 'Yes Bank Ltd.', 'www.yesbank.in', 'Mumbai, Maharashtra', 2004, 'FINANCIAL SERVICES', 'YESBANK', 'EQ', 'INE528G01035', 'resources/img/nifty-logo/yes.png'),
(50, 'Zee Entertainment Enterprises ', 'www.zeeentertainment.com', 'Mumbai, Maharashtra', 1926, 'MEDIA & ENTERTAINMENT', 'ZEEL', 'EQ', 'INE256A01028', 'resources/img/nifty-logo/zee.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `User_ID` int(6) NOT NULL,
  `First_Name` varchar(20) DEFAULT NULL,
  `Last_Name` varchar(20) DEFAULT NULL,
  `Age` int(3) DEFAULT NULL,
  `Email` varchar(40) DEFAULT NULL,
  `Username` varchar(30) DEFAULT NULL,
  `Password` varchar(30) DEFAULT NULL,
  `img` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`User_ID`, `First_Name`, `Last_Name`, `Age`, `Email`, `Username`, `Password`, `img`) VALUES
(1, 'admin', 'admin', 19, 'admin@gmail.com', 'admin', 'admin', 'resources/img/profile/admin.png'),
(5, 'Maruf', 'Memon', 20, 'maruf@gmail.com', 'maruf_memon', 'M1234', 'resources/img/profile/m.png'),
(6, '', 'admin', 19, '', 'admin', '', NULL),
(7, 'Tanuj', 'Dey', 20, 'tanuj@gmail.com', 'tanuj_dey', 'T1234', 'resources/img/profile/t.png'),
(8, 'Yash', 'Gor', 20, 'gor@gmail.com', 'yash_gor', 'Y1234', 'resources/img/profile/g.png'),
(9, 'Demo', 'Demo123', 25, 'demo@gmail.com', 'demo24', 'D1234', 'resources/img/profile/PP.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `prediction`
--
ALTER TABLE `prediction`
  ADD PRIMARY KEY (`Prediction_ID`),
  ADD KEY `Comp_ID` (`Comp_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `stock_details`
--
ALTER TABLE `stock_details`
  ADD KEY `Comp_ID` (`Comp_ID`);

--
-- Indexes for table `s_c_details`
--
ALTER TABLE `s_c_details`
  ADD PRIMARY KEY (`Comp_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `User_ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `prediction`
--
ALTER TABLE `prediction`
  MODIFY `Prediction_ID` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_details`
--
ALTER TABLE `stock_details`
  MODIFY `Comp_ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT for table `s_c_details`
--
ALTER TABLE `s_c_details`
  MODIFY `Comp_ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `User_ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `prediction`
--
ALTER TABLE `prediction`
  ADD CONSTRAINT `prediction_ibfk_1` FOREIGN KEY (`Comp_ID`) REFERENCES `s_c_details` (`Comp_ID`),
  ADD CONSTRAINT `prediction_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `user` (`User_ID`);

--
-- Constraints for table `stock_details`
--
ALTER TABLE `stock_details`
  ADD CONSTRAINT `stock_details_ibfk_1` FOREIGN KEY (`Comp_ID`) REFERENCES `s_c_details` (`Comp_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
