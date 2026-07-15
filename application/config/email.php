<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Isi bagian ini dengan SMTP lo.
 * Kalau pake Gmail: wajib pake "App Password", bukan password akun biasa.
 * (Google Account -> Security -> 2-Step Verification -> App Passwords)
 */
$config['protocol']    = 'smtp';
$config['smtp_host']   = 'smtp.gmail.com'; // ganti sesuai provider
$config['smtp_port']   = 465;
$config['smtp_user']   = 'apgchannel11@gmail.com';
$config['smtp_pass']   = 'xplmgybiyapsnvzm';
$config['smtp_crypto'] = 'ssl';
$config['smtp_timeout'] = 30;

$config['mailtype']    = 'html';
$config['charset']     = 'utf-8';
$config['newline']     = "\r\n";
$config['wordwrap']    = TRUE;
