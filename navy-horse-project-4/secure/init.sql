-- TODO: Put ALL SQL in between `BEGIN TRANSACTION` and `COMMIT`
BEGIN TRANSACTION;

-- TODO: create tables

  CREATE TABLE `users` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `username` TEXT NOT NULL UNIQUE,
    `password` TEXT NOT NULL
  );

  CREATE TABLE `sessions` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `user_id` INTEGER NOT NULL,
    `session_id` INTEGER NOT NULL UNIQUE
  );

  CREATE TABLE `images` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `file_name` TEXT NOT NULL,
    `file_extension` TEXT NOT NULL,
    `name` TEXT NOT NULL,
    `description` TEXT NOT NULL,
    `source` TEXT
  );

  CREATE TABLE `officer_images` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `file_name` TEXT NOT NULL,
    `file_extension` TEXT NOT NULL,
    `source` TEXT
  );

  CREATE TABLE `officers` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `name` TEXT NOT NULL,
    `position` TEXT NOT NULL,
    `biography` TEXT NOT NULL,
    `officer_image_id` INTEGER NOT NULL
  );

  CREATE TABLE `performances` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `performance` TEXT NOT NULL UNIQUE
  );

  CREATE TABLE `performance_images` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `image_id` INTEGER NOT NULL,
    `performance_id` INTEGER NOT NULL
  );

-- TODO: initial seed data

  INSERT INTO images (id, file_name, file_extension, name, description, source) VALUES (1, 'asia-night-2018.jpg', 'jpg', 'Asia Night 2018', 'We are seen in Duffield Hall at the 2018 Asia Night (Asia Night Mart), performing with a dragon.', 'Cornell Lion Dance');
  INSERT INTO images (id, file_name, file_extension, name, description, source) VALUES (2, 'asia-night-2018-2.jpg', 'jpg', 'Asia Night 2018 2', 'Asia Night 2018, performing with a dragon.', 'Cornell Lion Dance');
  INSERT INTO images (id, file_name, file_extension, name, description, source) VALUES (3, 'asia-night-2019.jpg', 'jpg', 'Asia Night 2019', 'Group club photo at Asia Night 2019 (The Five Elements).', 'Cornell Lion Dance');
  INSERT INTO images (id, file_name, file_extension, name, description, source) VALUES (4, 'asia-night-group-photo.jpg', 'jpg', 'Asia Night 2018 Group Photo', 'Group photo for Asia Night 2018!', 'Cornell Lion Dance');
  INSERT INTO images (id, file_name, file_extension, name, description, source) VALUES (5, 'balch-performance.jpg', 'jpg', 'Asian and Asian American Welcome Reception 2018', 'We performed at Balch Hall in North Campus for the Asian and Asian American Welcome Reception (A3WR) for the new freshmen of Cornell.', 'Cornell Lion Dance');
  INSERT INTO images (id, file_name, file_extension, name, description, source) VALUES (6, 'club-members.jpg', 'jpg', 'Club Members', 'Some of our club members! Winnie, Varun, and Mena (left to right).', 'Cornell Lion Dance');
  INSERT INTO images (id, file_name, file_extension, name, description, source) VALUES (7, 'asianight2018stand.jpg', 'jpg', 'Lion Dance Stand at Asia Night 2018', 'Lion Dance had a stand in Asia Night 2018 and gave free ramen noodles!', 'Cornell Lion Dance');
  INSERT INTO images (id, file_name, file_extension, name, description, source) VALUES (8, 'group-selfie.jpg', 'jpg', 'Group Selfie', 'A club selfie after practice.', 'Cornell Lion Dance');
  INSERT INTO images (id, file_name, file_extension, name, description, source) VALUES (9, 'lion-dance.jpg', 'jpg', 'Lion Dance', 'Photo in front of Bailey Hall.', 'Cornell Lion Dance');

  INSERT INTO performances (id, performance) VALUES (1, 'Asia Night 2018');
  INSERT INTO performances (id, performance) VALUES (2, 'Asia Night 2019');
  INSERT INTO performances (id, performance) VALUES (3, 'A3WR Performance 2018');

  INSERT INTO performance_images (id, image_id, performance_id) VALUES (1, 1, 1);
  INSERT INTO performance_images (id, image_id, performance_id) VALUES (2, 3, 2);
  INSERT INTO performance_images (id, image_id, performance_id) VALUES (3, 5, 3);

  INSERT INTO officers (id, name, position, biography, officer_image_id) VALUES (1, 'Kenneth Fang', 'President', 'Kenneth is an Electrical and Computer Engineering (ECE) and Computer Science double major in the class of 2020.', 1);
  INSERT INTO officers (id, name, position, biography, officer_image_id) VALUES (2, 'Karrie Shi', 'Practice Coordinator', 'Karrie is a Computer Science major in the class of 2019.', 2);
  INSERT INTO officers (id, name, position, biography, officer_image_id) VALUES (3, 'Christine Leung', 'Treasurer', 'Christine is an Applied Economics and Management (AEM) and International Development double major in the class of 2020.', 3);
  INSERT INTO officers (id, name, position, biography, officer_image_id) VALUES (4, 'Varun Iyengar', 'Publicity Chair', 'Varun is a Computer Science major and East Asian Studies minor in the class of 2021.', 4);

  INSERT INTO officer_images (id, file_name, file_extension, source) VALUES (1, 'officer1.jpg', 'jpg', 'Lion Dance');
  INSERT INTO officer_images (id, file_name, file_extension, source) VALUES (2, 'officer2.png', 'png', 'Lion Dance');
  INSERT INTO officer_images (id, file_name, file_extension, source) VALUES (3, 'officer3.jpg', 'jpg', 'Lion Dance');
  INSERT INTO officer_images (id, file_name, file_extension, source) VALUES (4, 'officer4.jpg', 'jpg', 'Lion Dance');

  INSERT INTO users (id, username, password) VALUES (1, 'liondance','$2y$10$//KIYAD6ux5JVkrENFjoGuEO/RbFSGtHZk4XKI66eROXf/PK0FvIG'); --password: kingofthejungle



-- TODO: FOR HASHED PASSWORDS, LEAVE A COMMENT WITH THE PLAIN TEXT PASSWORD!

COMMIT;
