-- Run once in phpMyAdmin: removes plaintext passwords stored earlier in password_bckup.
UPDATE `tb_partner` SET `password_bckup` = '';
