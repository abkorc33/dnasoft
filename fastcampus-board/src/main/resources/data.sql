-- 123 게시글
insert into article (title, content, created_by, modified_by, created_at, modified_at, hashtag) values
('Quisque ut erat.', 'Vestibulum quam sapien, varius ut, blandit non, interdum in, ante.', 'Kamilah', 'Murial', '2021-05-30 23:53:46', '2021-03-10 08:48:50', '#pink');

insert into article_comment (article_id, content, created_by, modified_by, created_at, modified_at) values
('1', 'Vestibulum quam sapien, varius ut, blandit non, interdum in, ante.', 'Kamilah', 'Murial', '2021-05-30 23:53:46', '2021-03-10 08:48:50');