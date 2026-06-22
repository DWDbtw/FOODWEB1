<?php
session_start();
require_once 'Includes/db_connect.php';

// Получаем товары из БД
$query = "SELECT * FROM menu_items WHERE is_available = true ORDER BY category, name";
$result = pg_query($conn, $query);
$items = pg_fetch_all($result);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Суши-меню</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #1a1a1a;
            color: #ffffff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Навигация */
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid #333;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            color: #888;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #fff;
        }

        .nav-links a.active {
            color: #fff;
        }

        .cart-icon-wrapper {
            position: relative;
            display: inline-block;
        }

        /* Новая круглая кнопка корзины */
        .cart-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
            font-size: 20px;
        }

        .cart-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .cart-btn svg {
            width: 24px;
            height: 24px;
            fill: none;
            stroke: #ffffff;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4444;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        /* Меню товаров */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 30px 0;
        }

        .menu-item {
            background: #2a2a2a;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .menu-item-image {
            width: 100%;
            height: 200px;
            background: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #666;
            position: relative;
            overflow: hidden;
        }

        .menu-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .menu-item-content {
            padding: 18px;
        }

        .menu-item-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #fff;
        }

        .menu-item-description {
            font-size: 14px;
            color: #999;
            line-height: 1.4;
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .menu-item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-item-price {
            font-size: 20px;
            font-weight: 700;
            color: #ff6b35;
        }

        .menu-item-price span {
            font-size: 14px;
            font-weight: 400;
            color: #888;
        }

        .add-to-cart-btn {
            background: #ff6b35;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .add-to-cart-btn:hover {
            background: #e55a2b;
        }

        /* Модальное окно для товара */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: #2a2a2a;
            border-radius: 20px;
            max-width: 700px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 30px;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 28px;
            color: #888;
            cursor: pointer;
            background: none;
            border: none;
            transition: color 0.3s;
        }

        .modal-close:hover {
            color: #fff;
        }

        .modal-image {
            width: 100%;
            height: 300px;
            background: #333;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            color: #666;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .modal-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .modal-description {
            font-size: 16px;
            color: #aaa;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .modal-nutrition {
            background: #333;
            padding: 15px;
            border-radius: 12px;
            margin: 15px 0;
        }

        .modal-nutrition h4 {
            color: #888;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .modal-nutrition-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .modal-nutrition-item {
            text-align: center;
        }

        .modal-nutrition-item .value {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
        }

        .modal-nutrition-item .label {
            font-size: 12px;
            color: #888;
        }

        .modal-price {
            font-size: 24px;
            font-weight: 700;
            color: #ff6b35;
            margin: 15px 0;
        }

        .modal-add-btn {
            width: 100%;
            padding: 14px;
            background: #ff6b3