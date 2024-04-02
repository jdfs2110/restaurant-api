# Casos de uso con sus endpoints

## Roles

| Caso de uso            | Endpoint   | Método HTTP |
| ---------------------- | ---------- | ----------- |
| Buscar todos los roles | /roles     | GET         |
| Buscar un rol por id   | /roles/{id}| GET         |
| Crear un rol           | /roles/new | POST        |
| Editar un rol          | /roles/{id}| PATCH       |
| Buscar todos los usuarios de un rol concreto | /roles/{id}/usuarios | GET |

## Usuarios

| Caso de uso                              | Endpoint     | Método HTTP |
| ---------------------------------------- | ------------ | ----------- |
| Login                                    | /login       | POST        |
| Registro                                 | /registro    | POST        |
| Cierre de sesión                         | /logout      | POST        |
| Editar usuario (solo para administrador) | /usuario/{id}| PUT         |

## Categorias

| Caso de uso                           | Endpoint             | Método HTTP |
| ------------------------------------- | -------------------- | ----------- |
| Buscar todas las categorias           | /categorias          | GET         |
| Buscar una categoría por id           | /categorias/{id}     | GET         |
| Eliminar una categoría (admin)        | /categorias/{id}     | DELETE      |
| Crear una categoría                   | /categorias/new      | POST        |
| Editar una categoría                  | /categorias/{id}     | PUT         |
| Buscar todos los productos de una categoría | /categorias/{id}/productos | GET | 

## Productos

| Caso de uso                           | Endpoint             | Método HTTP |
| ------------------------------------- | -------------------- | ----------- |
| Buscar todos los productos            | /productos           | GET         |
| Buscar un producto por id             | /productos/{id}      | GET         |
| Eliminar un producto                  | /productos/{id}      | DELETE      |
| Crear un nuevo producto               | /productos/new       | POST        |
| Buscar el stock de un producto        | /productos/{id}/stock| GET         |
| Buscar el stock de todos los productos| /productos/stock (en decisión) | GET|

## Mesas

| Caso de uso            | Endpoint   | Método HTTP |
| ---------------------- | ---------- | ----------- |
| Buscar todas las mesas | /mesas     | GET         |
| Buscar una mesa por id | /mesas/{id}| GET         |
| Crear una mesa         | /mesas/new | POST        |
| Editar una mesa        | /mesas/{id}| PUT / PATCH |
| Eliminar una mesa      | /mesas/{id}| DELETE      |

## Pedidos (la  tabla ´facturas´ podría ser descartada)

| Caso de uso                    | Endpoint              | Método HTTP |
| ------------------------------ | --------------------- | ----------- |
| Buscar todos los pedidos       | /pedidos              | GET         |
| Buscar un pedido por id        | /pedidos/{id}         | GET         |
| Crear un pedido                | /pedidos/new          | POST        |
| Editar un pedido (admin)       | /pedidos/{id}         | PUT / PATCH |
| Buscar las líneas de un pedido | /pedidos/{id}/lineas  | GET         |
| Buscar la factura de un pedido | /pedidos/{id}/factura | GET         |

## Lineas

| Caso de uso                    | Endpoint              | Método HTTP |
| ------------------------------ | --------------------- | ----------- |
| Añadir una nueva línea (teoricamente se le asigna el id del pedido en el cuerpo de la peticion)         | /lineas/new | POST |
| Editar una línea | /lineas/{id} | PUT / PATCH |
| Eliminar una línea | /lineas/{id} | DELETE | 

## Facturas (podría ser descartada)

| Caso de uso                    | Endpoint              | Método HTTP |
| ------------------------------ | --------------------- | ----------- |
| Buscar todas las facturas      | /facturas             | GET         |
| Buscar una factura por id      | /facturas/{id}        | GET         |
| Crear una factura              | /facturas/new         | POST        |
| Eliminar una factura (admin)   | /facturas/{id}        | DELETE      |

---------------------
<br>

# Tablas de la Base de Datos

## Roles

```sql
CREATE TABLE roles(
  id INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR2 NOT NULL,
  PRIMARY KEY(id)
);
```

## Usuarios

```sql
CREATE TABLE usuarios(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR2 NOT NULL,
  email VARCHAR2 UNIQUE NOT NULL,
  password VARCHAR2 NOT NULL,
  estado BOOLEAN DEFAULT true NOT NULL, -- Alta / Baja
  fecha_ingreso DATE NOT NULL,
  id_rol INT NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_rol) REFERENCES roles(id)
);
```

## Categorias (WIP)

```sql
CREATE TABLE categorias(
  id INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR2 NOT NULL,
  -- foto BASE64 NOT NULL
  PRIMARY KEY(id)
);
```

## Productos

```sql
CREATE TABLE productos(
  id INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR2 NOT NULL,
  precio FLOAT NOT NULL DEFAULT 0,
  activo BOOLEAN NOT NULL DEFAULT TRUE,
  PRIMARY KEY(id)
);
```

## Stock

```sql
CREATE TABLE stock(
  id INT NOT NULL AUTO_INCREMENT,
  cantidad INT NOT NULL DEFAULT 0,
  id_producto INT NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_producto) REFERENCES productos(id)
);
```

## Mesas

```sql
CREATE TABLE mesas(
  id INT NOT NULL AUTO_INCREMENT,
  capacidad_maxima INT NOT NULL DEFAULT 0,
  estado INT NOT NULL,
  PRIMARY KEY(id),
  CHECK estado <= 2 AND estado >= 0
  );
```

## Pedidos

```sql
CREATE TABLE pedidos(
  id INT NOT NULL AUTO_INCREMENT,
  fecha DATETIME NOT NULL,
  estado INT NOT NULL,
  numero_comensales INT NOT NULL DEFAULT 1,
  id_mesa INT NOT NULL,
  id_usuario INT NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_mesa) REFERENCES mesas(id),
  FOREIGN KEY(id_usuario) REFERENCES usuarios(id),
  CHECK estado<= 3 AND estado >= 0
); 
```

## Lineas

```sql
CREATE TABLE lineas(
  id INT NOT NULL AUTO_INCREMENT,
  precio FLOAT NOT NULL DEFAULT 0,
  cantidad INT NOT NULL DEFAULT 1,
  id_producto INT NOT NULL,
  id_pedido INT NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_producto) REFERENCES productos(id),
  FOREIGN KEY(id_pedido) REFERENCES pedidos(id)
);
```

## Facturas (quizá se descarte)

```sql
CREATE TABLE facturas(
  id INT NOT NULL AUTO_INCREMENT,
  fecha DATETIME NOT NULL,
  id_pedido INT NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_pedido) REFERENCES pedidos(id)
);
```
