-- phpMyAdmin SQL Dump
-- version 3.4.10.2
-- http://www.phpmyadmin.net
--
-- 호스트: localhost
-- 처리한 시간: 13-05-15 20:43 
-- 서버 버전: 5.1.34
-- PHP 버전: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 데이터베이스: `godotech05`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `gd_manual`
--

DROP TABLE IF EXISTS `gd_manual`;
CREATE TABLE IF NOT EXISTS `gd_manual` (
  `m_index` int(11) NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `m_division_code` varchar(100) NOT NULL COMMENT '부서 관계 데이터 구분자("|")',
  `m_category_code` varchar(100) NOT NULL COMMENT '카테고리 관계 데이터 구분자("|")',
  `m_solution_code` varchar(100) NOT NULL COMMENT '솔루션 관계 데이터 구분자("|")',
  `mm_index` int(11) NOT NULL COMMENT '작성자 일련번호',
  `m_name` varchar(20) NOT NULL COMMENT '작성자 이름',
  `m_mail` varchar(200) DEFAULT NULL COMMENT '작성자 메일 주소',
  `m_subject` varchar(200) NOT NULL COMMENT '제목',
  `m_contents` text NOT NULL COMMENT '내용',
  `m_fileupload` varchar(100) DEFAULT NULL COMMENT '첩부파일',
  `m_patchFl` enum('y','n') NOT NULL DEFAULT 'n' COMMENT '패치여부',
  `m_hit` int(11) NOT NULL COMMENT '조회수',
  `m_deleteFl` enum('y','n') NOT NULL DEFAULT 'n' COMMENT '삭제 여부',
  `m_ip` varchar(15) NOT NULL DEFAULT '000.000.000.000' COMMENT '등록, 수정, 삭제 IP',
  `m_editdate` datetime NOT NULL COMMENT '수정,삭제일',
  `m_writedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록일',
  PRIMARY KEY (`m_index`),
  KEY `mm_index` (`mm_index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='매뉴얼 관리 테이블' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 테이블 구조 `gd_manual_admin_menu`
--

DROP TABLE IF EXISTS `gd_manual_admin_menu`;
CREATE TABLE IF NOT EXISTS `gd_manual_admin_menu` (
  `mam_index` int(11) NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `mam_code` varchar(10) NOT NULL COMMENT '메뉴 관리 코드',
  `mam_subject` varchar(100) NOT NULL COMMENT '메뉴명',
  `mam_url` varchar(1000) DEFAULT NULL COMMENT '메뉴경로',
  `mam_parent` int(11) NOT NULL DEFAULT '0' COMMENT '상위메뉴 일련번호',
  `mam_sort` int(11) NOT NULL DEFAULT '0' COMMENT '메뉴정렬 순서',
  `mam_viewFl` enum('y','n') NOT NULL DEFAULT 'y' COMMENT '메뉴 보임 여부',
  `mam_useFl` enum('y','n') NOT NULL DEFAULT 'y' COMMENT '메뉴 사용 여부',
  `mam_linktype` varchar(50) DEFAULT NULL COMMENT '링크 타입',
  `mam_width` varchar(4) DEFAULT NULL COMMENT '메뉴 가로 사이즈',
  `mam_height` varchar(4) DEFAULT NULL COMMENT '메뉴 세로 사이즈',
  `mam_usetype` enum('a','u') NOT NULL DEFAULT 'a' COMMENT '사용자(''u''),관리자(''a'') 구분',
  `mam_login` enum('c','y','n') NOT NULL COMMENT '로그인 체크여부',
  `mam_button` varchar(50) DEFAULT NULL COMMENT '버튼 활성화 여부',
  `mam_deleteFl` enum('y','n') NOT NULL DEFAULT 'n' COMMENT '삭제여부',
  `mam_ip` varchar(15) NOT NULL DEFAULT '000.000.000.000' COMMENT '등록, 수정, 삭제 IP',
  `mam_editdate` datetime DEFAULT NULL COMMENT '수정, 삭제 일자',
  `mam_writedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록 일자',
  PRIMARY KEY (`mam_index`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='관리자 페이지 메뉴관리 테이블' AUTO_INCREMENT=27 ;

--
-- 테이블의 덤프 데이터 `gd_manual_admin_menu`
--

INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(1, 'menu', '메뉴 관리', '', 0, 99, 'y', 'y', '1', NULL, NULL, 'a', 'n', 'Y,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(2, 'menu', '메뉴 관리 페이지', '../_systool/menu/menu.php', 1, 1, 'y', 'y', '1', NULL, NULL, 'a', 'y', 'N,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(3, 'auth', '권한 관리', '/auth/', 0, 2, 'y', 'y', '', NULL, NULL, 'a', 'y', 'N,N,N,N,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(5, 'member', '회원 관리', '/member/', 0, 1, 'y', 'y', '', NULL, NULL, 'a', 'y', 'N,N,N,N,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(6, 'member', '회원 리스트', '../../_systool/member/_list.php', 5, 1, 'y', 'y', '1', NULL, NULL, 'a', 'y', 'Y,N,Y,N,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(9, 'division', '부서관리', '', 0, 5, 'y', 'y', '', NULL, NULL, 'a', 'y', 'Y,N,Y,N,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(8, 'auth', '권한 리스트', '../../_systool/auth/_list.php', 3, 1, 'y', 'y', '1', NULL, NULL, 'a', 'y', 'Y,N,Y,N,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(10, 'division', '부서 리스트', '../../_systool/division/_list.php', 9, 1, 'y', 'y', '1', NULL, NULL, 'a', 'y', 'Y,N,Y,N,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(11, 'division', '부서 상세 페이지', '../../_systool/division/_view.php', 9, 1, 'n', 'y', '1', NULL, NULL, 'a', 'y', 'Y,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(12, 'solution', '솔루션관리', '', 0, 3, 'y', 'y', '', NULL, NULL, 'a', 'y', 'Y,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(13, 'solution', '솔루션리스트', '../../_systool/solution/_list.php', 12, 1, 'y', 'y', '1', NULL, NULL, 'a', 'y', 'Y,N,Y,N,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(14, 'category', '카테고리관리', '', 0, 4, 'y', 'y', '', NULL, NULL, 'a', 'y', 'Y,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(15, 'category', '카테고리리스트', '../../_systool/category/_list.php', 14, 1, 'y', 'y', '', NULL, NULL, 'a', 'y', 'Y,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(16, 'solution', '솔루션 상세 페이지', '../../_systool/solution/_view.php', 12, 2, 'n', 'y', '1', NULL, NULL, 'a', 'y', 'Y,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(17, 'member', '회원 상세 페이지', '../../_systool/member/_view.php', 5, 1, 'n', 'y', '1', NULL, NULL, 'a', 'y', 'Y,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(18, 'category', '카테고리 상세 페이지', '../../_systool/category/_view.php', 14, 2, 'n', 'y', '1', NULL, NULL, 'a', 'y', 'Y,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(19, 'auth', '권한 관리 상세 페이지', '../../_systool/auth/_view.php', 3, 2, 'n', 'y', '1', NULL, NULL, 'a', 'y', 'Y,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(20, 'manual', '메뉴얼 관리', '/manual/', 0, 6, 'y', 'y', '', NULL, NULL, 'a', 'y', 'Y,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(21, 'manual', '메뉴얼 리스트', '../../_systool/manual/_list.php', 20, 1, 'y', 'y', '1', NULL, NULL, 'a', 'y', 'Y,N,Y,N,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(22, 'manual', '메뉴얼 상세 페이지', '../../_systool/manual/_view.php', 20, 2, 'n', 'y', '1', NULL, NULL, 'a', 'y', 'Y,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_admin_menu` (`mam_index`, `mam_code`, `mam_subject`, `mam_url`, `mam_parent`, `mam_sort`, `mam_viewFl`, `mam_useFl`, `mam_linktype`, `mam_width`, `mam_height`, `mam_usetype`, `mam_login`, `mam_button`, `mam_deleteFl`, `mam_ip`, `mam_editdate`, `mam_writedate`) VALUES(26, 'manual', '메뉴얼 답글 작성 페이지', '../../_systool/manual/mpp_write.php', 20, 3, 'n', 'y', '1', NULL, NULL, 'a', 'y', 'Y,Y,Y,Y,N,N,N', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 테이블 구조 `gd_manual_auth`
--

DROP TABLE IF EXISTS `gd_manual_auth`;
CREATE TABLE IF NOT EXISTS `gd_manual_auth` (
  `ma_index` int(11) NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `ma_code` varchar(10) NOT NULL COMMENT '권한코드',
  `ma_division_code` varchar(100) NOT NULL COMMENT '부서 코드 데이터 구분자(''|'')',
  `ma_category_code` varchar(100) NOT NULL COMMENT '카테고리 코드 데이터 구분자(''|'')',
  `ma_menu_code` varchar(100) NOT NULL COMMENT '메뉴 권한 코드',
  `ma_name` varchar(30) NOT NULL COMMENT '권한 명칭',
  `ma_deleteFl` enum('y','n') NOT NULL DEFAULT 'n' COMMENT '삭제여부',
  `ma_ip` varchar(15) NOT NULL DEFAULT '000.000.000.000' COMMENT '등록, 수정, 삭제 IP',
  `ma_editdate` datetime NOT NULL COMMENT '수정, 삭제 일자',
  `ma_writedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록 일자',
  PRIMARY KEY (`ma_index`),
  UNIQUE KEY `ma_code` (`ma_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='권한 관리 테이블' AUTO_INCREMENT=3 ;

--
-- 테이블의 덤프 데이터 `gd_manual_auth`
--

INSERT INTO `gd_manual_auth` (`ma_index`, `ma_code`, `ma_division_code`, `ma_category_code`, `ma_menu_code`, `ma_name`, `ma_deleteFl`, `ma_ip`, `ma_editdate`, `ma_writedate`) VALUES(1, 'A', '|TS|', '', '|menu|auth|member|division|solution|category|manual|', '관리자 권한', 'n', '61.36.175.188', '2013-05-10 11:51:45', '2013-04-14 14:29:10');
INSERT INTO `gd_manual_auth` (`ma_index`, `ma_code`, `ma_division_code`, `ma_category_code`, `ma_menu_code`, `ma_name`, `ma_deleteFl`, `ma_ip`, `ma_editdate`, `ma_writedate`) VALUES(2, 'TS', '|TS|', '', '|solution|category|manual|', '기술지원팀 권한', 'n', '61.36.175.188', '0000-00-00 00:00:00', '2013-05-15 20:38:38');

-- --------------------------------------------------------

--
-- 테이블 구조 `gd_manual_category`
--

DROP TABLE IF EXISTS `gd_manual_category`;
CREATE TABLE IF NOT EXISTS `gd_manual_category` (
  `mc_index` int(11) NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `mc_parent` int(11) NOT NULL DEFAULT '0' COMMENT '상위카테고리 일련번호',
  `mc_depth` int(11) NOT NULL DEFAULT '1' COMMENT '카테고리 레벨',
  `mc_sort` int(11) NOT NULL DEFAULT '0' COMMENT '정렬순서',
  `mc_code` varchar(10) NOT NULL COMMENT '카테고리 코드',
  `mc_name` varchar(30) NOT NULL COMMENT '카테고리명',
  `mc_deleteFl` enum('y','n') NOT NULL COMMENT '삭제여부',
  `mc_ip` varchar(15) NOT NULL DEFAULT '000.000.000.000' COMMENT '등록, 수정, 삭제 IP',
  `mc_editdate` datetime DEFAULT NULL COMMENT '수정, 삭제 일자',
  `mc_writedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록 일자',
  PRIMARY KEY (`mc_index`),
  UNIQUE KEY `mc_code` (`mc_code`),
  UNIQUE KEY `mc_category_unique` (`mc_parent`,`mc_depth`,`mc_name`,`mc_deleteFl`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 테이블 구조 `gd_manual_division`
--

DROP TABLE IF EXISTS `gd_manual_division`;
CREATE TABLE IF NOT EXISTS `gd_manual_division` (
  `md_index` int(11) NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `md_code` varchar(10) NOT NULL COMMENT '부서코드',
  `md_name` varchar(30) NOT NULL COMMENT '부서명',
  `md_deleteFl` enum('y','n') NOT NULL COMMENT '삭제여부',
  `md_ip` varchar(15) NOT NULL DEFAULT '000.000.000.000' COMMENT '등록, 수정, 삭제IP',
  `md_editdate` datetime DEFAULT NULL COMMENT '수정, 삭제 일자',
  `md_writedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록 일자',
  PRIMARY KEY (`md_index`),
  UNIQUE KEY `md_code` (`md_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='부서 관리 테이블' AUTO_INCREMENT=10 ;

--
-- 테이블의 덤프 데이터 `gd_manual_division`
--

INSERT INTO `gd_manual_division` (`md_index`, `md_code`, `md_name`, `md_deleteFl`, `md_ip`, `md_editdate`, `md_writedate`) VALUES(9, 'TS', '기술지원팀', 'n', '61.36.175.188', '2013-05-10 10:44:01', '2013-04-10 22:05:27');

-- --------------------------------------------------------

--
-- 테이블 구조 `gd_manual_member`
--

DROP TABLE IF EXISTS `gd_manual_member`;
CREATE TABLE IF NOT EXISTS `gd_manual_member` (
  `mm_index` int(11) NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `ma_code` varchar(10) NOT NULL COMMENT '권한 코드 식별자',
  `md_code` varchar(10) NOT NULL COMMENT '부서 코드 식별자',
  `mm_id` varchar(200) NOT NULL COMMENT '아이디(이메일주소)',
  `mm_name` varchar(20) NOT NULL COMMENT '회원 이름',
  `mm_password` varchar(41) NOT NULL COMMENT '비밀번호',
  `mm_deleteFl` enum('y','n') NOT NULL DEFAULT 'n' COMMENT '삭제여부',
  `mm_loginip` varchar(15) DEFAULT NULL COMMENT '로그인 IP',
  `mm_logindate` datetime DEFAULT NULL COMMENT '로그인 일자',
  `mm_editip` varchar(15) NOT NULL DEFAULT '000.000.000.000' COMMENT '등록, 수정, 삭제 IP',
  `mm_editdate` datetime DEFAULT NULL COMMENT '수정, 삭제 일자',
  `mm_writedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록일',
  PRIMARY KEY (`mm_index`),
  KEY `ma_code` (`ma_code`),
  KEY `md_code` (`md_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 테이블의 덤프 데이터 `gd_manual_member`
--

INSERT INTO `gd_manual_member` (`mm_index`, `ma_code`, `md_code`, `mm_id`, `mm_name`, `mm_password`, `mm_deleteFl`, `mm_loginip`, `mm_logindate`, `mm_editip`, `mm_editdate`, `mm_writedate`) VALUES(1, 'A', 'TS', 'artherot@godo.co.kr', '신동규', '*4ACFE3202A5FF5CF467898FC58AAB1D615029441', 'n', NULL, NULL, '61.36.175.190', '2013-04-15 19:44:15', '0000-00-00 00:00:00');
INSERT INTO `gd_manual_member` (`mm_index`, `ma_code`, `md_code`, `mm_id`, `mm_name`, `mm_password`, `mm_deleteFl`, `mm_loginip`, `mm_logindate`, `mm_editip`, `mm_editdate`, `mm_writedate`) VALUES(2, 'A', 'TS', 'chnsky@godo.co.kr', '최하늘', '*8407CB87BAFC68CA978EDE2A6E4F37B83AFAA1A7', 'n', NULL, NULL, '61.36.175.188', '2013-04-14 15:24:13', '0000-00-00 00:00:00');
INSERT INTO `gd_manual_member` (`mm_index`, `ma_code`, `md_code`, `mm_id`, `mm_name`, `mm_password`, `mm_deleteFl`, `mm_loginip`, `mm_logindate`, `mm_editip`, `mm_editdate`, `mm_writedate`) VALUES(3, 'A', 'TS', 'hym1987@godo.co.kr', '한영민', '*A2F82F8AF762E516CFB95C8AF9279765D4E6A802', 'n', NULL, NULL, '61.36.175.188', '2013-04-27 13:00:49', '0000-00-00 00:00:00');
INSERT INTO `gd_manual_member` (`mm_index`, `ma_code`, `md_code`, `mm_id`, `mm_name`, `mm_password`, `mm_deleteFl`, `mm_loginip`, `mm_logindate`, `mm_editip`, `mm_editdate`, `mm_writedate`) VALUES(4, 'A', 'TS', 'tomi124@godo.co.kr', '박태준', '*8734504C2952C2679D32E48E27A4FF939E5F6C9E', 'n', NULL, NULL, '61.36.175.188', '2013-05-13 18:35:46', '0000-00-00 00:00:00');
INSERT INTO `gd_manual_member` (`mm_index`, `ma_code`, `md_code`, `mm_id`, `mm_name`, `mm_password`, `mm_deleteFl`, `mm_loginip`, `mm_logindate`, `mm_editip`, `mm_editdate`, `mm_writedate`) VALUES(5, 'TS', 'TS', 'jsseo@godo.co.kr', '서진선', '*9F0B4E3F78D6C302DFC00059831B0B68349F9F2E', 'n', NULL, NULL, '61.36.175.188', '2013-04-14 15:24:37', '0000-00-00 00:00:00');
INSERT INTO `gd_manual_member` (`mm_index`, `ma_code`, `md_code`, `mm_id`, `mm_name`, `mm_password`, `mm_deleteFl`, `mm_loginip`, `mm_logindate`, `mm_editip`, `mm_editdate`, `mm_writedate`) VALUES(6, 'TS', 'TS', 'takuteru@godo.co.kr', '구양은', '*69C4365702C66583CEFC073ED51ACEEF5FE79749', 'n', NULL, NULL, '61.36.175.188', '2013-04-14 15:24:46', '0000-00-00 00:00:00');
INSERT INTO `gd_manual_member` (`mm_index`, `ma_code`, `md_code`, `mm_id`, `mm_name`, `mm_password`, `mm_deleteFl`, `mm_loginip`, `mm_logindate`, `mm_editip`, `mm_editdate`, `mm_writedate`) VALUES(7, 'TS', 'TS', 'mijungnim@godo.co.kr', '심미정', '*0C1A1F5876756153286FCCC594723DF4C51C3E1C', 'n', NULL, NULL, '61.36.175.188', '2013-04-14 15:25:03', '0000-00-00 00:00:00');
INSERT INTO `gd_manual_member` (`mm_index`, `ma_code`, `md_code`, `mm_id`, `mm_name`, `mm_password`, `mm_deleteFl`, `mm_loginip`, `mm_logindate`, `mm_editip`, `mm_editdate`, `mm_writedate`) VALUES(8, 'TS', 'TS', 'bumyul2000@godo.co.kr', '윤범열', '*F185BDFD81D84957397D0D336506C73A88A1C7E2', 'n', NULL, NULL, '61.36.175.188', '2013-04-14 15:25:10', '0000-00-00 00:00:00');
INSERT INTO `gd_manual_member` (`mm_index`, `ma_code`, `md_code`, `mm_id`, `mm_name`, `mm_password`, `mm_deleteFl`, `mm_loginip`, `mm_logindate`, `mm_editip`, `mm_editdate`, `mm_writedate`) VALUES(9, 'TS', 'TS', 'loyfoever@godo.co.kr', '이우영', '*9C208E7D5302A90921B6A6B0F2EB2940EEBF55AA', 'n', NULL, NULL, '61.36.175.188', '2013-04-14 15:25:18', '0000-00-00 00:00:00');
INSERT INTO `gd_manual_member` (`mm_index`, `ma_code`, `md_code`, `mm_id`, `mm_name`, `mm_password`, `mm_deleteFl`, `mm_loginip`, `mm_logindate`, `mm_editip`, `mm_editdate`, `mm_writedate`) VALUES(10, 'TS', 'TS', 'gofud89@godo.co.kr', '이해령', '*A93A52BE0235CD4D52B2F0D8E168895CB5D1210D', 'n', NULL, NULL, '61.36.175.188', '2013-04-14 15:25:42', '0000-00-00 00:00:00');
INSERT INTO `gd_manual_member` (`mm_index`, `ma_code`, `md_code`, `mm_id`, `mm_name`, `mm_password`, `mm_deleteFl`, `mm_loginip`, `mm_logindate`, `mm_editip`, `mm_editdate`, `mm_writedate`) VALUES(11, 'TS', 'TS', 'jonr@godo.co.kr', '조나리', '*CBE16A0E8BAEB6F5A56AA672F6E11042D21BCA7C', 'n', NULL, NULL, '61.36.175.188', '2013-04-14 15:25:50', '0000-00-00 00:00:00');
INSERT INTO `gd_manual_member` (`mm_index`, `ma_code`, `md_code`, `mm_id`, `mm_name`, `mm_password`, `mm_deleteFl`, `mm_loginip`, `mm_logindate`, `mm_editip`, `mm_editdate`, `mm_writedate`) VALUES(12, 'TS', 'TS', 'hyun8863@godo.co.kr', '최현영', '*2BB95D6B9EFAE797436B80497BBD66B48E055F4A', 'n', NULL, NULL, '61.36.175.188', '2013-04-14 15:29:17', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 테이블 구조 `gd_manual_process_patch`
--

DROP TABLE IF EXISTS `gd_manual_process_patch`;
CREATE TABLE IF NOT EXISTS `gd_manual_process_patch` (
  `mpp_index` int(11) NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `m_index` int(11) NOT NULL COMMENT '매뉴얼 일련번호',
  `mpp_type` enum('r','a') NOT NULL DEFAULT 'r' COMMENT '프로세스(r), 패치(a) 구분',
  `mm_index` int(11) NOT NULL COMMENT '작성자 일련번호',
  `mpp_name` varchar(20) NOT NULL COMMENT '작성자명',
  `mpp_subject` varchar(200) NOT NULL COMMENT '제목',
  `mpp_contents` text COMMENT '내용',
  `mpp_patchurl` varchar(200) DEFAULT NULL COMMENT '패치경로',
  `mpp_deleteFl` enum('y','n') NOT NULL DEFAULT 'n' COMMENT '삭제여부',
  `mpp_ip` varchar(15) NOT NULL DEFAULT '000.000.000.000' COMMENT '등록, 수정, 삭제 IP',
  `mpp_editdate` datetime DEFAULT NULL COMMENT '수정, 삭제 일자',
  `mpp_writedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록 일자',
  PRIMARY KEY (`mpp_index`),
  KEY `m_index` (`m_index`),
  KEY `mm_index` (`mm_index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='매뉴얼 처리, 패치 관리 테이블' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 테이블 구조 `gd_manual_reply`
--

DROP TABLE IF EXISTS `gd_manual_reply`;
CREATE TABLE IF NOT EXISTS `gd_manual_reply` (
  `mr_index` int(11) NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `m_index` int(11) NOT NULL COMMENT '작성자 일련번호',
  `mr_name` varchar(20) NOT NULL COMMENT '작성자 이름',
  `mr_contents` mediumtext NOT NULL COMMENT '댓글 내용',
  `mr_deleteFl` enum('y','n') NOT NULL DEFAULT 'n' COMMENT '삭제여부',
  `mr_ip` varchar(15) NOT NULL DEFAULT '000.000.000.000' COMMENT '등록, 수정, 삭제 IP',
  `mr_editdate` datetime DEFAULT NULL COMMENT '수정, 삭제 일자',
  `mr_writedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록 일자',
  PRIMARY KEY (`mr_index`),
  KEY `m_index` (`m_index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='매뉴얼 댓글 관리 테이블' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 테이블 구조 `gd_manual_solution`
--

DROP TABLE IF EXISTS `gd_manual_solution`;
CREATE TABLE IF NOT EXISTS `gd_manual_solution` (
  `ms_index` int(11) NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `ms_code` varchar(10) NOT NULL COMMENT '솔루션 코드',
  `ms_name` varchar(30) NOT NULL COMMENT '솔루션 명',
  `ms_deleteFl` enum('y','n') NOT NULL DEFAULT 'n' COMMENT '삭제여부',
  `ms_ip` varchar(15) NOT NULL DEFAULT '000.000.000.000' COMMENT '등록, 수정, 삭제 IP',
  `ms_editdate` datetime DEFAULT NULL COMMENT '수정, 삭제 일자',
  `ms_writedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록 일자',
  PRIMARY KEY (`ms_index`),
  UNIQUE KEY `ms_code` (`ms_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- 테이블의 덤프 데이터 `gd_manual_solution`
--

INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(1, '1', 'Cube 독립형', 'n', '61.36.175.188', '2013-04-14 15:32:15', '2013-04-10 10:33:04');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(2, '2', 'e나무 스마트 무료형', 'n', '', '2013-04-10 10:32:55', '2013-04-10 10:32:55');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(3, '3', 'e나무 스마트 프리미엄', 'n', '', '2013-04-10 10:30:11', '2013-04-10 10:30:11');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(4, '4', 'e나무 무료형 시즌4(시즌3 무료)', 'n', '', '2013-04-10 10:29:57', '2013-04-10 10:29:57');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(5, '5', 'e나무 임대형 시즌4(시즌3 임대)', 'n', '', '2013-04-10 10:30:38', '2013-04-10 10:30:38');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(6, '6', 'e나무 독립형 시즌4', 'n', '', '2013-04-10 10:30:50', '2013-04-10 10:30:50');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(7, '7', 'e나무 독립형 시즌3', 'n', '', '2013-04-10 10:31:01', '2013-04-10 10:31:01');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(8, '8', 'e나무 독립형(일반) 시즌2', 'n', '', '2013-04-10 10:31:05', '2013-04-10 10:31:05');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(9, '9', 'e나무 독립형(패션) 시즌2', 'n', '', '2013-04-10 10:31:14', '2013-04-10 10:31:14');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(10, '10', ' e나무 무료형 시즌1', 'n', '000.000.000.000', NULL, '2013-04-10 00:00:00');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(11, '11', 'e나무 무제한(구 500) 시즌1', 'n', '', '2013-04-10 10:35:27', '0000-00-00 00:00:00');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(12, '12', ' e나무 무제한 시즌1', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(13, '13', 'e나무 독립형 시즌1', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(14, '14', '무료몰 500', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(15, '15', '업그레이드몰', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(16, '16', '날개Free 150', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(17, '17', '날개 500', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(18, '18', '날개 무제한', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(19, '19', 'ver4 Master', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(20, '20', '프라임', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');
INSERT INTO `gd_manual_solution` (`ms_index`, `ms_code`, `ms_name`, `ms_deleteFl`, `ms_ip`, `ms_editdate`, `ms_writedate`) VALUES(21, '21', '기타', 'n', '000.000.000.000', NULL, '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
