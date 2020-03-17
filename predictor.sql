-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2020 at 12:57 PM
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
(8, NULL, 'gor@gmail.com', 'Y1234');

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
  `Volume` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_details`
--

INSERT INTO `stock_details` (`Comp_ID`, `Symbol`, `Open`, `Close`, `Volume`) VALUES
(1, 'ADANIPOWER', '31.700000762939453', '23.0', '27.299999237060547'),
(2, 'AMARAJABAT', '595.4000244140625', '505.54998779296875', '510.0'),
(3, 'APOLLOHOSP', '1598.5', '1379.0', '1380.0999755859375'),
(4, 'APOLLOTYRE', '105.19999694824219', '88.0', '90.1500015258789'),
(5, 'BALKRISIND', '1045.0', '881.0', '890.0'),
(6, 'BANKINDIA', '37.099998474121094', '30.399999618530273', '31.0'),
(7, 'BATAINDIA', '1488.0', '1272.0', '1272'),
(8, 'BEL', '70.55000305175781', '60.0', '60.0'),
(9, 'BHARATFORG', '382.0', '332.5', '332.5'),
(10, 'BHEL', '26.399999618530273', '18.399999618530273', '22.200000762939453'),
(11, 'CESC', '529.9500122070312', '432.0', '457'),
(12, 'CANBK', '109.4000015258789', '82.0', '89.19999694824219'),
(13, 'CASTROLIND', '130.64999389648438', '120.05000305175781', '120.3499984741211'),
(14, 'CHOLAFIN', '263.8999938964844', '210.35000610351562', '230.0'),
(15, 'CUMMINSIND', '453.8999938964844', '404.0', '410'),
(16, 'ESCORTS', '740.0', '590.0499877929688', '616.0'),
(17, 'EXIDEIND', '154.0', '125.8499984741211', '129'),
(18, 'FEDERALBNK', '69.25', '53.650001525878906', '56.79999923706055'),
(19, 'GMRINFRA', '17.299999237060547', '14.100000381469727', '14.699999809265137'),
(20, 'GLENMARK', '217.3000030517578', '161.64999389648438', '181.0'),
(21, 'HEXAWARE', '330.0', '248.9499969482422', '262.0'),
(22, 'IDFCFIRSTB', '30.450000762939453', '20.700000762939453', '24.799999237060547'),
(23, 'IGL', '429.79998779296875', '349.54998779296875', '349.54998779296875'),
(24, 'NAUKRI', '2350.0', '2131.550048828125', '2132'),
(25, 'JINDALSTEL', '130.5', '99.19999694824219', '103.0'),
(26, 'JUBLFOOD', '1611.949951171875', '1270.0', '1326.699951171875'),
(27, 'LICHSGFIN', '283.6499938964844', '227.75', '232.0'),
(28, 'MRF', '66300.0', '56030', '56030'),
(29, 'MGL', '971.8499755859375', '780.5499877929688', '840.0'),
(30, 'M&MFIN', '313.20001220703125', '251.0', '260.3500061035156'),
(31, 'MANAPPURAM', '138.60000610351562', '110.5', '119.0'),
(32, 'MFSL', '457.6000061035156', '355.5', '399.95001220703125'),
(33, 'MINDTREE', '889.5', '700.1500244140625', '735'),
(34, 'MUTHOOTFIN', '817.4500122070312', '650.0499877929688', '691.2999877929688'),
(35, 'NBCC', '19.25', '15.0', '17.0'),
(36, 'NATIONALUM', '30.899999618530273', '24.399999618530273', '24.399999618530273'),
(37, 'OIL', '85.44999694824219', '63.5', '70.0'),
(38, 'RBLBANK', '220.1999969482422', '166.0', '191.14999389648438'),
(39, 'RECLTD', '101.5999984741211', '84.0', '85.5999984741211'),
(40, 'SRF', '3591.14990234375', '2900.35009765625', '3065.0'),
(41, 'SAIL', '30.450000762939453', '21.399999618530273', '22.799999237060547'),
(42, 'SUNTV', '408.3999938964844', '325.0', '326'),
(43, 'TVSMOTOR', '408.6499938964844', '349.3999938964844', '351.0'),
(44, 'TATAGLOBAL', '320.0', '297.20001220703125', '320.0'),
(45, 'TATAPOWER', '42.79999923706055', '34.599998474121094', '37.900001525878906'),
(46, 'RAMCOCEM', '670.2000122070312', '590.1500244140625', '590.1500244140625'),
(47, 'TORNTPHARM', '2140.85009765625', '1790.0', '1810.0'),
(48, 'TORNTPOWER', '297.8500061035156', '231.9499969482422', '262.6000061035156'),
(49, 'UNIONBANK', '31.600000381469727', '24.399999618530273', '26.299999237060547'),
(50, 'VOLTAS', '664.2000122070312', '559.2999877929688', '564');

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
(1, 'Adani Power Ltd.', 'www.adani.com', 'Ahmedabad,Gujarat', 1988, 'ENERGY', 'ADANIPOWER', 'EQ', 'INE814H01011', 'resources/img/nifty-logo/adani.png'),
(2, 'Amara Raja Batteries Ltd.', 'www.amararaja.com', 'Tirupati', 1985, 'AUTOMOBILE', 'AMARAJABAT', 'EQ', 'INE885A01032', 'resources/img/nifty-logo/amara.jpg'),
(3, 'Apollo Hospitals Enterprise Lt', 'www.apollohospitals.com', 'Chennai, India', 1983, 'HEALTHCARE SERVICES', 'APOLLOHOSP', 'EQ', 'INE437A01024', 'resources/img/nifty-logo/apollo.png'),
(4, 'Apollo Tyres Ltd.', 'www.apollotyres.com', 'Gurgaon, Haryana, India', 1972, 'AUTOMOBILE', 'APOLLOTYRE', 'EQ', 'INE438A01022', 'resources/img/nifty-logo/apollotyre.png'),
(5, 'Balkrishna Industries Ltd.', 'www.bkt-tires.com', 'Mumbai, India', 1987, 'AUTOMOBILE', 'BALKRISIND', 'EQ', 'INE787D01026', 'resources/img/nifty-logo/bkt.png'),
(6, 'Bank of India', 'www.bankofindia.co.in', 'Mumbai, India', 1906, 'FINANCIAL SERVICES', 'BANKINDIA', 'EQ', 'INE084A01016', 'resources/img/nifty-logo/boi.png'),
(7, 'Bata India Ltd.', 'www.bata.com', 'Lausanne, Switzerland', 1894, 'CONSUMER GOODS', 'BATAINDIA', 'EQ', 'INE176A01028', 'resources/img/nifty-logo/bata.jpg'),
(8, 'Bharat Electronics Ltd.', 'www.bel-india.in', 'Bengaluru, Karnataka, India', 1954, 'INDUSTRIAL MANUFACTURING', 'BEL', 'EQ', 'INE263A01024', 'resources/img/nifty-logo/bharat.png'),
(9, 'Bharat Forge Ltd.', 'www.bharatforge.com', 'Pune, Maharashtra, India', 1961, 'INDUSTRIAL MANUFACTURING', 'BHARATFORG', 'EQ', 'INE465A01025', 'resources/img/nifty-logo/bharatforge.png'),
(10, 'Bharat Heavy Electricals Ltd.', 'www.bhel.com', 'New Delhi, India', 1964, 'INDUSTRIAL MANUFACTURING', 'BHEL', 'EQ', 'INE257A01026', 'resources/img/nifty-logo/bhel.png'),
(11, 'CESC Ltd.', 'www.cesc.co.in', 'Kolkata, India', 1897, 'ENERGY', 'CESC', 'EQ', 'INE486A01013', 'resources/img/nifty-logo/cesc.png'),
(12, 'Canara Bank', 'www.canarabank.com', 'Bangalore, Karnataka, India', 1906, 'FINANCIAL SERVICES', 'CANBK', 'EQ', 'INE476A01014', 'resources/img/nifty-logo/canara.png'),
(13, 'Castrol India Ltd.', 'www.castrol.com/en_in/india.html', 'Mumbai, Maharashtra, India', 1910, 'ENERGY', 'CASTROLIND', 'EQ', 'INE172A01027', 'resources/img/nifty-logo/castrol.png'),
(14, 'Cholamandalam Investment and F', 'www.cholamandalam.com', 'Chennai, Tamil Nadu, India', 1978, 'FINANCIAL SERVICES', 'CHOLAFIN', 'EQ', 'INE121A01024', 'resources/img/nifty-logo/chola.jpg'),
(15, 'Cummins India Ltd.', 'www.cummins.com', 'Columbus, Indiana, United States', 1919, 'INDUSTRIAL MANUFACTURING', 'CUMMINSIND', 'EQ', 'INE298A01020', 'resources/img/nifty-logo/cummins.png'),
(16, 'Escorts Ltd.', 'www.escortsgroup.com', 'Faridabad, India', 1960, 'AUTOMOBILE', 'ESCORTS', 'EQ', 'INE042A01014', 'resources/img/nifty-logo/escort.png'),
(17, 'Exide Industries Ltd.', 'www.exideindustries.com', 'Kolkata, West Bengal, India', 1947, 'AUTOMOBILE', 'EXIDEIND', 'EQ', 'INE302A01020', 'resources/img/nifty-logo/exide.webp'),
(18, 'Federal Bank Ltd.', 'www.federalbank.co.in', 'Aluva, Kochi, Kerala, India', 1949, 'FINANCIAL SERVICES', 'FEDERALBNK', 'EQ', 'INE171A01029', 'resources/img/nifty-logo/federal.png'),
(19, 'GMR Infrastructure Ltd.', 'www.gmrgroup.in', 'New Delhi, India', 1978, 'CONSTRUCTION', 'GMRINFRA', 'EQ', 'INE776C01039', 'resources/img/nifty-logo/GMR.png'),
(20, 'Glenmark Pharmaceuticals Ltd.', 'www.glenmarkpharma.com', 'Mumbai, India', 1977, 'PHARMA', 'GLENMARK', 'EQ', 'INE935A01035', 'resources/img/nifty-logo/glenmark.png'),
(21, 'Hexaware Technologies Ltd.', 'www.hexaware.com', 'Mumbai, India', 1990, 'IT', 'HEXAWARE', 'EQ', 'INE093A01033', 'resources/img/nifty-logo/hexaware.jfiif'),
(22, 'IDFC First Bank Ltd.', 'www.idfcfirstbank.com', 'Mumbai, India', 2015, 'FINANCIAL SERVICES', 'IDFCFIRSTB', 'EQ', 'INE092T01019', 'resources/img/nifty-logo/idfc.png'),
(23, 'Indraprastha Gas Ltd.', 'www.iglonline.net', 'New Delhi, India', 1998, 'ENERGY', 'IGL', 'EQ', 'INE203G01027', 'resources/img/nifty-logo/indraprastha.png'),
(24, 'Info Edge (India) Ltd.', 'www.infoedge.com', 'India', 1995, 'IT', 'NAUKRI', 'EQ', 'INE663F01024', 'resources/img/nifty-logo/infoedge.png'),
(25, 'Jindal Steel & Power Ltd.', 'www.jindalsteelpower.com', 'New Delhi, India[', 1952, 'METALS', 'JINDALSTEL', 'EQ', 'INE749A01030', 'resources/img/nifty-logo/jindal.png'),
(26, 'Jubilant Foodworks Ltd.', 'www.jubilantfoodworks.com', 'Noida, Uttar Pradesh, India', 1995, 'CONSUMER GOODS', 'JUBLFOOD', 'EQ', 'INE797F01012', 'resources/img/nifty-logo/jubilant.jfif'),
(27, 'LIC Housing Finance Ltd.', 'www.lichousing.com', 'Mumbai, India', 1989, 'FINANCIAL SERVICES', 'LICHSGFIN', 'EQ', 'INE115A01026', 'resources/img/nifty-logo/lic.jfif'),
(28, 'MRF Ltd.', 'www.mrftyres.com', 'Chennai, Tamilnadu, India', 1946, 'AUTOMOBILE', 'MRF', 'EQ', 'INE883A01011', 'resources/img/nifty-logo/mrf.png'),
(29, 'Mahanagar Gas Ltd.', 'www.mahanagargas.com', 'Mumbai, Maharashtra, India', 1995, 'ENERGY', 'MGL', 'EQ', 'INE002S01010', 'resources/img/nifty-logo/mahanagar.jpg'),
(30, 'Mahindra & Mahindra Financial ', 'www.mahindrafinance.com', 'Mumbai, Maharashtra, India', 1991, 'FINANCIAL SERVICES', 'M&MFIN', 'EQ', 'INE774D01024', 'resources/img/nifty-logo/mahindra.png'),
(31, 'Manappuram Finance Ltd.', 'www.manappuram.com', 'Mumbai, Maharashtra, India', 1949, 'FINANCIAL SERVICES', 'MANAPPURAM', 'EQ', 'INE522D01027', 'resources/img/nifty-logo/manapurram.jpg'),
(32, 'Max Financial Services Ltd.', 'www.maxfinancialservices.com', 'India', 1988, 'FINANCIAL SERVICES', 'MFSL', 'EQ', 'INE180A01020', 'resources/img/nifty-logo/max.png'),
(33, 'MindTree Ltd.', 'www.mindtree.com', '	Bangalore, India', 1999, 'IT', 'MINDTREE', 'EQ', 'INE018I01017', 'resources/img/nifty-logo/mindtree.jpg'),
(34, 'Muthoot Finance Ltd.', 'www.muthootgroup.com', 'Kochi, Kerala, India', 1887, 'FINANCIAL SERVICES', 'MUTHOOTFIN', 'EQ', 'INE414G01012', 'resources/img/nifty-logo/muthoot.jpg'),
(35, 'NBCC (India) Ltd.', 'www.nbccindia.com', 'Delhi, India', 1960, 'CONSTRUCTION', 'NBCC', 'EQ', 'INE095N01031', 'resources/img/nifty-logo/nbcc.webp'),
(36, 'National Aluminium Co. Ltd.', 'www.nalcoindia.com', 'Bhubaneswar, Odisha, India', 1981, 'METALS', 'NATIONALUM', 'EQ', 'INE139A01034', 'resources/img/nifty-logo/nalco.png'),
(37, 'Oil India Ltd.', 'www.oil-india.com', '	Duliajan, Assam, India', 1959, 'ENERGY', 'OIL', 'EQ', 'INE274J01014', 'resources/img/nifty-logo/oil.png'),
(38, 'RBL Bank Ltd.', 'www.rblbank.com', 'Mumbai, Maharashtra, India', 1943, 'FINANCIAL SERVICES', 'RBLBANK', 'EQ', 'INE976G01028', 'resources/img/nifty-logo/rbl.png'),
(39, 'REC Ltd.', 'www.recindia.nic.in', 'New Delhi, India', 1969, 'FINANCIAL SERVICES', 'RECLTD', 'EQ', 'INE020B01018', 'resources/img/nifty-logo/rec.png'),
(40, 'SRF Ltd.', 'www.srf.com', 'Gurgaon, India', 1970, 'TEXTILES', 'SRF', 'EQ', 'INE647A01010', 'resources/img/nifty-logo/srf.jfif'),
(41, 'Steel Authority of India Ltd.', 'www.sail.co.in', 'New Delhi, India', 1954, 'METALS', 'SAIL', 'EQ', 'INE114A01011', 'resources/img/nifty-logo/sail.jpg'),
(42, 'Sun TV Network Ltd.', 'www.suntv.in', 'Chennai, Tamil Nadu, India', 1991, 'MEDIA & ENTERTAINMENT', 'SUNTV', 'EQ', 'INE424H01027', 'resources/img/nifty-logo/sun.jfif'),
(43, 'TVS Motor Company Ltd.', 'www.tvsmotor.com', 'Chennai, Tamil Nadu, India', 1978, 'AUTOMOBILE', 'TVSMOTOR', 'EQ', 'INE494B01023', 'resources/img/nifty-logo/tvs.png'),
(44, 'Tata Global Beverages Ltd.', 'www.tataconsumer.com', 'Kolkata, West Bengal, India', 1964, 'CONSUMER GOODS', 'TATAGLOBAL', 'EQ', 'INE192A01025', 'resources/img/nifty-logo/tp.jpg'),
(45, 'Tata Power Co. Ltd.', 'www.tatapower.com', 'Mumbai, Maharashtra, India', 1919, 'ENERGY', 'TATAPOWER', 'EQ', 'INE245A01021', 'resources/img/nifty-logo/tp.jpg'),
(46, 'The Ramco Cements Ltd.', 'www.ramcocements.in', 'Chennai, India', 1961, 'CEMENT & CEMENT PRODUCTS', 'RAMCOCEM', 'EQ', 'INE331A01037', 'resources/img/nifty-logo/ramco.jfif'),
(47, 'Torrent Pharmaceuticals Ltd.', 'www.torrentpharma.com', 'Ahmedabad, India', 1959, 'PHARMA', 'TORNTPHARM', 'EQ', 'INE685A01028', 'resources/img/nifty-logo/torrent.jpg'),
(48, 'Torrent Power Ltd.', 'www.torrentpower.com', 'Ahmedabad, India', 1996, 'ENERGY', 'TORNTPOWER', 'EQ', 'INE813H01021', 'resources/img/nifty-logo/torrentp.png'),
(49, 'Union Bank of India', 'www.unionbankofindia.co.in', 'Mumbai, India', 1919, 'FINANCIAL SERVICES', 'UNIONBANK', 'EQ', 'INE692A01016', 'resources/img/nifty-logo/union.jfif'),
(50, 'Voltas Ltd.', 'www.voltas.com', 'India', 1954, 'CONSUMER GOODS', 'VOLTAS', 'EQ', 'INE226A01021', 'resources/img/nifty-logo/voltas.png');

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
(8, 'Yash', 'Gor', 20, 'gor@gmail.com', 'yash_gor', 'Y1234', 'resources/img/profile/g.png');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `User_ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `prediction`
--
ALTER TABLE `prediction`
  MODIFY `Prediction_ID` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_details`
--
ALTER TABLE `stock_details`
  MODIFY `Comp_ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `s_c_details`
--
ALTER TABLE `s_c_details`
  MODIFY `Comp_ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `User_ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
