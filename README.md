## Usuarios

| Caso de uso                              | Endpoint     | Método HTTP |
| ---------------------------------------- | ------------ | ----------- |
| Login                                    | /login       | POST        |
| Registro                                 | /registro    | POST        |
| Cierre de sesión                         | /logout      | POST        |
| Editar usuario (solo para administrador) | /usuario/{id}| PUT         |

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

## Roles

| Caso de uso            | Endpoint   | Método HTTP |
| ---------------------- | ---------- | ----------- |
| Buscar todos los roles | /roles     | GET         |
| Buscar un rol por id   | /roles/{id}| GET         |
| Crear un rol           | /roles/new | POST        |
| Editar un rol          | /roles/{id}| PATCH       |
