CREATE TABLE article (
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(25) NOT NULL,
    author VARCHAR(25) NOT NULL,
    published DATE NOT NULL,
    content TEXT NOT NULL,
    dislikes INT NOT NULL,
    likes INT NOT NULL,
    PRIMARY KEY (id)
);