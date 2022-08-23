-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2022 at 08:21 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `invest`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `balance` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `greeting` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addresses` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `params` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shortcut` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `slug`, `subject`, `greeting`, `content`, `group`, `recipient`, `addresses`, `params`, `shortcut`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Investment Order Placed', 'investment-placed-customer', 'Invest on [[plan_name]] ([[order_id]])', 'Hello [[user_name]],', 'Thank you! You have invested the amount of [[invest_amount]] on \'[[plan_name]]\'. Your investment details are shown below for your reference:\n[[invest_details]] \n\nYour investment plan will start as soon as we have review and confirmed. \n\nFeel free to contact us if you have any questions.', 'investments', 'customer', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(2, 'Investment Order Placed', 'investment-placed-admin', 'Investment Plan ([[order_id]]) Purchased', 'Hello Admin,', 'A new investment plan purchased by [[order_by]]. The investment details as follows: \n[[invest_details]] \n\nCustomer Details:\n[[user_detail]] \n\nThis is an automatic email confirmation, please check full order details in dashboard.\n\nThank You.', 'investments', 'admin', NULL, '{\"regards\":\"off\"}', '[[user_detail]], [[order_id]], [[order_by]], [[plan_name]], [[order_time]], [[invest_amount]], [[invest_details]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(3, 'Investment Order Approved', 'investment-approved-customer', 'Investment plan ([[order_id]]) just started!', 'Dear [[user_name]],', 'Congratulations! Your investment plan ([[order_id]]) approved and successfully started. \nYour investment details are shown below for your reference:\n[[invest_details]]\n\nIf you have any question, you can contact us at [[site_email]].', 'investments', 'customer', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(4, 'Investment Order Approved', 'investment-approved-admin', 'Investment plan ([[order_id]]) just started!', 'Dear Admin,', 'The investment order ([[order_id]]) has been approved and started. The investment details as follows: \n[[invest_details]] \n\nPS. Do not reply to this email.\nThank you.', 'investments', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]', 'inactive', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(5, 'Investment Cancelled by User', 'investment-cancel-user-customer', 'Investment plan ([[order_id]]) has been cancelled!', 'Dear [[user_name]],', 'You have cancelled your investment plan ([[order_id]]). The amount returned to your account balance. \n\nIf you want to invest again, please feel free to login into your account and choose a plan once again.\n\n\nIf you have any question, you can contact us at [[site_email]].', 'investments', 'customer', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(6, 'Investment Cancelled by User', 'investment-cancel-user-admin', 'Investment plan ([[order_id]]) successfully cancelled!', 'Dear Admin,', 'The recent investment plan ([[order_id]]) has been cancelled by [[order_by]]. The invested amount returned to user\'s account balance. \n\n\nThis is an automatic email confirmation, no further action is needed.\n\nPS. Do not reply to this email.\nThank you.', 'investments', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(7, 'Investment Cancelled by Admin', 'investment-cancel-customer', 'Investment plan ([[order_id]]) has been cancelled!', 'Dear [[user_name]],', 'Your recent investment plan ([[order_id]]) has been cancelled. The invested amount returned to your account balance. \n\nIf you want to invest again, please feel free to login into your account and choose a plan once again.\n\n\nIf you have any question, you can contact us at [[site_email]].', 'investments', 'customer', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]', 'inactive', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(8, 'Investment Cancelled by Admin', 'investment-cancel-admin', 'Investment plan ([[order_id]]) successfully cancelled!', 'Dear Admin,', 'The investment order ([[order_id]]) has been cancelled. The invested amount returned to user\'s account balance. \n\nPS. Do not reply to this email.\nThank you.', 'investments', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]', 'inactive', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(9, 'Investment Cancellation by Admin', 'investment-cancellation-customer', 'Investment Cancellation ([[order_id]])', 'Dear [[user_name]],', 'We are sorry to inform you that we\'ve cancelled your investment plan of ([[plan_name]]). We have settlement your investment account balance. Please login into your account and check your account balance.\n\n\nIf you have any question about cancellation, please feel free to contact us.\n\nPS. Do not reply to this email.\nThank you.', 'investments', 'customer', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(10, 'Investment Cancellation by Admin', 'investment-cancellation-admin', 'Investment Cancellation ([[order_id]])', 'Dear [[user_name]],', 'The investment plan of ([[plan_name]] - [[order_id]]) has been cancelled. User account balance adjusted with invested amount.\n\nPS. Do not reply to this email.\nThank you.', 'investments', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_time]], [[plan_name]], [[invest_amount]], [[invest_details]]', 'inactive', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(11, 'Email Confirmation', 'users-confirm-email', 'Verify Your Email Address - [[site_name]]', 'Welcome [[user_name]]!', 'Thank you for registering on our platform. You\'re almost ready to start.\n\nSimply click the button below to confirm your email address and active your account.', 'authentication', 'customer', NULL, '{\"regards\":\"on\"}', '', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(12, 'Welcome Email', 'users-welcome-email', 'Welcome to [[site_name]]', 'Hi [[user_name]],', 'Thanks for joining our platform! \n\nAs a member of our platform, you can mange your account, buy or sell cryptocurrency. \n\nFind out more about in - [[site_url]]', 'authentication', 'customer', NULL, '{\"regards\":\"on\"}', '', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(13, 'Password Reset by User', 'users-reset-password', 'Password Reset Request on [[site_name]]', 'Hi [[user_name]],', '<strong>You told us you forgot your password.</strong> \n\nIf you really forgot, click the below button to reset your password. \n\nIf you did not make reset request, then you can just ignore this email; your password will not change.', 'authentication', 'customer', NULL, '{\"regards\":\"on\"}', '', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(14, 'Password Changed Successfully', 'users-change-password-success', 'Your Password Has Been Changed', 'Hi [[user_name]],', 'This email is to confirm that your account password has been successfully changed. If you did not request a password change, please contact us immediately.', 'authentication', 'customer', NULL, '{\"regards\":\"on\"}', '', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(15, 'Email Changed by User', 'users-change-email', 'Verify Your New Email Address - [[site_name]]', 'Hi [[user_name]],', '<strong>There was a request to change your email address.</strong> \n\nIf you really want to change your email, simply click the button below to confirm your new email address. \n\nIf you did not make this change, then you can just ignore this email; your email will not change.', 'authentication', 'customer', NULL, '{\"regards\":\"on\"}', '', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(16, 'Email Changed Successfully', 'users-change-email-success', 'Email Address Has Been Changed', 'Hi [[user_name]],', 'This email is to confirm that your account email address has been successfully changed. Now you can login at [[site_url]] with your new email address. If you did not make this change, please contact us immediately.', 'authentication', 'customer', NULL, '{\"regards\":\"on\"}', '', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(17, 'Unusual Login Email', 'users-unusual-login', 'Unusual Login Attempt on [[site_name]]', 'Hi [[user_name]],', 'We noticed you\'re having trouble logging into your account. There was few unsuccessful login attempt on your account. If this wasn\'t you, let us know.', 'authentication', 'customer', NULL, '{\"regards\":\"on\"}', '', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(18, 'Password Reset by Admin', 'users-admin-reset-password', 'Your Password is reseted on [[site_name]]', 'Hi [[user_name]],', 'We have reset your login password as per your requested via support. Now you can login at [[site_url]] with new password as below.', 'authentication', 'customer', NULL, '{\"regards\":\"on\"}', '', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(19, 'Welcome Email', 'user-registration-admin', 'Welcome to [[site_name]]', 'Hi [[user_name]],', 'You are receiving this email because you have registered on our site.', 'authentication', 'customer', NULL, '{\"regards\":\"on\"}', '', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(20, 'Deposit Order Placed', 'deposit-placed-customer', 'New Deposit #[[order_id]]', 'Hello [[user_name]],', 'Your deposit order has been placed and is now being waiting for payment. Your deposit details are shown below for your reference:\n[[order_details]]\n[[payment_information]]\n\nYour funds will add into your account as soon as we have confirmed the payment. \n\nFeel free to contact us if you have any questions.', 'deposits', 'customer', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]], [[payment_information]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(21, 'Deposit Order Placed', 'deposit-placed-admin', 'New Deposit #[[order_id]] by [[order_by]]', 'Hello Admin,', 'You have received an deposit order from [[order_by]]. The deposit order is as follows: \n[[order_details]] \n\nCustomer Details:\n[[user_detail]] \n\nThis is an automatic email confirmation, please check full order details in dashboard.\n\nThank You.', 'deposits', 'admin', NULL, '{\"regards\":\"off\"}', '[[user_detail]], [[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(22, 'Deposit Cancelled by User', 'deposit-cancel-user-customer', 'Deposit has been cancelled!', 'Dear [[user_name]],', 'Your recent deposit (#[[order_id]]) has been cancelled. \n\nIf you want to deposit funds into your again, please feel free to login into your account and add funds once again.\n\n\nIf you have any question, you can contact us at [[site_email]].', 'deposits', 'customer', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(23, 'Deposit Cancelled by User', 'deposit-cancel-user-admin', 'Deposit #[[order_id]] has been cancelled', 'Dear Admin,', 'The recent deposit order (#[[order_id]]) has been cancelled by [[order_by]]. \n\n\nThis is an automatic email confirmation, no need any action for further.\n\nPS. Do not reply to this email.\nThank you.', 'deposits', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(24, 'Deposit Cancelled by Gateway', 'deposit-cancel-gateway-customer', 'Payment Rejected - Deposit #[[order_id]]', 'Dear [[user_name]],', 'The deposit (#[[order_id]]) has been canceled, however the payment was not successful and [[payment_method]] rejected or cancelled the payment.\n\n\nIf you have any question, you can contact us at [[site_email]].\n\nPS. Do not reply to this email.\nThank you.', 'deposits', 'customer', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(25, 'Deposit Cancelled by Gateway', 'deposit-cancel-gateway-admin', 'Deposit #[[order_id]] has been cancelled', 'Dear Admin,', 'The recent deposit order (#[[order_id]]) has been cancelled by [[payment_method]], however the payment was not made. \n\n\nThis is an automatic email confirmation, no need any action for further.\n\nPS. Do not reply to this email.\nThank you.', 'deposits', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(26, 'Deposit Rejected by Admin', 'deposit-reject-customer', 'Cancelled Deposit #[[order_id]]', 'Dear [[user_name]],', 'The deposit (#[[order_id]]) has been cancelled, however we have not received your payment of [[order_amount]] (via [[payment_method]]).\n\n\nIf you have any question, you can contact us at [[site_email]].\n\nPS. Do not reply to this email.\nThank you.', 'deposits', 'customer', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]] , [[order_time]], [[order_details]], [[payment_method]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(27, 'Deposit Rejected by Admin', 'deposit-reject-admin', 'Deposit #[[order_id]] has been cancelled', 'Dear Admin,', 'The deposit order (#[[order_id]]) has been cancelled. \n\nPS. Do not reply to this email.\nThank you.', 'deposits', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]', 'inactive', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(28, 'Deposit Order Approved', 'deposit-approved-customer', 'Deposit successfully processed!', 'Dear [[user_name]],', 'Your deposit of [[order_amount]] has been successfully approved. \nThis email confirms that funds have been added to your account.\n\nIf you have any question, you can contact us at [[site_email]].', 'deposits', 'customer', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(29, 'Deposit Order Approved', 'deposit-approved-admin', 'Deposit Successfull - Order #[[order_id]]', 'Dear Admin,', 'The deposit order (#[[order_id]]) has been approved and funds of [[order_amount]] added into user account. \n\nPS. Do not reply to this email.\nThank you.', 'deposits', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]', 'inactive', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(30, 'Deposit Success by Gateway', 'deposit-success-gateway-customer', 'Deposit successfully processed!', 'Dear [[user_name]],', 'Your deposit of [[order_amount]] has been successfully. \nThis email confirms that funds have been added to your account.\n\nIf you have any question, you can contact us at [[site_email]].', 'deposits', 'customer', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(31, 'Deposit Success by Gateway', 'deposit-success-gateway-admin', 'Deposit Successfull - Order #[[order_id]]', 'Dear Admin,', 'You just received a payment of [[order_amount]] for deposit order (#[[order_id]]) via [[payment_method]]. \n\nPS. Do not reply to this email.\nThank you.', 'deposits', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(32, 'Deposit Refund by Admin', 'deposit-refund-customer', 'Your deposit (#[[order_id]]) has been refunded!', 'Hello [[user_name]],', 'We have refunded your funds and re-adjusted your account balance. Please find below your refund and original deposit details. \n\nPS. Do not reply to this email.\nThank you.', 'deposits', 'customer', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]], [[refund_details]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(33, 'Deposit Refund by Admin', 'deposit-refund-admin', 'Refunded Deposit #[[order_id]]', 'Hello Admin,', 'The deposit order (#[[order_id]]) refunded successfully. The user account balance adjusted with refund amount of [[order_amount]]. \n\nPS. Do not reply to this email.\nThank you.', 'deposits', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[order_details]], [[payment_method]], [[refund_details]]', 'inactive', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(34, 'Withdraw Request', 'withdraw-request-customer', 'Your Withdraw Request Has Been Received', 'Hello [[user_name]],', 'We received your request to withdraw funds from [[site_name]]. The funds will be deposited in your provided account and should be processed with 24-72 hours. You will be notified by email when we have completed your withdraw.\n\nWithdrawal Details:\n[[withdraw_details]]\n\nNote: If you did not make this withdraw request, please contact us immediately before its authorized by our team.\n\nIf you have any questions, please feel free to contact us.\n', 'withdrawal', 'customer', NULL, '{\"regards\":\"on\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(35, 'Withdraw Request', 'withdraw-request-admin', 'Withdraw Request from [[user_name]]', 'Hello Admin,', 'A user ([[user_name]] - [[user_email]]) requested to withdraw funds. Please review the withdraw request as soon as possible.\n[[withdraw_details]]\n\nPlease login into account and take necessary steps for withdraw.\n\n\nPS. Do not reply to this email.\nThank you.\n', 'withdrawal', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(36, 'Withdraw Cancel Request', 'withdraw-cancel-user-customer', 'Withdraw Has Been Cancelled!', 'Hello [[user_name]],', 'Your recent withdraw request (#[[order_id]]) has been cancelled. The funds returned to your account balance.\n\nIf you want to withdraw funds into your account again, please feel free to login into your account and withdraw once again.\n\nIf you have any questions, please feel free to contact us.\n', 'withdrawal', 'customer', NULL, '{\"regards\":\"on\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(37, 'Withdraw Cancel Request', 'withdraw-cancel-user-admin', 'Cancelled Withdraw Request by [[user_name]]', 'Hello Admin,', 'The recent withdraw request (#[[order_id]]) has been cancelled by user ([[user_name]] - [[user_email]]). \nYou do not need to take any further action.\n\n\nPS. Do not reply to this email.\nThank you.\n', 'withdrawal', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(38, 'Withdraw Order Confirmed', 'withdraw-confirmed-customer', 'Withdrawal Successfully Confirmed!', 'Dear [[user_name]],', 'Your withdraw request of [[order_amount]] has been successfully confirmed. \nThis email confirms that your desired amount will deposited in your account ([[withdraw_to]]) within few hours.\n\nIf you have any question, you can contact us at [[site_email]].', 'withdrawal', 'customer', NULL, '{\"regards\":\"on\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(39, 'Withdraw Order Confirmed', 'withdraw-confirmed-admin', 'Withdraw Request #[[order_id]] Has Been Confirmed', 'Hello Admin,', 'The withdraw request (#[[order_id]]) has been confirmed and notified to user ([[user_name]] - [[user_email]]). Withdraw amount of [[order_amount]] need to be processed for this user.\n\nPS. Do not reply to this email.\nThank you.', 'withdrawal', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]]', 'inactive', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(40, 'Withdraw Processed Request', 'withdraw-success-customer', 'Your Withdraw Request Has Been Completed', 'Hello [[user_name]],', '<strong>Congratulations!</strong>\n\nYour withdraw request (#[[order_id]]) has been successfully processed and a total amount of <strong>[[order_amount]]</strong> has been withdrawn from your account. Your funds transferred into your account as below. \n\nPayment Deposited: \n<strong>[[withdraw_to]]</strong> ([[payment_method]]).\n\nWithdraw Reference: \n[[withdraw_reference]]\n[[withdraw_note]]\n\nIf you have not received funds into your account yet, please feel free to contact us.\n', 'withdrawal', 'customer', NULL, '{\"regards\":\"on\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]], [[withdraw_reference]], [[withdraw_note]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(41, 'Withdraw Processed Request', 'withdraw-success-admin', 'Withdraw Request #[[order_id]] Has Been Processed', 'Hello Admin,', 'The withdraw request (#[[order_id]]) has been processed and notified to user ([[user_name]] - [[user_email]]). \n\nWithdraw Details:\n[[withdraw_details]] \n\nWithdraw Reference:\n[[withdraw_reference]] \n[[withdraw_note]]\n\nPS. Do not reply to this email.\nThank you.', 'withdrawal', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]], [[withdraw_reference]], [[withdraw_note]]', 'inactive', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(42, 'Withdraw Rejected by Admin', 'withdraw-reject-customer', 'Withdraw Request Has Been Rejected', 'Hello [[user_name]],', 'We have received your request (#[[order_id]]) to withdraw funds. We would like to inform you that we have cancelled this request and the funds ([[order_all_amount]]) returned to your account balance.\n\nWithdraw request has been rejected for following reason -\n[[withdraw_note]]\n\nIf you have any questions, please feel free to contact us.', 'withdrawal', 'customer', NULL, '{\"regards\":\"on\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]], [[withdraw_note]]', 'active', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(43, 'Withdraw Rejected by Admin', 'withdraw-reject-admin', 'Withdraw Request Has Been Rejected', 'Hello [[user_name]],', 'The withdraw request (#[[order_id]]) has been rejected. The amount of [[order_all_amount]] has been adjusted to user account balance and notified to user ([[user_name]] - [[user_email]]). \n\nRejection Note: \n[[withdraw_note]]\n\nPS. Do not reply to this email.\nThank you.', 'withdrawal', 'admin', NULL, '{\"regards\":\"off\"}', '[[order_id]], [[order_by]], [[order_amount]], [[order_all_amount]], [[order_time]], [[payment_method]], [[withdraw_to]], [[withdraw_details]], [[withdraw_note]]', 'inactive', '2022-03-29 23:35:29', '2022-03-29 23:35:29');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `iv_actions`
--

CREATE TABLE `iv_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_by` bigint(20) NOT NULL,
  `action_at` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `iv_invests`
--

CREATE TABLE `iv_invests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ivx` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `amount` double NOT NULL,
  `profit` double NOT NULL,
  `total` double NOT NULL,
  `received` double NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `term` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `term_count` int(11) NOT NULL,
  `term_total` int(11) NOT NULL,
  `term_calc` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `term_start` datetime DEFAULT NULL,
  `term_end` datetime DEFAULT NULL,
  `reference` bigint(20) NOT NULL,
  `scheme` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheme_id` bigint(20) UNSIGNED DEFAULT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `iv_ledgers`
--

CREATE TABLE `iv_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ivx` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `calc` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  `fees` double NOT NULL,
  `total` double NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invest_id` bigint(20) NOT NULL,
  `tnx_id` bigint(20) NOT NULL,
  `reference` bigint(20) NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dest` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `iv_profits`
--

CREATE TABLE `iv_profits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `invest_id` bigint(20) NOT NULL,
  `amount` double NOT NULL,
  `capital` double NOT NULL,
  `invested` double NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `term_no` int(11) NOT NULL,
  `payout` bigint(20) DEFAULT NULL,
  `calc_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `iv_schemes`
--

CREATE TABLE `iv_schemes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL,
  `maximum` double DEFAULT NULL,
  `is_fixed` tinyint(1) NOT NULL DEFAULT 0,
  `term` int(11) NOT NULL,
  `term_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double(8,2) NOT NULL,
  `rate_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `calc_period` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `days_only` tinyint(1) NOT NULL,
  `capital` tinyint(1) NOT NULL,
  `payout` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_locked` tinyint(1) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `iv_schemes`
--

INSERT INTO `iv_schemes` (`id`, `name`, `slug`, `short`, `desc`, `amount`, `maximum`, `is_fixed`, `term`, `term_type`, `rate`, `rate_type`, `calc_period`, `days_only`, `capital`, `payout`, `status`, `is_locked`, `featured`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Standard Plan', 'standard-plan', 'ST', 'Entry level of investment & earn money.', 10, 500, 0, 21, 'days', 1.10, 'percent', 'daily', 0, 0, 'term_basis', 'active', NULL, 0, NULL, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(2, 'Premium Plan', 'premium-plan', 'PM', 'Medium level of investment & earn money.', 100, 1500, 0, 1, 'months', 1.50, 'percent', 'daily', 0, 0, 'term_basis', 'active', NULL, 0, NULL, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(3, 'Professional Plan', 'professional-plan', 'PN', 'Exclusive level of investment & earn money.', 500, 2500, 0, 50, 'days', 2.50, 'percent', 'daily', 0, 0, 'term_basis', 'active', NULL, 0, NULL, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(4, 'Mercury', 'mercury', 'MC', 'Investment for long term & earn money.', 100, 0, 1, 7, 'days', 0.25, 'percent', 'hourly', 0, 0, 'term_basis', 'active', NULL, 1, NULL, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(5, 'Venus', 'venus', 'VN', 'Investment for long term & earn money.', 250, 0, 1, 1, 'months', 5.00, 'percent', 'daily', 0, 0, 'term_basis', 'active', NULL, 1, NULL, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(6, 'Jupiter', 'jupiter', 'JP', 'Investment for long term & earn money.', 500, 0, 1, 3, 'months', 20.00, 'percent', 'weekly', 0, 0, 'term_basis', 'active', NULL, 1, NULL, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(7, 'Silver Plan', 'silver-plan', 'SV', 'Investment for long term & earn money.', 100, 0, 1, 7, 'days', 0.25, 'percent', 'hourly', 0, 0, 'term_basis', 'inactive', NULL, 0, NULL, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(8, 'Dimond Plan', 'dimond-plan', 'DM', 'Investment for long term & earn money.', 250, 0, 1, 1, 'months', 5.00, 'percent', 'daily', 0, 0, 'term_basis', 'inactive', NULL, 0, NULL, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(9, 'Platinum Plan', 'platinum-plan', 'JP', 'Investment for long term & earn money.', 500, 0, 1, 3, 'months', 20.00, 'percent', 'weekly', 0, 0, 'term_basis', 'inactive', NULL, 0, NULL, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(10, 'Investment Plan X1', 'investment-plan-x1', 'X1', 'Invest your money and & earn.', 10, 500, 0, 7, 'days', 1.10, 'percent', 'daily', 0, 0, 'term_basis', 'inactive', NULL, 0, NULL, '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(11, 'Investment Plan X2', 'investment-plan-x2', 'X2', 'Invest your money and & earn.', 10, 500, 0, 7, 'days', 1.10, 'percent', 'daily', 0, 0, 'term_basis', 'inactive', NULL, 0, NULL, '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(12, 'Investment Plan X3', 'investment-plan-x3', 'X3', 'Invest your money and & earn.', 10, 500, 0, 7, 'days', 1.10, 'percent', 'daily', 0, 0, 'term_basis', 'inactive', NULL, 0, NULL, '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(13, 'Investment Plan X4', 'investment-plan-x4', 'X4', 'Invest your money and & earn.', 10, 500, 0, 7, 'days', 1.10, 'percent', 'daily', 0, 0, 'term_basis', 'inactive', NULL, 0, NULL, '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(14, 'Investment Plan X5', 'investment-plan-x5', 'X5', 'Invest your money and & earn.', 10, 500, 0, 7, 'days', 1.10, 'percent', 'daily', 0, 0, 'term_basis', 'inactive', NULL, 0, NULL, '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(15, 'Investment Plan X6', 'investment-plan-x6', 'X6', 'Invest your money and & earn.', 10, 500, 0, 7, 'days', 1.10, 'percent', 'daily', 0, 0, 'term_basis', 'inactive', NULL, 0, NULL, '2022-03-29 23:35:29', '2022-03-29 23:35:29');

-- --------------------------------------------------------

--
-- Table structure for table `iv_scheme_metas`
--

CREATE TABLE `iv_scheme_metas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scheme_id` bigint(20) NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `translations` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rtl` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `label`, `short`, `translations`, `rtl`, `status`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 'English', 'ENG', NULL, '', '1', '2022-03-29 23:35:25', '2022-03-29 23:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `ledgers`
--

CREATE TABLE `ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `debit` double DEFAULT NULL,
  `credit` double DEFAULT NULL,
  `balance` double NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2021_01_04_000000_create_users_table', 1),
(2, '2021_02_17_000000_create_failed_jobs_table', 1),
(3, '2021_04_12_101006_create_verify_tokens_table', 1),
(4, '2021_04_12_101023_create_user_metas_table', 1),
(5, '2021_04_12_101046_create_user_accounts_table', 1),
(6, '2021_04_12_101502_create_user_activities_table', 1),
(7, '2021_04_12_102056_create_referrals_table', 1),
(8, '2021_04_12_102084_create_referral_codes_table', 1),
(9, '2021_04_15_120264_create_settings_table', 1),
(10, '2021_04_15_120392_create_pages_table', 1),
(11, '2021_04_15_120416_create_email_templates_table', 1),
(12, '2021_04_15_120574_create_payment_methods_table', 1),
(13, '2021_04_15_120683_create_withdraw_methods_table', 1),
(14, '2021_04_15_132940_create_accounts_table', 1),
(15, '2021_04_19_132965_create_transactions_table', 1),
(16, '2021_04_19_132996_create_ledgers_table', 1),
(17, '2021_04_19_135150_add_iv_schemes_table', 1),
(18, '2021_04_19_135158_add_iv_scheme_metas_table', 1),
(19, '2021_04_19_135235_add_iv_invests_table', 1),
(20, '2021_04_19_135364_add_iv_ledgers_table', 1),
(21, '2021_04_19_135389_add_iv_profits_table', 1),
(22, '2021_04_19_135458_add_iv_actions_table', 1),
(23, '2021_06_04_162627_create_languages_table', 1),
(24, '2021_08_07_074811_add_scheme_column_in_iv_invest', 1),
(25, '2021_08_07_144326_add_is_locked_column_in_iv_schemes', 1),
(26, '2021_11_19_154153_add_max_column_in_methods', 1),
(27, '2021_11_19_154464_add_pid_column_in_pages', 1),
(28, '2022_03_04_060117_add_deleted_at_column_in_iv_schemes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_link` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pid` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `public` tinyint(1) NOT NULL DEFAULT 1,
  `params` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trash` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `slug`, `menu_name`, `menu_link`, `title`, `subtitle`, `seo`, `content`, `lang`, `status`, `pid`, `public`, `params`, `trash`, `created_at`, `updated_at`) VALUES
(1, 'About Us', 'about-us', 'About', NULL, 'About the platform', NULL, NULL, '<h4>About the Company</h4>\n<p><strong>[[site_name]]</strong> work in the field of financing promising developments on cryptocurrency market and with blockchain technology. According to experts, blockchain technologies currently have great opportunity. Lots of business ideas related to blockchain technologies become more successful and every day by day it bring high profits to their creators.</p>\n<p>We track and analyze most business ideas. It allows us to get high profits. For our investor do not need to research independently in which project it is more profitable. So our investor can invest their capital and then receive an interest on the profit.</p>\n<h4>Investment</h4>\n<p>We invest in projects at an early stage, in particular, it can be business ideas, investing in startups at various stages of their development, ICO (Initial Coin Offering), IEO (Initial Exchange Offering).</p>', 'en', 'active', 0, 1, NULL, 0, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(2, 'Our Fees', 'fees', 'Fees', NULL, 'Our Fees', '', '{\"title\":null,\"description\":null,\"keyword\":null}', 'Test Page !', 'en', 'active', 0, 1, '{\"is_html\":\"off\"}', 0, '2022-03-29 23:35:28', '2022-05-19 23:20:29'),
(3, 'Referral', 'referral', 'Referral', NULL, 'My Referral', '', '{\"title\":null,\"description\":null,\"keyword\":null}', 'Referral Page!', 'en', 'active', 0, 1, '{\"is_html\":\"off\"}', 0, '2022-03-29 23:35:28', '2022-05-19 23:21:10'),
(4, 'Frequently Asked Questions', 'faqs', 'FAQs', NULL, 'Frequently Asked Questions', NULL, NULL, '<h4>How can we help you?</h4>\n<p>Do You have any questions? We strongly recommend that you start searching for the necessary information in the FAQ section.</p>\n<h5>What is [[site_name]] company?</h5>\n<p>[[site_name]] platform is an international investment company. The activity of our company is aimed at the cryptocurrency trading, forex, stocks and providing investment services worldwide.</p>\n<h5>How to create an account?</h5>\n<p>The registration process on the website is quite simple. You need to fill out the fields of the registration form, which include full name, email address and password.</p>\n<h5>Which payment methods do you accept?</h5>\n<p>At the moment we work with PayPal, Wire Transfer, Bitcoin, Ethereum, Litecoin, Binance Coin.</p>\n<h5>I want to reinvest the funds received, is it possible?</h5>\n<p>Of course. You have the right to reinvesting your profits again and again.</p>', 'en', 'active', 0, 1, NULL, 0, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(5, 'Contact Us', 'contact-us', 'Contact', NULL, 'Contact Us', NULL, NULL, '<h4>Get In Touch</h4>\n<p>If you need advice or have any question in mind or technical assistance, do not hesitate to contact our specialists.</p>\n<p><strong>Email Address:</strong> [[site_email]]</p>', 'en', 'active', 0, 1, NULL, 0, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(6, 'Terms and Condition', 'terms-and-condition', 'Terms and Condition', NULL, 'Terms and Condition', NULL, NULL, '<h4>Terms and condition</h4>\n<p>Welcome to [[site_name]]!</p>\n<p>These terms and conditions outline the rules and regulations for the use of [[site_name]]\'s Website.</p>\n<p>By accessing this website we assume you accept these terms and conditions. Do not continue to use [[site_name]] if you do not agree to take all of the terms and conditions stated on this page.</p>\n<p>If you have additional questions or require more information, do not hesitate to contact us through email at [[site_email]].</p>', 'en', 'active', 0, 1, NULL, 0, '2022-03-29 23:35:28', '2022-03-29 23:35:28'),
(7, 'Privacy Policy', 'privacy-policy', 'Privacy Policy', NULL, 'Privacy Policy', NULL, NULL, '<h4>Privacy Policy for [[site_name]].</h4>\n<p>At <strong>[[site_name]]</strong>, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by <strong>[[site_name]]</strong> and how we use it.</p>\n<p>If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us through email at [[site_email]].</p>', 'en', 'active', 0, 1, NULL, 0, '2022-03-29 23:35:28', '2022-03-29 23:35:28');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_amount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_amount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fees` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currencies` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `countries` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `slug`, `name`, `desc`, `min_amount`, `max_amount`, `config`, `fees`, `currencies`, `countries`, `status`, `created_at`, `updated_at`) VALUES
(1, 'paypal', 'Paypal', 'Pay securely with your PayPal account.', '5', NULL, '[]', '{\"flat\":0,\"percent\":0}', '[\"USD\"]', '[]', 'inactive', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(2, 'bank-transfer', 'Bank Transfer', 'Make payment directly into our bank account.', '100', NULL, '[]', '{\"flat\":0,\"percent\":0}', '[\"USD\"]', '[]', 'inactive', '2022-03-29 23:35:29', '2022-03-29 23:35:29'),
(3, 'crypto-wallet', 'Crypto Wallet', 'Send your payment direct to our wallet.', '1', '5000000', '{\"meta\":{\"title\":null,\"fiat\":\"USD\",\"timeout\":\"0\",\"qr\":\"show\"},\"wallet\":{\"BTC\":{\"address\":null,\"wnote\":null,\"network\":\"default\",\"ref\":\"no\",\"min\":\"0\",\"max\":\"0\"},\"ETH\":{\"address\":\"0x177A2010BB23d434E6464D020be60F3454C56731\",\"wnote\":null,\"network\":\"bep20\",\"ref\":\"yes\",\"min\":\"0.0001\",\"max\":\"1000\",\"limit\":null,\"price\":null},\"LTC\":{\"address\":null,\"wnote\":null,\"network\":\"default\",\"ref\":\"no\",\"min\":\"0\",\"max\":\"0\"},\"BCH\":{\"address\":null,\"wnote\":null,\"network\":\"default\",\"ref\":\"no\",\"min\":\"0\",\"max\":\"0\"},\"BNB\":{\"address\":null,\"wnote\":null,\"network\":\"default\",\"ref\":\"no\",\"min\":\"0\",\"max\":\"0\"},\"ADA\":{\"address\":null,\"wnote\":null,\"ref\":\"no\",\"min\":\"0\",\"max\":\"0\"},\"XRP\":{\"address\":null,\"wnote\":null,\"ref\":\"no\",\"min\":\"0\",\"max\":\"0\"},\"USDC\":{\"address\":null,\"wnote\":null,\"network\":\"default\",\"ref\":\"no\",\"min\":\"0\",\"max\":\"0\"},\"USDT\":{\"address\":null,\"wnote\":null,\"network\":\"default\",\"ref\":\"no\",\"min\":\"0\",\"max\":\"0\"},\"TRX\":{\"address\":null,\"wnote\":null,\"network\":\"default\",\"ref\":\"no\",\"min\":\"0\",\"max\":\"0\"}}}', '{\"flat\":0,\"percent\":0}', '[\"ETH\"]', '[]', 'active', '2022-03-29 23:35:29', '2022-05-19 22:03:09');

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `refer_by` bigint(20) NOT NULL,
  `join_at` datetime NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_codes`
--

CREATE TABLE `referral_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `referral_codes`
--

INSERT INTO `referral_codes` (`id`, `user_id`, `code`, `type`, `label`, `desc`, `created_at`, `updated_at`) VALUES
(1, 3, '5e6c8eb5', '0', NULL, NULL, '2022-03-30 06:16:54', '2022-03-30 06:16:54'),
(2, 1, 'c13f5459', '0', NULL, NULL, '2022-05-16 23:52:28', '2022-05-16 23:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES
(1, 'site_name', 'InvestPlan', '2022-03-30 17:40:42'),
(2, 'site_email', 'admin@gmail.com', '2022-03-30 05:39:14'),
(3, 'site_copyright', ':Sitename &copy; :year. All Rights Reserved.', '2022-03-29 23:35:26'),
(4, 'site_disclaimer', '', '2022-03-29 23:35:26'),
(5, 'site_merchandise', '{\"purchase_code\":\"1a2s1d4f5c4z2a1s3d4t5r8q7e6r7y9t4h2j5\",\"name\":\"Admin\",\"email\":\"asadcse100@gmail.com\"}', '2022-03-30 05:37:45'),
(6, 'main_website', '', '2022-03-29 23:35:26'),
(7, 'front_page_enable', 'yes', '2022-03-29 23:35:26'),
(8, 'front_page_extra', 'on', '2022-03-29 23:35:26'),
(9, 'front_page_title', 'Welcome', '2022-03-29 23:35:27'),
(10, 'invest_page_enable', 'yes', '2022-03-29 23:35:27'),
(11, 'time_zone', 'Asia/Dhaka', '2022-03-29 23:35:27'),
(12, 'date_format', 'd M, Y', '2022-03-29 23:35:27'),
(13, 'time_format', 'h:i A', '2022-03-29 23:35:27'),
(14, 'decimal_fiat_display', '2', '2022-03-29 23:35:27'),
(15, 'decimal_crypto_display', '4', '2022-03-29 23:35:27'),
(16, 'decimal_fiat_calc', '2', '2022-03-29 23:35:27'),
(17, 'decimal_crypto_calc', '6', '2022-03-29 23:35:27'),
(18, 'signup_allow', 'enable', '2022-04-07 18:10:52'),
(19, 'email_verification', 'on', '2022-04-07 18:10:52'),
(20, 'batch_update', '120', '2022-03-29 23:35:27'),
(21, 'maintenance_mode', 'off', '2022-03-29 23:35:27'),
(22, 'maintenance_notice', 'We are upgrading our system. Please check after 30 minutes.', '2022-03-29 23:35:27'),
(23, 'mail_from_name', 'Investplan', '2022-03-30 18:39:55'),
(24, 'mail_from_email', 'asadcse100@gmail.com', '2022-03-30 10:44:28'),
(25, 'mail_global_footer', 'Best Regard, \r\nTeam of [[site_name]]', '2022-03-30 10:44:28'),
(26, 'mail_driver', 'smtp', '2022-03-30 10:44:28'),
(27, 'mail_smtp_host', 'smtp.gmail.com', '2022-03-30 10:44:28'),
(28, 'mail_smtp_port', '587', '2022-03-29 23:35:27'),
(29, 'mail_smtp_secure', 'tls', '2022-03-29 23:35:27'),
(30, 'mail_smtp_user', 'janifer100100@gmail.com', '2022-03-30 10:44:28'),
(31, 'mail_smtp_password', 'rony880n', '2022-03-30 10:44:28'),
(32, 'mail_recipient', 'asadcse100@gmail.com', '2022-03-30 10:44:28'),
(33, 'mail_recipient_alter', '', '2022-03-29 23:35:27'),
(34, 'youtube_link', '', '2022-03-29 23:35:27'),
(35, 'linkedin_link', '', '2022-03-29 23:35:27'),
(36, 'twitter_link', '', '2022-03-29 23:35:27'),
(37, 'facebook_link', '', '2022-03-29 23:35:27'),
(38, 'medium_link', '', '2022-03-29 23:35:27'),
(39, 'telegram_link', '', '2022-03-29 23:35:27'),
(40, 'github_link', '', '2022-03-29 23:35:27'),
(41, 'pinterest_link', '', '2022-03-29 23:35:27'),
(42, 'app_acquire', '{\"app\":\"Invest\",\"secret\":\"a12245678999ca31eeb35046d4d13a11\",\"cipher\":\"d4d13a11\",\"key\":\"245678\",\"update\":\"2537354402\"}', '2022-03-30 06:48:18'),
(43, 'exratesapi_access_key', '', '2022-03-29 23:35:27'),
(44, 'recaptcha_site_key', '', '2022-03-29 23:35:27'),
(45, 'recaptcha_secret_key', '', '2022-03-29 23:35:27'),
(46, 'custom_stylesheet', 'off', '2022-03-29 23:35:27'),
(47, 'header_code', '', '2022-03-29 23:35:27'),
(48, 'footer_code', '', '2022-03-29 23:35:27'),
(49, 'main_nav', '[1,4,5]', '2022-03-30 05:35:28'),
(50, 'main_menu', '[]', '2022-03-29 23:35:27'),
(51, 'footer_menu', '[4,6,7]', '2022-03-30 05:35:28'),
(52, 'page_terms', '6', '2022-03-30 05:35:28'),
(53, 'page_privacy', '7', '2022-03-30 05:35:28'),
(54, 'page_fee_deposit', '', '2022-03-29 23:35:27'),
(55, 'page_fee_withdraw', '', '2022-03-29 23:35:27'),
(56, 'page_contact', '5', '2022-03-30 05:35:28'),
(57, 'page_contact_form', 'on', '2022-03-29 23:35:27'),
(58, 'ui_page_skin', 'dark', '2022-03-29 23:35:27'),
(59, 'ui_auth_skin', 'dark', '2022-03-29 23:35:27'),
(60, 'ui_auth_layout', 'default', '2022-03-29 23:35:27'),
(61, 'ui_theme_mode', 'light', '2022-03-29 23:35:27'),
(62, 'ui_theme_skin', 'default', '2022-03-29 23:35:27'),
(63, 'ui_sidebar_user', 'white', '2022-03-29 23:35:27'),
(64, 'ui_sidebar_admin', 'darker', '2022-03-29 23:35:27'),
(65, 'ui_theme_mode_admin', 'light', '2022-03-29 23:35:27'),
(66, 'ui_theme_skin_admin', 'default', '2022-03-29 23:35:27'),
(67, 'payout_batch', 'a12245678999ca31eeb35046d4d13a11', '2022-03-30 05:38:43'),
(68, 'signup_bonus_allow', 'no', '2022-03-30 12:23:29'),
(69, 'signup_bonus_amount', '100', '2022-03-30 11:54:38'),
(70, 'deposit_bonus_allow', 'yes', '2022-03-30 11:54:38'),
(71, 'deposit_bonus_amount', '20', '2022-03-30 11:54:38'),
(72, 'deposit_bonus_type', 'percent', '2022-03-30 11:54:38'),
(73, 'referral_system', 'yes', '2022-03-30 11:46:25'),
(74, 'referral_invite_title', 'Refer Us & Earn', '2022-03-29 23:35:27'),
(75, 'referral_invite_text', 'Use the below link to invite your friends.', '2022-03-29 23:35:27'),
(76, 'referral_signup_user', 'no', '2022-03-30 12:22:25'),
(77, 'referral_signup_user_bonus', '0', '2022-03-29 23:35:27'),
(78, 'referral_signup_user_reward', 'no', '2022-03-29 23:35:27'),
(79, 'referral_deposit_user', 'yes', '2022-03-30 11:55:05'),
(80, 'referral_deposit_user_bonus', '10', '2022-03-30 12:21:57'),
(81, 'referral_deposit_user_type', 'percent', '2022-03-29 23:35:27'),
(82, 'referral_signup_referer', 'no', '2022-03-30 12:20:39'),
(83, 'referral_signup_referer_bonus', '0', '2022-03-29 23:35:27'),
(84, 'referral_deposit_referer', 'yes', '2022-03-30 11:55:05'),
(85, 'referral_deposit_referer_bonus', '10', '2022-03-30 12:22:41'),
(86, 'referral_deposit_referer_type', 'percent', '2022-03-29 23:35:27'),
(87, 'alert_wd_account', 'on', '2022-03-29 23:35:27'),
(88, 'alert_profile_basic', 'on', '2022-03-29 23:35:27'),
(89, 'header_notice_show', 'no', '2022-03-29 23:35:27'),
(90, 'header_notice_title', '', '2022-03-29 23:35:27'),
(91, 'header_notice_text', '', '2022-03-29 23:35:27'),
(92, 'header_notice_link', '', '2022-03-29 23:35:27'),
(93, 'system_service', 'OOO55FNKK32', '2022-04-03 14:28:08'),
(94, 'api_service', 'no', '2022-03-30 06:48:21'),
(95, 'deposit_service', 'a90c6258', '2022-03-30 05:37:47'),
(96, 'deposit_limit_request', '0', '2022-03-30 10:52:36'),
(97, 'deposit_cancel_timeout', '0', '2022-03-30 06:11:08'),
(98, 'deposit_fiat_minimum', '100', '2022-03-30 06:11:08'),
(99, 'deposit_crypto_minimum', '500', '2022-03-30 06:11:08'),
(100, 'deposit_fiat_maximum', '0', '2022-03-29 23:35:27'),
(101, 'deposit_crypto_maximum', '0', '2022-03-29 23:35:27'),
(102, 'deposit_disable_request', 'no', '2022-03-30 06:53:48'),
(103, 'deposit_disable_title', 'Temporarily unavailable!', '2022-03-29 23:35:27'),
(104, 'deposit_disable_notice', 'Sorry, we are upgrading our deposit system. Please check after sometimes. We apologize for any inconvenience.', '2022-03-29 23:35:27'),
(105, 'payout_check', '1655554941', '2022-06-18 11:52:21'),
(106, 'withdraw_service', 'a90c6258', '2022-03-30 05:37:47'),
(107, 'withdraw_limit_request', '3', '2022-03-30 06:11:00'),
(108, 'withdraw_cancel_timeout', 'yes', '2022-03-29 23:35:27'),
(109, 'withdraw_fiat_minimum', '1', '2022-03-30 10:59:03'),
(110, 'withdraw_fiat_maximum', '100', '2022-03-30 18:34:16'),
(111, 'withdraw_crypto_minimum', '500', '2022-03-30 06:11:00'),
(112, 'withdraw_crypto_maximum', '600', '2022-03-30 18:34:16'),
(113, 'withdraw_disable_request', 'no', '2022-03-30 10:59:03'),
(114, 'withdraw_disable_title', 'Temporarily unavailable!', '2022-03-29 23:35:27'),
(115, 'withdraw_disable_notice', 'Sorry, we are upgrading our withdrawal system. Please check after sometimes. We apologize for any inconvenience.', '2022-03-29 23:35:27'),
(116, 'app_queue', '0', '2022-03-29 23:35:27'),
(117, 'base_currency', 'USD', '2022-03-29 23:35:27'),
(118, 'alter_currency', 'BTC', '2022-03-30 12:15:29'),
(119, 'supported_currency', '{\"USD\":\"on\",\"EUR\":\"on\",\"GBP\":\"on\",\"CAD\":\"on\",\"AUD\":\"on\",\"TRY\":\"on\",\"RUB\":\"on\",\"INR\":\"on\",\"BRL\":\"on\",\"NGN\":\"on\",\"PKR\":\"on\",\"VND\":\"on\",\"TZS\":\"on\",\"SAR\":\"on\",\"MXN\":\"on\",\"GHS\":\"on\",\"KES\":\"on\",\"BTC\":\"on\",\"ETH\":\"on\",\"LTC\":\"on\",\"BCH\":\"on\",\"BNB\":\"on\",\"ADA\":\"on\",\"XRP\":\"on\",\"USDC\":\"on\",\"USDT\":\"on\",\"TRX\":\"on\"}', '2022-05-17 06:19:00'),
(120, 'fiat_rounded', 'up', '2022-03-29 23:35:27'),
(121, 'crypto_rounded', 'up', '2022-03-30 18:26:09'),
(122, 'exchange_method', 'manual', '2022-03-30 12:13:31'),
(123, 'exchange_auto_update', '30', '2022-03-29 23:35:27'),
(124, 'exchange_last_update', '1648618525', '2022-03-29 23:35:27'),
(125, 'manual_exchange_rate', '{\"USD\":\"1\",\"EUR\":\"0.89830\",\"GBP\":\"0\",\"CAD\":\"0\",\"BTC\":\"0.0000211476\",\"ETH\":\"0.0002944886\",\"LTC\":\"0\",\"BNB\":\"0\"}', '2022-03-30 12:13:31'),
(126, 'health_checker', '1', '2022-05-06 18:38:03'),
(127, 'top_iv_plan_x0', '3', '2022-03-30 05:35:28'),
(128, 'top_iv_plan_x1', '1', '2022-03-30 05:35:28'),
(129, 'top_iv_plan_x2', '2', '2022-03-30 05:35:28'),
(130, 'iv_plan_order', 'featured', '2022-03-29 23:35:27'),
(131, 'iv_show_plans', 'default', '2022-03-29 23:35:27'),
(132, 'iv_plan_desc_show', 'no', '2022-03-29 23:35:27'),
(133, 'iv_plan_total_percent', 'yes', '2022-03-29 23:35:27'),
(134, 'iv_plan_pg_heading', 'Investment Plans', '2022-03-29 23:35:27'),
(135, 'iv_plan_pg_title', 'Choose your favourite plan and start earning now.', '2022-03-29 23:35:27'),
(136, 'iv_plan_pg_text', 'Here is our several investment plans. You can invest daily, weekly or monthly and get higher returns in your investment.', '2022-03-29 23:35:27'),
(137, 'iv_launched_date', '03/30/2022', '2022-03-29 23:35:27'),
(138, 'iv_cancel_timeout', '15', '2022-03-29 23:35:27'),
(139, 'iv_admin_confirmtion', 'yes', '2022-03-29 23:35:27'),
(140, 'iv_disable_purchase', 'no', '2022-03-29 23:35:27'),
(141, 'iv_disable_title', 'Temporarily unavailable!', '2022-03-29 23:35:27'),
(142, 'iv_disable_notice', '', '2022-03-29 23:35:27'),
(143, 'iv_profit_payout', 'everytime', '2022-03-29 23:35:27'),
(144, 'iv_profit_payout_amount', '100', '2022-03-29 23:35:27'),
(145, 'iv_plan_fx_currencies', '[]', '2022-03-29 23:35:27'),
(146, 'iv_weekend_days', '[]', '2022-03-29 23:35:27'),
(147, 'language_default_public', 'en', '2022-03-29 23:35:29'),
(148, 'language_default_system', 'en', '2022-03-29 23:35:29'),
(149, 'language_show_as', 'default', '2022-03-29 23:35:29'),
(150, 'language_switcher', 'off', '2022-03-29 23:35:29'),
(151, 'social_auth', 'off', '2022-03-29 23:35:30'),
(152, 'gdpr_enable', 'yes', '2022-03-30 12:25:19'),
(153, 'cookie_consent_text', 'This website uses cookies. By continuing to use this website, you agree to their use. For details, please check our [[privacy]].', '2022-03-29 23:35:30'),
(154, 'referral_show_referred_users', 'yes', '2022-03-30 11:55:05'),
(155, 'referral_user_table_opts', '[\"earning\",\"compact\",null]', '2022-03-30 11:55:05'),
(156, 'referral_invite_redirect', 'register', '2022-03-29 23:35:30'),
(157, 'cookie_banner_position', 'bbox-left', '2022-03-29 23:35:30'),
(158, 'cookie_banner_background', 'light', '2022-03-29 23:35:30'),
(159, 'seo_description', '', '2022-03-29 23:35:30'),
(160, 'login_seo_title', '', '2022-03-29 23:35:30'),
(161, 'registration_seo_title', '', '2022-03-29 23:35:30'),
(162, 'og_title', '', '2022-03-29 23:35:30'),
(163, 'og_description', '', '2022-03-29 23:35:30'),
(164, 'header_notice_date', '', '2022-03-29 23:35:30'),
(165, 'deposit_amount_base', 'yes', '2022-05-17 06:12:40'),
(166, 'rates_ticker_display', 'no', '2022-03-29 23:35:30'),
(167, 'rates_ticker_from', 'base', '2022-03-29 23:35:30'),
(168, 'rates_ticker_fx', 'only', '2022-03-29 23:35:30'),
(169, 'iv_plan_capital_show', 'yes', '2022-03-29 23:35:30'),
(170, 'iv_plan_payout_show', 'no', '2022-03-29 23:35:30'),
(171, 'iv_plan_terms_show', 'no', '2022-03-29 23:35:30'),
(172, 'application_rcv', '2120115', '2022-03-29 23:35:31'),
(173, 'update_installed', '1648618531', '2022-03-29 23:35:31'),
(174, 'installed_apps', '1648618548', '2022-03-29 23:35:48'),
(175, 'baseurl_apps', 'localhost/invest', '2022-04-03 14:22:09'),
(176, 'system_super_admin', '1', '2022-03-29 23:38:36'),
(177, 'quick_setup_done', '1648618831', '2022-03-29 23:40:31'),
(178, 'exratesapi_error_msg', 'Access key was not sepecified in application.', '2022-06-13 01:56:48'),
(179, 'cache_version', '1648640701', '2022-03-30 05:45:01'),
(180, 'payout_locked_profit', NULL, '2022-03-30 05:45:01'),
(181, 'payout_locked_plan', NULL, '2022-03-30 05:45:01'),
(182, 'signup_form_fields', '{\"profile_phone\":{\"show\":\"no\",\"req\":\"no\"},\"profile_dob\":{\"show\":\"no\",\"req\":\"no\"},\"profile_country\":{\"show\":\"yes\",\"req\":\"no\"}}', '2022-04-07 18:10:08'),
(183, 'referral_deposit_user_allow', 'all', '2022-03-30 12:21:49'),
(184, 'referral_deposit_user_max', '', '2022-03-30 05:55:05'),
(185, 'referral_deposit_referer_allow', 'all', '2022-03-30 12:22:41'),
(186, 'referral_deposit_referer_max', '', '2022-03-30 12:22:41'),
(187, 'cookie_deny_btn', 'yes', '2022-03-30 06:25:19'),
(188, 'cookie_accept_btn_txt', 'I Agree', '2022-03-30 06:25:19'),
(189, 'cookie_deny_btn_txt', 'Deny', '2022-03-30 06:25:19'),
(190, 'instagram_link', '', '2022-03-30 11:40:42'),
(191, 'whatsapp_link', '', '2022-03-30 11:40:42'),
(192, 'reddit_link', '', '2022-03-30 11:40:42'),
(193, 'website_logo_dark', 'brand/6cFlEG0lzL85l4bn5LXHxabWfuYKetYuMSTjmu8S.png', '2022-03-30 11:48:52'),
(194, 'website_logo_light', 'brand/Z5ARQLaC7CxBuPg0TsDop9cEnhNvSlAG8XuoD4nk.png', '2022-03-30 11:49:00'),
(195, 'website_logo_mail', 'brand/x66qD8bnqTW31BlfMGsf8LW1CQqhXVln1GeR71Bn.png', '2022-03-30 11:49:11'),
(196, 'website_logo_dark2x', 'brand/f3tkrF271nTWLPQabKlMzPoWX87oLXrr1YODVrBC.png', '2022-03-30 11:49:18'),
(197, 'website_logo_light2x', 'brand/0lAevD8zT6spbFDkseq0BnBRkEigNCtavk5qLo5q.png', '2022-03-30 11:49:25');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tnx` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `account_to` bigint(20) DEFAULT NULL,
  `account_from` bigint(20) DEFAULT NULL,
  `calc` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `fees` double NOT NULL DEFAULT 0,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` double NOT NULL DEFAULT 0,
  `tnx_amount` double NOT NULL DEFAULT 0,
  `tnx_fees` double NOT NULL DEFAULT 0,
  `tnx_total` double NOT NULL DEFAULT 0,
  `tnx_currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tnx_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exchange` double NOT NULL DEFAULT 0,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refund` bigint(20) DEFAULT 0,
  `pay_from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay_to` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `confirmed_by` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `completed_by` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `refer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `2fa` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `role`, `status`, `last_login`, `refer`, `2fa`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'superadmin', 'admin', 'admin@gmail.com', '$2y$10$r0KHDJBiJv6zYl8DhF5MM.w7Qmuptqdwo9drZq4lLwSw.yYzcJlsK', 'super-admin', 'active', '2022-06-18 11:52:21', NULL, '0', NULL, '2022-03-29 23:38:36', '2022-06-18 05:52:21', NULL),
(2, 'md424', 'Md Asad', 'ronydiu1@gmail.com', '$2y$10$9jDTAl9R1z471Jw7qIW7NOy/9OHpSBfK6UWtSg2o2dBtrbIoc9BZa', 'user', 'active', '2022-05-17 04:53:41', NULL, '0', NULL, '2022-03-30 00:18:19', '2022-05-16 22:53:41', NULL),
(3, 'md760', 'Md Asaduzzaman', 'asadcse100@gmail.com', '$2y$10$VhZvJNBCo29Fm.cvZJrdmuMQGdzX2aFTPVw5sItT8FQPuDioUEhRm', 'user', 'active', '2022-05-17 06:03:43', NULL, '0', 'MhLqSJJzM3CPwijivVkB20JlaWZCawubmaXGstsPLaiNGbcABhM5oFCt06lf', '2022-04-07 11:12:21', '2022-05-17 00:03:43', NULL),
(5, 'eseitsoft912', 'eseitsoft', 'eseitsoft@gmail.com', '$2y$10$rPYAdz1yL.cn1.SDeqpCaO3OpyZeik2WQ7K1tZQeaOtekgLYbXjq2', 'user', 'inactive', NULL, NULL, '0', NULL, '2022-04-07 11:32:41', '2022-04-07 11:32:41', NULL),
(6, 'nerob746', 'Nerob', 'ronydiu2@gmail.com', '$2y$10$kjNOTeGlC7.wW6hKTmsDdeg08bscQDkgMvu8ZSVch0Rl38mYbHQPe', 'user', 'active', '2022-05-17 04:58:52', NULL, '0', 'KZv8vxSzqO2UwNUFj5SkhK3QHdWPT7oWGcPhrTYXJiktysyVVRD1DJ6M0vco', '2022-04-07 12:12:02', '2022-05-16 22:58:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `config` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_used` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`id`, `user_id`, `slug`, `name`, `config`, `last_used`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 3, 'user2', 'Asad', 'ok', '2022-03-30 08:23:17', NULL, '2022-03-30 06:25:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

CREATE TABLE `user_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `session` datetime NOT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `browser` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_activities`
--

INSERT INTO `user_activities` (`id`, `user_id`, `session`, `ip`, `meta`, `browser`, `device`, `platform`, `version`) VALUES
(1, 1, '2022-03-30 05:38:43', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.82\",\"platform\":\"10.0\"}'),
(2, 1, '2022-03-30 05:58:19', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.82\",\"platform\":\"10.0\"}'),
(3, 2, '2022-03-30 06:48:18', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.82\",\"platform\":\"10.0\"}'),
(4, 1, '2022-03-30 10:31:50', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.82\",\"platform\":\"10.0\"}'),
(5, 2, '2022-03-30 10:33:45', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.82\",\"platform\":\"10.0\"}'),
(6, 2, '2022-03-30 10:48:59', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(7, 1, '2022-03-30 17:36:32', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(8, 2, '2022-03-30 18:10:11', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(9, 1, '2022-04-03 14:24:57', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(10, 1, '2022-04-03 14:38:25', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(11, 3, '2022-04-07 17:13:00', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(12, 3, '2022-04-07 17:13:41', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(13, 2, '2022-04-07 17:15:14', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(14, 1, '2022-04-07 17:15:17', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(15, 3, '2022-04-07 17:20:14', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(16, 3, '2022-04-07 17:20:52', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(17, 3, '2022-04-07 17:21:00', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(18, 3, '2022-04-07 17:33:32', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(19, 3, '2022-04-07 17:34:57', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(20, 3, '2022-04-07 17:35:38', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(21, 3, '2022-04-07 17:36:22', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(22, 3, '2022-04-07 17:39:10', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(23, 3, '2022-04-07 17:51:35', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(24, 3, '2022-04-07 17:52:03', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(25, 3, '2022-04-07 17:52:56', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(26, 3, '2022-04-07 17:56:38', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(27, 3, '2022-04-07 17:59:50', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(28, 3, '2022-04-07 17:59:59', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(29, 3, '2022-04-07 18:01:36', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(30, 1, '2022-04-07 18:06:42', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(31, 6, '2022-04-07 18:12:44', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(32, 6, '2022-04-07 18:13:10', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(33, 6, '2022-04-07 18:14:09', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(34, 6, '2022-04-07 18:15:48', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(35, 6, '2022-04-07 18:16:10', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(36, 6, '2022-04-07 18:18:47', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(37, 6, '2022-04-07 18:19:28', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(38, 6, '2022-04-07 18:20:55', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(39, 6, '2022-04-07 18:22:56', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(40, 6, '2022-04-07 18:23:19', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(41, 6, '2022-04-07 18:23:44', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(42, 6, '2022-04-07 18:24:50', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(43, 6, '2022-04-07 18:30:27', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"99.0.4844.84\",\"platform\":\"10.0\"}'),
(44, 6, '2022-05-06 18:44:10', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"100.0.4896.127\",\"platform\":\"10.0\"}'),
(45, 2, '2022-05-06 18:44:55', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"100.0.4896.127\",\"platform\":\"10.0\"}'),
(46, 1, '2022-05-06 18:45:00', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"100.0.4896.127\",\"platform\":\"10.0\"}'),
(47, 2, '2022-05-17 04:53:41', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(48, 6, '2022-05-17 04:54:03', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(49, 6, '2022-05-17 04:58:43', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(50, 6, '2022-05-17 04:58:52', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(51, 3, '2022-05-17 05:22:41', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(52, 3, '2022-05-17 05:22:48', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(53, 3, '2022-05-17 05:23:53', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(54, 3, '2022-05-17 05:30:30', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(55, 3, '2022-05-17 05:33:45', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(56, 3, '2022-05-17 05:36:23', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(57, 3, '2022-05-17 05:36:44', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(58, 3, '2022-05-17 05:37:10', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(59, 3, '2022-05-17 05:37:27', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(60, 3, '2022-05-17 05:38:03', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(61, 3, '2022-05-17 05:40:57', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(62, 3, '2022-05-17 05:41:08', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(63, 1, '2022-05-17 05:59:41', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(64, 3, '2022-05-17 06:03:43', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(65, 1, '2022-05-17 06:11:43', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(66, 1, '2022-05-17 07:41:38', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.4951.67\",\"platform\":\"10.0\"}'),
(67, 1, '2022-05-19 17:27:35', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.0.0\",\"platform\":\"10.0\"}'),
(68, 1, '2022-05-20 04:02:10', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.0.0\",\"platform\":\"10.0\"}'),
(69, 1, '2022-05-20 04:08:31', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"101.0.0.0\",\"platform\":\"10.0\"}'),
(70, 1, '2022-06-12 15:23:55', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"102.0.0.0\",\"platform\":\"10.0\"}'),
(71, 1, '2022-06-13 01:56:45', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"102.0.0.0\",\"platform\":\"10.0\"}'),
(72, 1, '2022-06-18 11:52:21', '::1', 'User Logged in', 'Chrome', 'WebKit', 'Windows', '{\"browser\":\"102.0.0.0\",\"platform\":\"10.0\"}');

-- --------------------------------------------------------

--
-- Table structure for table `user_metas`
--

CREATE TABLE `user_metas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `meta_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_metas`
--

INSERT INTO `user_metas` (`id`, `user_id`, `meta_key`, `meta_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'profile_display_name', 'admin', '2022-03-29 23:38:36', '2022-03-29 23:38:36'),
(2, 1, 'profile_avatar_bg', 'danger', '2022-03-29 23:38:36', '2022-03-29 23:38:36'),
(3, 1, 'profile_display_full_name', 'on', '2022-03-29 23:38:36', '2022-03-29 23:38:36'),
(4, 1, 'setting_activity_log', 'on', '2022-03-29 23:38:36', '2022-03-29 23:38:36'),
(5, 1, 'setting_unusual_activity', 'on', '2022-03-29 23:38:36', '2022-03-29 23:38:36'),
(6, 1, 'registration_method', 'system', '2022-03-29 23:38:36', '2022-03-29 23:38:36'),
(7, 1, 'email_verified', '2022-03-30 05:38:36', '2022-03-29 23:38:36', '2022-03-29 23:38:36'),
(8, 1, 'email_verified_last', '2022-03-30 05:38:36', '2022-03-29 23:38:36', '2022-03-29 23:38:36'),
(9, 1, 'first_login_at', '2022-03-30 05:38:43', '2022-03-29 23:38:43', '2022-03-29 23:38:43'),
(10, 2, 'profile_display_name', 'Asad', '2022-03-30 00:18:19', '2022-03-30 00:18:19'),
(11, 2, 'profile_avatar_bg', 'danger', '2022-03-30 00:18:19', '2022-03-30 00:18:19'),
(12, 2, 'profile_display_full_name', 'off', '2022-03-30 00:18:19', '2022-03-30 00:49:26'),
(13, 2, 'setting_activity_log', 'on', '2022-03-30 00:18:19', '2022-03-30 00:18:19'),
(14, 2, 'setting_unusual_activity', 'on', '2022-03-30 00:18:19', '2022-03-30 00:18:19'),
(15, 2, 'registration_method', 'email', '2022-03-30 00:18:19', '2022-03-30 00:18:19'),
(16, 2, 'first_login_at', '2022-03-30 06:48:18', '2022-03-30 00:48:18', '2022-03-30 00:48:18'),
(17, 2, 'profile_phone', '01633571444', '2022-03-30 00:49:26', '2022-03-30 00:49:26'),
(18, 2, 'profile_dob', '10/06/1994', '2022-03-30 00:49:26', '2022-03-30 00:49:26'),
(19, 2, 'profile_country', 'Bangladesh', '2022-03-30 00:49:26', '2022-03-30 00:49:26'),
(20, 2, 'email_verified', '2022-03-30 10:48:47', '2022-03-30 04:48:47', '2022-03-30 04:48:47'),
(21, 2, 'email_verified_last', '2022-03-30 10:48:47', '2022-03-30 04:48:47', '2022-03-30 04:48:47'),
(22, 3, 'profile_display_name', 'Asaduzzaman', '2022-04-07 11:12:21', '2022-04-07 11:12:21'),
(23, 3, 'profile_avatar_bg', 'pink', '2022-04-07 11:12:21', '2022-04-07 11:12:21'),
(24, 3, 'profile_display_full_name', 'on', '2022-04-07 11:12:21', '2022-04-07 11:12:21'),
(25, 3, 'setting_activity_log', 'on', '2022-04-07 11:12:21', '2022-04-07 11:12:21'),
(26, 3, 'setting_unusual_activity', 'on', '2022-04-07 11:12:21', '2022-04-07 11:12:21'),
(27, 3, 'registration_method', 'email', '2022-04-07 11:12:21', '2022-04-07 11:12:21'),
(28, 3, 'first_login_at', '2022-04-07 17:13:00', '2022-04-07 11:13:00', '2022-04-07 11:13:00'),
(35, 5, 'profile_display_name', 'eseitsoft', '2022-04-07 11:32:41', '2022-04-07 11:32:41'),
(36, 5, 'profile_avatar_bg', 'blue', '2022-04-07 11:32:41', '2022-04-07 11:32:41'),
(37, 5, 'profile_display_full_name', 'on', '2022-04-07 11:32:41', '2022-04-07 11:32:41'),
(38, 5, 'setting_activity_log', 'on', '2022-04-07 11:32:41', '2022-04-07 11:32:41'),
(39, 5, 'setting_unusual_activity', 'on', '2022-04-07 11:32:41', '2022-04-07 11:32:41'),
(40, 5, 'registration_method', 'email', '2022-04-07 11:32:41', '2022-04-07 11:32:41'),
(41, 6, 'profile_display_name', 'Nerob', '2022-04-07 12:12:02', '2022-04-07 12:12:02'),
(42, 6, 'profile_avatar_bg', 'secondary', '2022-04-07 12:12:02', '2022-04-07 12:12:02'),
(43, 6, 'profile_display_full_name', 'on', '2022-04-07 12:12:02', '2022-04-07 12:12:02'),
(44, 6, 'setting_activity_log', 'on', '2022-04-07 12:12:02', '2022-04-07 12:12:02'),
(45, 6, 'setting_unusual_activity', 'on', '2022-04-07 12:12:02', '2022-04-07 12:12:02'),
(46, 6, 'registration_method', 'email', '2022-04-07 12:12:02', '2022-04-07 12:12:02'),
(47, 6, 'profile_country', 'Afghanistan', '2022-04-07 12:12:02', '2022-04-07 12:12:02'),
(48, 6, 'email_verified', '2022-04-07 18:12:23', '2022-04-07 12:12:23', '2022-04-07 12:12:23'),
(49, 6, 'email_verified_last', '2022-04-07 18:12:23', '2022-04-07 12:12:23', '2022-04-07 12:12:23'),
(50, 6, 'first_login_at', '2022-04-07 18:12:44', '2022-04-07 12:12:44', '2022-04-07 12:12:44');

-- --------------------------------------------------------

--
-- Table structure for table `verify_tokens`
--

CREATE TABLE `verify_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verify` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verify_tokens`
--

INSERT INTO `verify_tokens` (`id`, `user_id`, `email`, `token`, `code`, `verify`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin@gmail.com', '8cb41f94b4f0b8bb108c289c750f6ffbd409a4d5', '836891', '2022-03-30 05:38:36', '2022-03-29 23:38:36', '2022-03-29 23:38:36'),
(2, 2, 'ronydiu1@gmail.com', '8d61cf24b9f8d53953e98fa62c26cc581232d5a4', '527804', '2022-03-30 10:48:47', '2022-03-30 00:18:19', '2022-03-30 04:48:47'),
(3, 3, 'asadcse100@gmail.com', 'd69303222f07251a9ec65dfe7a4069ac1d400a92', '238775', '2022-05-16 11:02:39', '2022-04-07 11:12:21', '2022-05-16 23:01:54'),
(5, 5, 'eseitsoft@gmail.com', '0462096eeaa3c22e72308d170f1fa9d750afb1c7', '135992', NULL, '2022-04-07 11:32:41', '2022-04-07 11:32:41'),
(6, 6, 'ronydiu2@gmail.com', 'de4c56b903b9962d52e7f0088128d0a892ccbdf9', '323837', '2022-04-07 18:12:23', '2022-04-07 12:12:02', '2022-04-07 12:12:23');

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_methods`
--

CREATE TABLE `withdraw_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_amount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_amount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fees` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currencies` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `countries` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_templates_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iv_actions`
--
ALTER TABLE `iv_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iv_actions_type_id_index` (`type_id`);

--
-- Indexes for table `iv_invests`
--
ALTER TABLE `iv_invests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iv_ledgers`
--
ALTER TABLE `iv_ledgers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iv_profits`
--
ALTER TABLE `iv_profits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iv_profits_user_id_index` (`user_id`);

--
-- Indexes for table `iv_schemes`
--
ALTER TABLE `iv_schemes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `iv_schemes_slug_unique` (`slug`);

--
-- Indexes for table `iv_scheme_metas`
--
ALTER TABLE `iv_scheme_metas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `languages_code_unique` (`code`);

--
-- Indexes for table `ledgers`
--
ALTER TABLE `ledgers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ledgers_transaction_id_unique` (`transaction_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_methods_slug_unique` (`slug`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_codes`
--
ALTER TABLE `referral_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referral_codes_code_unique` (`code`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_tnx_unique` (`tnx`),
  ADD KEY `transactions_user_id_index` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_metas`
--
ALTER TABLE `user_metas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verify_tokens`
--
ALTER TABLE `verify_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `withdraw_methods_slug_unique` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `iv_actions`
--
ALTER TABLE `iv_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `iv_invests`
--
ALTER TABLE `iv_invests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `iv_ledgers`
--
ALTER TABLE `iv_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `iv_profits`
--
ALTER TABLE `iv_profits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `iv_schemes`
--
ALTER TABLE `iv_schemes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `iv_scheme_metas`
--
ALTER TABLE `iv_scheme_metas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ledgers`
--
ALTER TABLE `ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_codes`
--
ALTER TABLE `referral_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_activities`
--
ALTER TABLE `user_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `user_metas`
--
ALTER TABLE `user_metas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `verify_tokens`
--
ALTER TABLE `verify_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
