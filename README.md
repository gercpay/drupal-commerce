# Модуль GercPay для Drupal 8-10 Commerce 2

Creator: [MustPay](https://mustpay.tech)<br>
Tags: GercPay, Commerce, payment, payment gateway, credit card, Visa, Mastercard, Apple Pay, Google Pay<br>
Requires at least: Drupal 8.8<br>
Ліцензія: GNU GPL v3.0<br>
License URI: [License](https://opensource.org/licenses/GPL-3.0)

Для роботи модуля у вас повинні бути встановлені CMS **Drupal 8.8-10.x** та плагін електронної комерції **Commerce 2.x-3.x**.

# Встановлення

1. Розархівувати папку з кодом модуля та скопіювати в каталог *{your_site}/modules* із збереженням структури папок.

2. В адміністративному розділі сайту зайти до підрозділу «Extend».

3. Активувати модуль **Commerce GercPay Payment** та натиснути **«Install»**.

4. Перейти до розділу *«Commerce -> Конфігурація -> Payment gateways»* та натиснути кнопку **Add payment gateway**.

5. **ВАЖЛИВО!**
   - *Назва платіжної системи* (**Name**): **GercPay Payment**;
   - *Ідентифікатор платіжної системи* (**Machine name**): **gercpay_payment**.

6. Заповнити дані торговця значеннями, отриманими від платіжної системи:
   - *Ідентифікатор торговця (Merchant ID)*;
   - *Секретний ключ (Secret key)*.

7. Зберегти налаштування платіжного методу.

Модуль готовий до роботи.

*Модуль протестований для роботи з Drupal (8.9.11, 9.3.9, 10.0.7), Commerce 8.x-2.35, PHP 8.1.*