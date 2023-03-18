CREATE TABLE USERS(
    username CHAR(8) NOT NULL,
    password VARCHAR(999) NOT NULL,
    role VARCHAR(25) NOT NULL,
    PRIMARY KEY(username),
    CONSTRAINT chk_role CHECK
        (role IN ('moderator', 'publisher'))
);

CREATE TABLE ARTICLE(
    article_id VARCHAR(10) NOT NULL,
    content VARCHAR(999) NOT NULL,
    date_de_publication DATE NOT NULL,
    author VARCHAR(8) NOT NULL,
    PRIMARY KEY(article_id),
    FOREIGN KEY(author) REFERENCES USERS(username)
);

CREATE TABLE LIKES(
    article_id VARCHAR(10) NOT NULL,
    id_username CHAR(8) NOT NULL,
    PRIMARY KEY(article_id, id_username),
    FOREIGN KEY(article_id) REFERENCES ARTICLE(article_id)
);

CREATE TABLE DISLIKES(
    article_id VARCHAR(10) NOT NULL,
    id_username CHAR(8) NOT NULL,
    PRIMARY KEY(article_id, id_username),
    FOREIGN KEY(article_id) REFERENCES ARTICLE(article_id)
);

/*
INSERT INTO `users` (`username`, `password`, `role`) VALUES
    ('maxiwere', '0236bfa420bcb17e716e364e15b592e54a49ffabe248d54debc83b933cba64ce', 'moderator'),
    ('otsu', 'e8118a06c716700aed9af6c900107c02dbd226abc34935bd9ab83f3ca7a77e73', 'moderator'),
    ('iutprof', 'b38bb9429239744b50dfc9ef13d1a96b1985eb2b1afc9d056d3650b97c015cb7', 'moderator'),
    ('bonbily', '8e3c6948098ae3149f733a34631597918ead9b9be0e86171f74bee7121323066', 'publisher'),
    ('yahyanft', '700a9493ae0585465a816dd5ad4244ef181f16b5de9374b3afb0a7759457dabb', 'publisher'),
    ('fujitoo', 'bf9f601d680c6aacca166393fc57ba3bbf0e6ace3f865252270b09de3034e4fb', 'publisher'),
    ('riperpro', 'a680a492637fe0bf4270cb90701c3ac41eabce648e3f67805c7580c649d199de', 'publisher'),
    ('baran', 'a060587546cf0b8beed94f9701b613203fadc098062e222777e82f50b6497474', 'publisher');
COMMIT;
 */