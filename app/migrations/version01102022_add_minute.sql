-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 01 oct. 2022 à 19:00.
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

ALTER TABLE `controle_tech` ADD `minute` VARCHAR(255) NULL DEFAULT NULL AFTER `report`;

COMMIT;