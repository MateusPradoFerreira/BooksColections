create database bookscolection;
use bookscolection;

create table usuarios (
	id_user int unsigned not null auto_increment primary key,
    nome_usuario varchar(40) not null,
    senha varchar(32) not null,
    email varchar(100) not null,
    telefone varchar(17) not null
);

select * from usuarios;
select * from livros;
select * from pedidos;
select * from pedidos_recusados;
select * from trocas_andamento;
select * from trocas_finalizadas;

create table livros (
	id_livro int unsigned not null auto_increment primary key,
    nome_livro varchar(60) not null,
    imagem_livro varchar(1000),
    descricao varchar(1000),
    data_postagem date,
    id_usuario_dono int not null
);

create table pedidos (
	id_pedido int unsigned not null auto_increment primary key,
    id_usuario_dono int not null,
    id_usuario_pedinte int not null,
    id_livro int not null,
    stat varchar(20)
);

create table pedidos_recusados (
	id_pedido_recusado int unsigned not null auto_increment primary key,
    id_usuario_dono int not null,
    id_usuario_pedinte int not null,
    motivo varchar(200) not null,
    nome_livro varchar(60) not null
);

create table trocas_andamento (
	id_trocas_andamento int unsigned not null auto_increment primary key,
    id_pedido_01 int not null,
    id_pedido_02 int not null,
    aceitos int not null,
    aceitou_primeiro int not null,
    statu varchar(200) not null
);

create table trocas_finalizadas (
	id_trocas_finalizada int unsigned not null auto_increment primary key,
    nome_livro_01 varchar(60) not null,
    id_user_01 varchar(40) not null,
    nome_livro_02 varchar(60) not null,
    id_user_02 varchar(40) not null,
    data_finalizacao date
);

insert into usuarios (nome_usuario, senha, email, telefone)
values ('Mateus', md5('123'), 'mateus@gmail.com', '(69) 98483-2312');

insert into usuarios (nome_usuario, senha, email, telefone)
values ('Bia', md5('123'), 'bia@gmail.com', '(69) 98476-4322');