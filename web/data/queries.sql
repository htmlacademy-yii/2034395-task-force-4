use taskforce;

INSERT INTO category (name, icon)
VALUES ('Курьерские услуги', 'courier'),
       ('Уборка', 'clean'),
       ('Переезды', 'cargo'),
       ('Компьютерная помощь', 'neo'),
       ('Ремонт квартирный', 'flat'),
       ('Ремонт техники', 'repair'),
       ('Красота', 'beauty'),
       ('Фото', 'photo');

INSERT INTO task (status, creation_date, title, details, category_id, customer_id, executor_id, city_id, budget, execution_date)
VALUES ('new', NOW(), 'test', 'test', 1, 1, 2, 1, 10000, '2022-10-25 16:00:00');