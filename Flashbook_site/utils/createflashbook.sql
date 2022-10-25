CREATE TABLE `users`(
    user_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    user_pwd VARCHAR(70) NOT NULL,
    user_pseudo VARCHAR(45) NOT NULL,
    user_picture VARCHAR(255),
    user_isadmin TINYINT (1) NOT NULL DEFAULT 0
);

CREATE TABLE `groups`(
    group_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    group_name VARCHAR(255) NOT NULL
);

CREATE TABLE `isingroups`(
    user_id INT NOT NULL,
    group_id INT NOT NULL,
    PRIMARY KEY (user_id, group_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (group_id) REFERENCES groups(group_id) 
);

CREATE TABLE `messages`(
    message_id INT (11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    user_id INT(11) NOT NULL,
    group_id INT (11) NOT NULL,
    message_content TEXT NOT NULL,
    message_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (group_id) REFERENCES groups(group_id)
);