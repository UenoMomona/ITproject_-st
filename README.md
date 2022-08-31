# ITproject_1st
# データベース構造は下記の通りです

create table answers (
  id int(11) not null auto_increment comment '解答ID'
  , user_id int(11) not null comment 'ユーザーID'
  , question_id int(11) not null comment '質問ID'
  , answer text not null comment '解答'
  , created_at timestamp default current_timestamp not null comment '投稿日時'
  , updated_at timestamp default current_timestamp on update current_timestamp not null comment '更新日時'
  , constraint answers_PKC primary key (id)
) comment '解答' ;

create table questions (
  id int(11) not null auto_increment comment '質問ID'
  , user_id int(11) not null comment 'ユーザーID'
  , title text not null comment 'タイトル'
  , grade int(2) not null comment '学年'
  , body text not null comment '質問'
  , solve_check int(1) default 0 not null comment '解決済み'
  , created_at timestamp default current_timestamp not null comment '投稿日時'
  , updated_at timestamp default current_timestamp on update current_timestamp not null comment '更新日時'
  , constraint questions_PKC primary key (id)
) comment '質問' ;

create table users (
  id int(11) not null auto_increment comment 'ユーザーID'
  , name VARCHAR(32) not null comment '名前'
  , mail VARCHAR(255) not null comment 'メールアドレス'
  , password VARCHAR(255) not null comment 'パスワード'
  , created_at timestamp default current_timestamp not null comment '登録日時'
  , updated_at timestamp default current_timestamp on update current_timestamp not null comment '更新日時'
  , constraint users_PKC primary key (id)
) comment 'ユーザー' ;

create table login_checks (
  id INT(11) not null auto_increment comment 'ログインチェックID'
  , user_id int(11) not null comment 'ユーザーID'
  , verify_key VARCHAR(64) not null comment '認証キー'
  , end_time timestamp not null comment '期限'
  , created_at timestamp default current_timestamp not null comment '登録日時'
  , updated_at timestamp default current_timestamp on update current_timestamp not null comment '更新日時'
  , constraint login_checks_PKC primary key (id)
) comment 'ログインチェック' ;
