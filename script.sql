grant
alter,
alter routine,
create,
create routine,
create temporary tables,
create view,
delete,
drop,
event,
execute,
index,
insert
,
lock tables,
references,
select,
    show view, trigger,
update on restaurant.* to jdfs;

create table cache (
                       `key` varchar(255) not null primary key,
                       value mediumtext not null,
                       expiration int not null
) collate = utf8mb4_unicode_ci;

create table cache_locks (
                             `key` varchar(255) not null primary key,
                             owner varchar(255) not null,
                             expiration int not null
) collate = utf8mb4_unicode_ci;

create table categorias (
                            id bigint unsigned auto_increment primary key,
                            nombre varchar(255) not null,
                            foto varchar(255) not null,
                            created_at timestamp null,
                            updated_at timestamp null,
                            deleted_at timestamp null
) collate = utf8mb4_unicode_ci;

create table failed_jobs (
                             id bigint unsigned auto_increment primary key,
                             uuid varchar(255) not null,
                             connection text not null,
                             queue text not null,
                             payload longtext not null,
                             exception longtext not null,
                             failed_at timestamp default CURRENT_TIMESTAMP not null,
                             constraint failed_jobs_uuid_unique unique (uuid)
) collate = utf8mb4_unicode_ci;

create table job_batches (
                             id varchar(255) not null primary key,
                             name varchar(255) not null,
                             total_jobs int not null,
                             pending_jobs int not null,
                             failed_jobs int not null,
                             failed_job_ids longtext not null,
                             options mediumtext null,
                             cancelled_at int null,
                             created_at int not null,
                             finished_at int null
) collate = utf8mb4_unicode_ci;

create table jobs (
                      id bigint unsigned auto_increment primary key,
                      queue varchar(255) not null,
                      payload longtext not null,
                      attempts tinyint unsigned not null,
                      reserved_at int unsigned null,
                      available_at int unsigned not null,
                      created_at int unsigned not null
) collate = utf8mb4_unicode_ci;

create index jobs_queue_index on jobs (queue);

create table mesas (
                       id bigint unsigned auto_increment primary key,
                       capacidad_maxima int default 0 not null,
                       estado int default 0 not null,
                       created_at timestamp null,
                       updated_at timestamp null,
                       deleted_at timestamp null
) collate = utf8mb4_unicode_ci;

create table migrations (
                            id int unsigned auto_increment primary key,
                            migration varchar(255) not null,
                            batch int not null
) collate = utf8mb4_unicode_ci;

create table password_reset_tokens (
                                       email varchar(255) not null primary key,
                                       token varchar(255) not null,
                                       created_at timestamp null
) collate = utf8mb4_unicode_ci;

create table personal_access_tokens (
                                        id bigint unsigned auto_increment primary key,
                                        tokenable_type varchar(255) not null,
                                        tokenable_id bigint unsigned not null,
                                        name varchar(255) not null,
                                        token varchar(64) not null,
                                        abilities text null,
                                        last_used_at timestamp null,
                                        expires_at timestamp null,
                                        created_at timestamp null,
                                        updated_at timestamp null,
                                        constraint personal_access_tokens_token_unique unique (token)
) collate = utf8mb4_unicode_ci;

create index personal_access_tokens_tokenable_type_tokenable_id_index on personal_access_tokens (tokenable_type, tokenable_id);

create table productos (
                           id bigint unsigned auto_increment primary key,
                           nombre varchar(255) not null,
                           precio double default 0 not null,
                           activo tinyint(1) default 1 not null,
                           created_at timestamp null,
                           updated_at timestamp null,
                           deleted_at timestamp null,
                           id_categoria bigint unsigned not null,
                           foto varchar(255) not null,
                           constraint productos_id_categoria_foreign foreign key (id_categoria) references categorias (id)
) collate = utf8mb4_unicode_ci;

create table roles (
                       id bigint unsigned auto_increment primary key,
                       nombre varchar(255) not null,
                       created_at timestamp null,
                       updated_at timestamp null,
                       deleted_at timestamp null
) collate = utf8mb4_unicode_ci;

create table sessions (
                          id varchar(255) not null primary key,
                          user_id bigint unsigned null,
                          ip_address varchar(45) null,
                          user_agent text null,
                          payload longtext not null,
                          last_activity int not null
) collate = utf8mb4_unicode_ci;

create index sessions_last_activity_index on sessions (last_activity);

create index sessions_user_id_index on sessions (user_id);

create table stock (
                       id bigint unsigned auto_increment primary key,
                       cantidad int default 0 not null,
                       id_producto bigint unsigned not null,
                       created_at timestamp null,
                       updated_at timestamp null,
                       deleted_at timestamp null,
                       constraint uk_stock_producto unique (id_producto),
                       constraint stock_id_producto_foreign foreign key (id_producto) references productos (id)
) collate = utf8mb4_unicode_ci;

create table users (
                       id bigint unsigned auto_increment primary key,
                       name varchar(255) not null,
                       email varchar(255) not null,
                       password varchar(255) not null,
                       estado tinyint(1) default 1 not null,
                       fecha_ingreso date not null,
                       remember_token varchar(100) null,
                       created_at timestamp null,
                       updated_at timestamp null,
                       deleted_at timestamp null,
                       id_rol bigint unsigned not null,
                       constraint users_email_unique unique (email),
                       constraint users_id_rol_foreign foreign key (id_rol) references roles (id)
) collate = utf8mb4_unicode_ci;

create table pedidos (
                         id bigint unsigned auto_increment primary key,
                         fecha datetime not null,
                         estado int default 0 not null,
                         precio double default 0 not null,
                         numero_comensales int default 1 not null,
                         id_mesa bigint unsigned not null,
                         id_usuario bigint unsigned not null,
                         created_at timestamp null,
                         updated_at timestamp null,
                         deleted_at timestamp null,
                         constraint pedidos_id_mesa_foreign foreign key (id_mesa) references mesas (id),
                         constraint pedidos_id_usuario_foreign foreign key (id_usuario) references users (id)
) collate = utf8mb4_unicode_ci;

create table facturas (
                          id bigint unsigned auto_increment primary key,
                          fecha datetime not null,
                          id_pedido bigint unsigned not null,
                          created_at timestamp null,
                          updated_at timestamp null,
                          deleted_at timestamp null,
                          constraint uk_facturas_pedido unique (id_pedido),
                          constraint facturas_id_pedido_foreign foreign key (id_pedido) references pedidos (id)
) collate = utf8mb4_unicode_ci;

create table lineas (
                        id bigint unsigned auto_increment primary key,
                        precio double default 0 not null,
                        cantidad int default 1 not null,
                        id_producto bigint unsigned not null,
                        id_pedido bigint unsigned not null,
                        tipo varchar(255) not null,
                        estado int not null,
                        created_at timestamp null,
                        updated_at timestamp null,
                        deleted_at timestamp null,
                        constraint lineas_id_pedido_foreign foreign key (id_pedido) references pedidos (id),
                        constraint lineas_id_producto_foreign foreign key (id_producto) references productos (id)
) collate = utf8mb4_unicode_ci;
